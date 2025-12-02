<?php

namespace App\Controllers;

use App\Models\AtmLiquidationModel;
use App\Models\AtmLiquidationDetailModel;
use App\Models\ScholarshipRecipientModel;
use App\Models\UserModel;

class AtmLiquidationController extends BaseController
{
    protected $atmLiquidationModel;
    protected $atmDetailModel;
    protected $recipientModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->atmLiquidationModel = new AtmLiquidationModel();
        $this->atmDetailModel = new AtmLiquidationDetailModel();
        $this->recipientModel = new ScholarshipRecipientModel();
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $filters = [
            'status' => $this->request->getGet('status'),
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year')
        ];

        // Get individual liquidations with recipient details
        $liquidations = $this->atmDetailModel->getLiquidationsWithRecipients($filters);
        
        // Get batch-level records with uploader details
        $batches = $this->atmLiquidationModel->getBatchesWithUploader($filters);
        
        // Add recipient count and total amount for each batch
        foreach ($batches as &$batch) {
            $batch['recipient_count'] = $this->atmDetailModel->where('atm_liquidation_id', $batch['id'])->countAllResults();
            $batch['total_amount'] = $this->atmDetailModel->where('atm_liquidation_id', $batch['id'])->selectSum('amount')->get()->getRow()->amount ?? 0;
        }

        $data = [
            'title' => 'ATM Liquidations',
            'liquidations' => $liquidations,
            'batches' => $batches,
            'filters' => $filters,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('atm_liquidation/index', $data);
    }

    public function create()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        // Fetch all scholarship recipients
        $recipients = $this->recipientModel->orderBy('last_name', 'ASC')->findAll();

        $data = [
            'title' => 'Create ATM Liquidation',
            'user' => $this->userModel->find($this->session->get('user_id')),
            'recipients' => $recipients
        ];

        return view('atm_liquidation/create', $data);
    }

    public function store()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();
        
        // Validate form data including file
        $rules = [
            'recipient_id' => 'required|numeric',
            'transaction_date' => 'required|valid_date',
            'amount' => 'required|decimal|greater_than[0]',
            'reference_number' => 'required|max_length[100]',
            'semester' => 'required|max_length[20]',
            'academic_year' => 'required|max_length[20]',
            'attachment_file' => 'uploaded[attachment_file]|max_size[attachment_file,10240]|ext_in[attachment_file,csv,xlsx,xls,pdf]'
        ];

        if (!$this->validate($rules)) {
            $errors = $validation->getErrors();
            log_message('error', 'ATM Liquidation validation failed: ' . json_encode($errors));
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', 'Please check all required fields and ensure file is attached.');
        }

        // Handle file upload first (required)
        $file = $this->request->getFile('attachment_file');
        
        if (!$file) {
            log_message('error', 'No file received in request');
            return redirect()->back()->withInput()->with('error', 'No file was uploaded. Please attach a file.');
        }
        
        if (!$file->isValid()) {
            $error = $file->getErrorString() . '(' . $file->getError() . ')';
            log_message('error', 'Invalid file upload: ' . $error);
            return redirect()->back()->withInput()->with('error', 'Invalid file: ' . $error);
        }
        
        if ($file->hasMoved()) {
            log_message('error', 'File has already been moved');
            return redirect()->back()->withInput()->with('error', 'File upload error: file has already been processed.');
        }

        // Ensure uploads directory exists
        $uploadPath = WRITEPATH . 'uploads/atm_liquidations/';
        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0755, true)) {
                log_message('error', 'Failed to create upload directory: ' . $uploadPath);
                return redirect()->back()->withInput()->with('error', 'Failed to create upload directory.');
            }
        }

        // Check if directory is writable
        if (!is_writable($uploadPath)) {
            log_message('error', 'Upload directory is not writable: ' . $uploadPath);
            return redirect()->back()->withInput()->with('error', 'Upload directory is not writable.');
        }

        // Get file extension and generate safe name
        $fileExtension = $file->getExtension();
        $fileName = $file->getRandomName();
        
        try {
            // Move file to uploads directory
            if (!$file->move($uploadPath, $fileName)) {
                log_message('error', 'Failed to move file to: ' . $uploadPath . $fileName);
                return redirect()->back()->withInput()->with('error', 'Failed to move uploaded file.');
            }
            
            $filePath = $uploadPath . $fileName;
            
            // Verify file was actually saved
            if (!file_exists($filePath)) {
                log_message('error', 'File not found after move: ' . $filePath);
                return redirect()->back()->withInput()->with('error', 'File upload verification failed.');
            }
            
            // Determine file type
            $fileType = in_array($fileExtension, ['xlsx', 'xls']) ? 'excel' : $fileExtension;
            
            log_message('info', 'File uploaded successfully: ' . $filePath);
            
        } catch (\Exception $e) {
            log_message('error', 'Exception during file upload: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to upload file: ' . $e->getMessage());
        }

        // Prepare liquidation data
        $liquidationData = [
            'recipient_id' => $this->request->getPost('recipient_id'),
            'transaction_date' => $this->request->getPost('transaction_date'),
            'amount' => $this->request->getPost('amount'),
            'reference_number' => $this->request->getPost('reference_number'),
            'semester' => $this->request->getPost('semester'),
            'academic_year' => $this->request->getPost('academic_year'),
            'remarks' => $this->request->getPost('remarks'),
            'file_path' => $filePath,
            'file_type' => $fileType,
            'status' => 'verified',
            'created_by' => $this->session->get('user_id'),
            'verified_by' => $this->session->get('user_id'),
            'verified_at' => date('Y-m-d H:i:s')
        ];

        // Insert liquidation record
        try {
            $liquidationId = $this->atmDetailModel->insert($liquidationData);
            
            if ($liquidationId) {
                log_message('info', 'ATM liquidation created successfully: ID ' . $liquidationId);
                return redirect()->to('/atm-liquidation')->with('success', 'ATM liquidation created successfully.');
            } else {
                // Remove uploaded file if database insert fails
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                log_message('error', 'Failed to insert liquidation record to database');
                return redirect()->back()->withInput()->with('error', 'Failed to create liquidation record.');
            }
        } catch (\Exception $e) {
            // Remove uploaded file if database insert fails
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            log_message('error', 'Exception during database insert: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        }
    }

    public function batchUploadForm()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Batch Upload ATM Liquidation',
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('atm_liquidation/batch_upload', $data);
    }

    public function processBatch()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();
        
        if (!$this->validate([
            'batch_name' => 'required|max_length[100]',
            'semester' => 'required|max_length[20]',
            'academic_year' => 'required|max_length[20]',
            'liquidation_file' => 'uploaded[liquidation_file]|max_size[liquidation_file,10240]|ext_in[liquidation_file,csv,xlsx,xls]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors())->with('error', 'Please check all required fields.');
        }

        $file = $this->request->getFile('liquidation_file');
        
        if (!$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'Invalid file uploaded.');
        }

        // Get file extension and type BEFORE moving the file
        $fileExtension = $file->getClientExtension();
        $fileType = in_array($fileExtension, ['xlsx', 'xls']) ? 'excel' : 'csv';

        // Ensure uploads directory exists
        $uploadPath = WRITEPATH . 'uploads/atm_liquidations/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = $file->getRandomName();
        $file->move($uploadPath, $fileName);
        $filePath = $uploadPath . $fileName;

        // Create batch record first
        $batchName = $this->request->getPost('batch_name');
        $semester = $this->request->getPost('semester');
        $academicYear = $this->request->getPost('academic_year');
        $batchRemarks = $this->request->getPost('remarks');
        
        $batchData = [
            'batch_name' => $batchName,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'uploaded_by' => $this->session->get('user_id'),
            'semester' => $semester,
            'academic_year' => $academicYear,
            'status' => 'verified',
            'remarks' => $batchRemarks
        ];
        
        $batchId = $this->atmLiquidationModel->insert($batchData);
        
        if (!$batchId) {
            $errors = $this->atmLiquidationModel->errors();
            log_message('error', 'Failed to create batch record. Errors: ' . json_encode($errors));
            log_message('error', 'Batch data: ' . json_encode($batchData));
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            $errorMsg = 'Failed to create batch record.';
            if (!empty($errors)) {
                $errorMsg .= ' ' . implode(', ', $errors);
            }
            
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }

        // Parse file and create individual liquidation records
        try {
            $records = $this->parseUploadedFile($filePath, $fileType);
            
            log_message('info', 'Parsed records from file: ' . json_encode($records));
            
            $successCount = 0;
            $errorCount = 0;
            
            log_message('info', 'Processing ' . count($records) . ' records from batch upload');
            
            if (empty($records)) {
                return redirect()->back()->withInput()->with('error', 'No valid records found in the uploaded file.');
            }
            
            foreach ($records as $index => $record) {
                // Trim whitespace from all fields
                $record['recipient_code'] = trim($record['recipient_code']);
                $record['transaction_date'] = trim($record['transaction_date']);
                $record['amount'] = trim($record['amount']);
                $record['reference_number'] = trim($record['reference_number']);
                
                // Find recipient by code
                $recipient = $this->recipientModel->where('recipient_id', $record['recipient_code'])->first();
                
                if (!$recipient) {
                    log_message('warning', "Row " . ($index + 2) . ": Recipient not found - " . $record['recipient_code']);
                    $errorCount++;
                    continue;
                }
                
                $liquidationData = [
                    'atm_liquidation_id' => $batchId,
                    'recipient_id' => $recipient['id'],
                    'transaction_date' => $record['transaction_date'],
                    'amount' => $record['amount'],
                    'reference_number' => $record['reference_number'],
                    'semester' => $semester,
                    'academic_year' => $academicYear,
                    'remarks' => $batchRemarks,
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'status' => 'verified',
                    'created_by' => $this->session->get('user_id'),
                    'verified_by' => $this->session->get('user_id'),
                    'verified_at' => date('Y-m-d H:i:s')
                ];
                
                if ($this->atmDetailModel->insert($liquidationData)) {
                    $successCount++;
                    log_message('info', "Row " . ($index + 2) . ": Successfully created liquidation for " . $record['recipient_code']);
                } else {
                    $errorCount++;
                    $errors = $this->atmDetailModel->errors();
                    log_message('error', "Row " . ($index + 2) . ": Failed to insert - " . json_encode($errors));
                }
            }
            
            // Update batch with processing results
            $this->atmLiquidationModel->update($batchId, [
                'total_records' => $successCount + $errorCount,
                'processed_records' => $successCount
            ]);
            
            $message = "Batch processed: {$successCount} records created successfully.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} records failed.";
            }
            
            return redirect()->to('/atm-liquidation')->with('success', $message);
            
        } catch (\Exception $e) {
            log_message('error', 'Batch processing failed: ' . $e->getMessage());
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return redirect()->back()->withInput()->with('error', 'Failed to process file: ' . $e->getMessage());
        }
    }

    private function parseUploadedFile($filePath, $fileType)
    {
        $records = [];
        
        if ($fileType === 'csv') {
            if (($handle = fopen($filePath, 'r')) !== false) {
                $header = fgetcsv($handle);
                
                // Find column indices dynamically
                $recipientCodeIdx = array_search('recipient_code', $header);
                $transactionDateIdx = array_search('transaction_date', $header);
                $amountIdx = array_search('amount', $header);
                $referenceNumberIdx = array_search('reference_number', $header);
                
                // Fall back to positional indices if headers not found (old format)
                if ($recipientCodeIdx === false) {
                    $recipientCodeIdx = 0;
                    $transactionDateIdx = 1;
                    $amountIdx = 2;
                    $referenceNumberIdx = 3;
                }
                
                $rowNum = 1;
                while (($row = fgetcsv($handle)) !== false) {
                    $rowNum++;
                    // Skip empty rows
                    if (empty($row[$recipientCodeIdx]) || trim($row[$recipientCodeIdx]) === '') {
                        continue;
                    }
                    if (isset($row[$recipientCodeIdx]) && isset($row[$transactionDateIdx]) && 
                        isset($row[$amountIdx]) && isset($row[$referenceNumberIdx])) {
                        $records[] = [
                            'recipient_code' => trim($row[$recipientCodeIdx]),
                            'transaction_date' => trim($row[$transactionDateIdx]),
                            'amount' => trim($row[$amountIdx]),
                            'reference_number' => trim($row[$referenceNumberIdx])
                        ];
                    } else {
                        log_message('warning', "Row {$rowNum}: Insufficient columns - " . implode(',', $row));
                    }
                }
                fclose($handle);
            }
        } else {
            // Excel parsing using PhpSpreadsheet
            require_once ROOTPATH . 'vendor/autoload.php';
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Get header row and find column indices
            $header = array_shift($rows);
            $recipientCodeIdx = array_search('recipient_code', $header);
            $transactionDateIdx = array_search('transaction_date', $header);
            $amountIdx = array_search('amount', $header);
            $referenceNumberIdx = array_search('reference_number', $header);
            
            // Fall back to positional indices if headers not found
            if ($recipientCodeIdx === false) {
                $recipientCodeIdx = 0;
                $transactionDateIdx = 1;
                $amountIdx = 2;
                $referenceNumberIdx = 3;
            }
            
            $rowNum = 1;
            foreach ($rows as $row) {
                $rowNum++;
                // Skip empty rows
                if (empty($row[$recipientCodeIdx]) || trim($row[$recipientCodeIdx]) === '') {
                    continue;
                }
                if (isset($row[$recipientCodeIdx]) && isset($row[$transactionDateIdx]) && 
                    isset($row[$amountIdx]) && isset($row[$referenceNumberIdx])) {
                    $records[] = [
                        'recipient_code' => trim($row[$recipientCodeIdx]),
                        'transaction_date' => trim($row[$transactionDateIdx]),
                        'amount' => trim($row[$amountIdx]),
                        'reference_number' => trim($row[$referenceNumberIdx])
                    ];
                } else {
                    log_message('warning', "Row {$rowNum}: Insufficient columns");
                }
            }
        }
        
        return $records;
    }

    public function upload()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();
        
        // Log upload attempt
        log_message('info', 'File upload attempt by user: ' . $this->session->get('user_id'));
        
        if (!$this->validate([
            'batch_name' => 'required|max_length[100]',
            'semester' => 'required|max_length[20]',
            'academic_year' => 'required|max_length[20]',
            'liquidation_file' => 'uploaded[liquidation_file]|ext_in[liquidation_file,csv,xlsx,xls]'
        ])) {
            log_message('error', 'File upload validation failed: ' . json_encode($validation->getErrors()));
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $file = $this->request->getFile('liquidation_file');
        
        if (!$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'Invalid file uploaded or file has been moved.');
        }

        // Check if file exists and is readable before processing
        if (!$file->getTempName() || !is_readable($file->getTempName())) {
            return redirect()->back()->with('error', 'File is not accessible for processing.');
        }

        // Get file extension before moving
        $fileExtension = $file->getExtension();
        $fileType = $fileExtension === 'csv' ? 'csv' : 'excel';

        // Ensure uploads directory exists
        $uploadPath = ROOTPATH . 'writable/uploads/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Move file to uploads directory
        $fileName = $file->getRandomName();
        
        try {
            $file->move($uploadPath, $fileName);
        } catch (\Exception $e) {
            log_message('error', 'Failed to move uploaded file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to save uploaded file.');
        }
        
        $filePath = $uploadPath . $fileName;
        
        // Verify the file was moved successfully
        if (!file_exists($filePath)) {
            log_message('error', 'File was not found after move operation: ' . $filePath);
            return redirect()->back()->with('error', 'File upload failed - file not saved properly.');
        }

        // Save batch information
        $batchData = [
            'batch_name' => $this->request->getPost('batch_name'),
            'file_path' => $filePath,
            'file_type' => $fileType,
            'uploaded_by' => $this->session->get('user_id'),
            'semester' => $this->request->getPost('semester'),
            'academic_year' => $this->request->getPost('academic_year'),
            'status' => 'uploaded'
        ];

        $batchId = $this->atmLiquidationModel->insert($batchData);

        if ($batchId) {
            // Process file in background
            $this->processLiquidationFile($batchId, $filePath, $fileType);
            return redirect()->to('/atm-liquidation')->with('success', 'File uploaded successfully and processing started.');
        } else {
            return redirect()->back()->with('error', 'Failed to save batch information.');
        }
    }

    public function show($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        // Get individual liquidation with recipient details
        $builder = $this->atmDetailModel->db->table('atm_liquidation_details ald');
        $builder->select('ald.*, 
            sr.recipient_id as recipient_code, 
            CONCAT(sr.first_name, " ", sr.last_name) as recipient_name,
            sr.first_name, 
            sr.last_name, 
            sr.middle_name, 
            sr.campus');
        $builder->join('scholarship_recipients sr', 'ald.recipient_id = sr.id', 'left');
        $builder->where('ald.id', $id);
        
        $liquidation = $builder->get()->getRowArray();
        
        if (empty($liquidation)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'ATM Liquidation Details',
            'liquidation' => $liquidation,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('atm_liquidation/show', $data);
    }

    private function processLiquidationFile($batchId, $filePath, $fileType)
    {
        try {
            // Update status to processing
            $this->atmLiquidationModel->update($batchId, ['status' => 'processing']);

            $data = [];
            if ($fileType === 'csv') {
                $data = $this->processCSVFile($filePath);
            } else {
                $data = $this->processExcelFile($filePath);
            }

            // Update total records
            $this->atmLiquidationModel->update($batchId, [
                'total_records' => count($data),
                'processed_records' => count($data),
                'status' => 'processed'
            ]);

            // Insert details
            if (!empty($data)) {
                foreach ($data as &$record) {
                    $record['atm_liquidation_id'] = $batchId;
                }
                $this->atmDetailModel->bulkInsert($data);
            }

            // Auto-send to chairman if configured
            $this->autoSendToChairman($batchId);

        } catch (\Exception $e) {
            $this->atmLiquidationModel->update($batchId, [
                'status' => 'rejected',
                'remarks' => 'Processing failed: ' . $e->getMessage()
            ]);
        }
    }

    private function processCSVFile($filePath)
    {
        $data = [];
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== FALSE) {
                if (count($row) >= 4) {
                    // Find recipient by recipient_id
                    $recipient = $this->recipientModel->where('recipient_id', $row[0])->first();
                    if ($recipient) {
                        $data[] = [
                            'recipient_id' => $recipient['id'],
                            'transaction_date' => date('Y-m-d', strtotime($row[1])),
                            'amount' => floatval($row[2]),
                            'reference_number' => $row[3] ?? null,
                            'status' => 'pending'
                        ];
                    }
                }
            }
            fclose($handle);
        }
        return $data;
    }

    private function processExcelFile($filePath)
    {
        // This would require PhpSpreadsheet library
        // For now, return empty array
        return [];
    }

    private function autoSendToChairman($batchId)
    {
        // Update status to sent to chairman
        $this->atmLiquidationModel->updateStatus($batchId, 'sent_to_chairman');
        
        // Here you could add email notification logic
    }

    public function approve($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if ($user['role'] === 'scholarship_chairman') {
            $this->atmLiquidationModel->updateStatus($id, 'approved');
            $this->autoSendToAccounting($id);
            return redirect()->back()->with('success', 'Batch approved and sent to accounting.');
        } else {
            return redirect()->back()->with('error', 'Unauthorized to approve.');
        }
    }

    private function autoSendToAccounting($batchId)
    {
        $this->atmLiquidationModel->updateStatus($batchId, 'sent_to_accounting');
        // Add email notification logic here
    }

    public function reject($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if ($user['role'] === 'scholarship_chairman') {
            $remarks = $this->request->getPost('remarks');
            $this->atmDetailModel->update($id, [
                'status' => 'rejected',
                'remarks' => $remarks
            ]);
            return redirect()->back()->with('success', 'Liquidation rejected.');
        } else {
            return redirect()->back()->with('error', 'Unauthorized to reject.');
        }
    }

    public function verify($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if (in_array($user['role'], ['disbursing_officer', 'admin'])) {
            $this->atmDetailModel->update($id, ['status' => 'verified']);
            return redirect()->back()->with('success', 'Liquidation verified successfully.');
        } else {
            return redirect()->back()->with('error', 'Unauthorized to verify.');
        }
    }

    public function viewFile($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        // Get liquidation with recipient details
        $builder = $this->atmDetailModel->db->table('atm_liquidation_details ald');
        $builder->select('ald.*, 
            sr.recipient_id as recipient_code, 
            CONCAT(sr.first_name, " ", sr.last_name) as recipient_name');
        $builder->join('scholarship_recipients sr', 'ald.recipient_id = sr.id', 'left');
        $builder->where('ald.id', $id);
        
        $liquidation = $builder->get()->getRowArray();
        
        if (!$liquidation || empty($liquidation['file_path'])) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Check if file exists
        if (!file_exists($liquidation['file_path'])) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Parse CSV or Excel file
        $csvData = [];
        $headers = [];
        
        try {
            if ($liquidation['file_type'] === 'csv') {
                // Handle CSV files
                if (($handle = fopen($liquidation['file_path'], "r")) !== FALSE) {
                    // Read headers
                    $headers = fgetcsv($handle);
                    
                    // Read data rows (limit to first 1000 rows for performance)
                    $rowCount = 0;
                    while (($row = fgetcsv($handle)) !== FALSE && $rowCount < 1000) {
                        $csvData[] = $row;
                        $rowCount++;
                    }
                    fclose($handle);
                }
            } else {
                // Handle Excel files
                $this->convertExcelToCsvArray($liquidation['file_path'], $headers, $csvData);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error reading file: ' . $e->getMessage());
        }

        $data = [
            'title' => $liquidation['file_type'] === 'csv' ? 'CSV File Viewer' : 'Excel File Viewer',
            'liquidation' => $liquidation,
            'headers' => $headers,
            'csvData' => $csvData,
            'totalRows' => count($csvData),
            'isLimited' => count($csvData) >= 1000,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('atm_liquidation/view_csv', $data);
    }

    public function downloadFile($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $liquidation = $this->atmDetailModel->find($id);
        
        if (!$liquidation || empty($liquidation['file_path'])) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Check if file exists
        if (!file_exists($liquidation['file_path'])) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Force download
        return $this->response->download($liquidation['file_path'], null, true);
    }

    public function viewCsv($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $batch = $this->atmLiquidationModel->find($id);
        
        if (!$batch) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if file exists
        if (!file_exists($batch['file_path'])) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Parse CSV or Excel file
        $csvData = [];
        $headers = [];
        
        try {
            if ($batch['file_type'] === 'csv') {
                // Handle CSV files
                if (($handle = fopen($batch['file_path'], "r")) !== FALSE) {
                    // Read headers
                    $headers = fgetcsv($handle);
                    
                    // Read data rows (limit to first 1000 rows for performance)
                    $rowCount = 0;
                    while (($row = fgetcsv($handle)) !== FALSE && $rowCount < 1000) {
                        $csvData[] = $row;
                        $rowCount++;
                    }
                    fclose($handle);
                }
            } else {
                // Handle Excel files by converting first sheet to CSV-like array
                $this->convertExcelToCsvArray($batch['file_path'], $headers, $csvData);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error reading file: ' . $e->getMessage());
        }

        $data = [
            'title' => $batch['file_type'] === 'csv' ? 'CSV File Viewer' : 'Excel File Viewer',
            'batch' => $batch,
            'headers' => $headers,
            'csvData' => $csvData,
            'totalRows' => count($csvData),
            'isLimited' => count($csvData) >= 1000,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('atm_liquidation/view_csv', $data);
    }

    public function downloadCsv($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $batch = $this->atmLiquidationModel->find($id);
        
        if (!$batch) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if file exists
        if (!file_exists($batch['file_path'])) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Force download
        return $this->response->download($batch['file_path'], null, true);
    }

    private function convertExcelToCsvArray($filePath, &$headers, &$csvData)
    {
        // Simple Excel file reading for basic functionality
        // For now, we'll return a message that Excel viewing requires additional setup
        $headers = ['Message'];
        $csvData = [
            ['Excel file viewing is not fully implemented yet. Please download the file to view its contents.'],
            ['File Path: ' . basename($filePath)],
            ['File Type: Excel'],
            ['To enable Excel viewing, PhpSpreadsheet library needs to be installed.']
        ];
    }

    public function viewBatch($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        // Get batch record
        $batch = $this->atmLiquidationModel->find($id);
        
        if (!$batch) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get all detail records for this batch
        $details = $this->atmDetailModel
            ->select('atm_liquidation_details.*, 
                      scholarship_recipients.recipient_id as recipient_code,
                      scholarship_recipients.first_name,
                      scholarship_recipients.last_name,
                      scholarship_recipients.campus')
            ->join('scholarship_recipients', 'scholarship_recipients.id = atm_liquidation_details.recipient_id', 'left')
            ->where('atm_liquidation_details.atm_liquidation_id', $id)
            ->orderBy('atm_liquidation_details.created_at', 'DESC')
            ->findAll();

        // Calculate totals
        $totalAmount = 0;
        foreach ($details as $detail) {
            $totalAmount += $detail['amount'];
        }

        $data = [
            'title' => 'View Batch Liquidation',
            'batch' => $batch,
            'details' => $details,
            'totalAmount' => $totalAmount,
            'user' => $this->userModel->find($this->session->get('user_id'))
        ];

        return view('atm_liquidation/view_batch', $data);
    }

    public function downloadBatchFile($id)
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $batch = $this->atmLiquidationModel->find($id);
        
        if (!$batch) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if file exists
        if (!file_exists($batch['file_path'])) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Force download
        return $this->response->download($batch['file_path'], null, true);
    }
}
