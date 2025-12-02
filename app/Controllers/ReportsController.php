<?php

namespace App\Controllers;

use App\Models\ManualLiquidationModel;
use App\Models\AtmLiquidationModel;
use App\Models\AtmLiquidationDetailModel;
use App\Models\ScholarshipRecipientModel;
use App\Models\DisbursementModel;
use App\Models\UserModel;

class ReportsController extends BaseController
{
    protected $manualLiquidationModel;
    protected $atmLiquidationModel;
    protected $atmDetailModel;
    protected $recipientModel;
    protected $disbursementModel;
    protected $userModel;
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->manualLiquidationModel = new ManualLiquidationModel();
        $this->atmLiquidationModel = new AtmLiquidationModel();
        $this->atmDetailModel = new AtmLiquidationDetailModel();
        $this->recipientModel = new ScholarshipRecipientModel();
        $this->disbursementModel = new DisbursementModel();
        $this->userModel = new UserModel();
        $this->session = session();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        // Only allow admin access
        if ($user['role'] !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        // Get filters
        $filters = [
            'campus' => $this->request->getVar('campus'),
            'scholarship_type' => $this->request->getVar('scholarship_type'),
            'status' => $this->request->getVar('status'),
            'semester' => $this->request->getVar('semester'),
            'academic_year' => $this->request->getVar('academic_year'),
            'date_from' => $this->request->getVar('date_from'),
            'date_to' => $this->request->getVar('date_to'),
            'report_type' => $this->request->getVar('report_type') ?? 'submissions_by_recipient'
        ];

        // Get report data based on report type
        $reportData = $this->getReportData($filters);

        // Get filter options
        $campuses = $this->recipientModel->select('campus')
                                        ->distinct()
                                        ->where('campus IS NOT NULL')
                                        ->findAll();
        
        $scholarshipTypes = $this->recipientModel->select('scholarship_type')
                                               ->distinct()
                                               ->where('scholarship_type IS NOT NULL')
                                               ->findAll();

        $disbursing_officers = $this->userModel->getUsersByRole('disbursing_officer');
        $scholarship_coordinators = $this->userModel->getUsersByRole('scholarship_coordinator');

        $data = [
            'title' => 'Admin Reports',
            'user' => $user,
            'filters' => $filters,
            'report_data' => $reportData,
            'campuses' => $campuses,
            'scholarship_types' => $scholarshipTypes,
            'disbursing_officers' => $disbursing_officers,
            'scholarship_coordinators' => $scholarship_coordinators
        ];

        return view('admin/reports', $data);
    }

    private function getReportData($filters)
    {
        switch ($filters['report_type']) {
            case 'submissions_by_recipient':
                return $this->getLiquidationSubmissionsByRecipient($filters);
            case 'submissions_by_campus':
                return $this->getLiquidationSubmissionsByCampus($filters);
            case 'submissions_by_scholarship':
                return $this->getLiquidationSubmissionsByScholarship($filters);
            case 'approved_pending_proofs':
                return $this->getApprovedPendingProofs($filters);
            case 'voucher_summary':
                return $this->getVoucherSummary($filters);
            case 'officer_performance':
                return $this->getOfficerPerformance($filters);
            case 'coordinator_performance':
                return $this->getCoordinatorPerformance($filters);
            default:
                return $this->getLiquidationSubmissionsByRecipient($filters);
        }
    }

    private function getLiquidationSubmissionsByRecipient($filters)
    {
        $builder = $this->db->table('scholarship_recipients sr');
        $builder->select('sr.recipient_id, 
                         sr.first_name, 
                         sr.last_name, 
                         sr.campus,
                         sr.scholarship_type,
                         COUNT(DISTINCT ml.id) as manual_liquidations,
                         COUNT(DISTINCT ald.id) as atm_liquidations,
                         COALESCE(SUM(ml.amount), 0) + COALESCE(SUM(ald.amount), 0) as total_amount,
                         COUNT(DISTINCT CASE WHEN ml.status = "approved" THEN ml.id END) + COUNT(DISTINCT CASE WHEN ald.status = "approved" THEN ald.id END) as approved_count,
                         COUNT(DISTINCT CASE WHEN ml.status = "pending" THEN ml.id END) + COUNT(DISTINCT CASE WHEN ald.status = "pending" THEN ald.id END) as pending_count');
        
        $builder->join('manual_liquidations ml', 'ml.recipient_id = sr.id', 'left');
        $builder->join('atm_liquidation_details ald', 'ald.recipient_id = sr.id', 'left');
        
        $this->applyFilters($builder, $filters);
        
        $builder->groupBy('sr.id, sr.recipient_id, sr.first_name, sr.last_name, sr.campus, sr.scholarship_type');
        $builder->orderBy('total_amount', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    private function getLiquidationSubmissionsByCampus($filters)
    {
        $builder = $this->db->table('scholarship_recipients sr');
        $builder->select('sr.campus,
                         COUNT(DISTINCT sr.id) as total_recipients,
                         COUNT(DISTINCT ml.id) as manual_liquidations,
                         COUNT(DISTINCT ald.id) as atm_liquidations,
                         COALESCE(SUM(ml.amount), 0) + COALESCE(SUM(ald.amount), 0) as total_amount,
                         AVG(CASE WHEN ml.amount > 0 THEN ml.amount END) as avg_manual_amount,
                         AVG(CASE WHEN ald.amount > 0 THEN ald.amount END) as avg_atm_amount');
        
        $builder->join('manual_liquidations ml', 'ml.recipient_id = sr.id', 'left');
        $builder->join('atm_liquidation_details ald', 'ald.recipient_id = sr.id', 'left');
        
        $this->applyFilters($builder, $filters);
        
        $builder->groupBy('sr.campus');
        $builder->orderBy('total_amount', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    private function getLiquidationSubmissionsByScholarship($filters)
    {
        $builder = $this->db->table('scholarship_recipients sr');
        $builder->select('sr.scholarship_type,
                         COUNT(DISTINCT sr.id) as total_recipients,
                         COUNT(DISTINCT ml.id) as manual_liquidations,
                         COUNT(DISTINCT ald.id) as atm_liquidations,
                         COALESCE(SUM(ml.amount), 0) + COALESCE(SUM(ald.amount), 0) as total_amount,
                         AVG(CASE WHEN ml.amount > 0 THEN ml.amount END) as avg_manual_amount,
                         AVG(CASE WHEN ald.amount > 0 THEN ald.amount END) as avg_atm_amount');
        
        $builder->join('manual_liquidations ml', 'ml.recipient_id = sr.id', 'left');
        $builder->join('atm_liquidation_details ald', 'ald.recipient_id = sr.id', 'left');
        
        $this->applyFilters($builder, $filters);
        
        $builder->groupBy('sr.scholarship_type');
        $builder->orderBy('total_amount', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    private function getApprovedPendingProofs($filters)
    {
        // Get manual liquidations
        $manualBuilder = $this->db->table('manual_liquidations ml');
        $manualBuilder->select('ml.id,
                               ml.voucher_number,
                               ml.amount,
                               ml.status,
                               ml.liquidation_date,
                               ml.campus,
                               ml.semester,
                               ml.academic_year,
                               sr.recipient_id,
                               sr.first_name,
                               sr.last_name,
                               sr.scholarship_type,
                               u.username as disbursing_officer,
                               "manual" as liquidation_type');
        $manualBuilder->join('scholarship_recipients sr', 'sr.id = ml.recipient_id', 'left');
        $manualBuilder->join('users u', 'u.id = ml.disbursing_officer_id', 'left');
        $manualBuilder->whereIn('ml.status', ['approved', 'pending']);
        
        // Apply filters for manual
        if (!empty($filters['campus'])) {
            $manualBuilder->where('sr.campus', $filters['campus']);
        }
        if (!empty($filters['scholarship_type'])) {
            $manualBuilder->where('sr.scholarship_type', $filters['scholarship_type']);
        }
        if (!empty($filters['semester'])) {
            $manualBuilder->where('ml.semester', $filters['semester']);
        }
        if (!empty($filters['academic_year'])) {
            $manualBuilder->where('ml.academic_year', $filters['academic_year']);
        }

        // Get ATM liquidations
        $atmBuilder = $this->db->table('atm_liquidation_details ald');
        $atmBuilder->select('ald.id,
                            COALESCE(ald.reference_number, "N/A") as voucher_number,
                            ald.amount,
                            ald.status,
                            ald.transaction_date as liquidation_date,
                            sr.campus,
                            ald.semester,
                            ald.academic_year,
                            sr.recipient_id,
                            sr.first_name,
                            sr.last_name,
                            sr.scholarship_type,
                            COALESCE(u.username, "System") as disbursing_officer,
                            "atm" as liquidation_type');
        $atmBuilder->join('scholarship_recipients sr', 'sr.id = ald.recipient_id', 'left');
        $atmBuilder->join('users u', 'u.id = ald.created_by', 'left');
        $atmBuilder->whereIn('ald.status', ['approved', 'pending']);
        
        // Apply filters for ATM
        if (!empty($filters['campus'])) {
            $atmBuilder->where('sr.campus', $filters['campus']);
        }
        if (!empty($filters['scholarship_type'])) {
            $atmBuilder->where('sr.scholarship_type', $filters['scholarship_type']);
        }
        if (!empty($filters['semester'])) {
            $atmBuilder->where('ald.semester', $filters['semester']);
        }
        if (!empty($filters['academic_year'])) {
            $atmBuilder->where('ald.academic_year', $filters['academic_year']);
        }

        // Get results
        $manualResults = $manualBuilder->get()->getResultArray();
        $atmResults = $atmBuilder->get()->getResultArray();
        
        return array_merge($manualResults, $atmResults);
    }

    private function getVoucherSummary($filters)
    {
        $builder = $this->db->table('manual_liquidations ml');
        $builder->select('ml.voucher_number,
                         ml.amount,
                         ml.liquidation_date,
                         ml.status,
                         ml.campus,
                         ml.semester,
                         ml.academic_year,
                         sr.recipient_id,
                         sr.first_name,
                         sr.last_name,
                         sr.scholarship_type,
                         u.username as disbursing_officer,
                         sc.username as scholarship_coordinator');
        
        $builder->join('scholarship_recipients sr', 'sr.id = ml.recipient_id', 'left');
        $builder->join('users u', 'u.id = ml.disbursing_officer_id', 'left');
        $builder->join('users sc', 'sc.id = ml.scholarship_coordinator_id', 'left');
        
        $this->applyFilters($builder, $filters);
        
        $builder->orderBy('ml.liquidation_date', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    private function getOfficerPerformance($filters)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.id,
                         u.username,
                         u.campus,
                         COUNT(DISTINCT ml.id) as total_liquidations,
                         COUNT(DISTINCT d.id) as total_disbursements,
                         SUM(CASE WHEN ml.status = "approved" THEN 1 ELSE 0 END) as approved_liquidations,
                         SUM(CASE WHEN ml.status = "pending" THEN 1 ELSE 0 END) as pending_liquidations,
                         SUM(CASE WHEN ml.status = "rejected" THEN 1 ELSE 0 END) as rejected_liquidations,
                         COALESCE(SUM(ml.amount), 0) as total_amount_liquidated,
                         COALESCE(SUM(d.amount), 0) as total_amount_disbursed');
        
        $builder->join('manual_liquidations ml', 'ml.disbursing_officer_id = u.id', 'left');
        $builder->join('disbursements d', 'd.disbursing_officer_id = u.id', 'left');
        
        $builder->where('u.role', 'disbursing_officer');
        
        if (!empty($filters['campus'])) {
            $builder->where('u.campus', $filters['campus']);
        }
        
        if (!empty($filters['semester'])) {
            $builder->where('ml.semester', $filters['semester']);
        }
        
        if (!empty($filters['academic_year'])) {
            $builder->where('ml.academic_year', $filters['academic_year']);
        }
        
        $builder->groupBy('u.id, u.username, u.campus');
        $builder->orderBy('total_amount_liquidated', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    private function getCoordinatorPerformance($filters)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.id,
                         u.username,
                         u.campus,
                         COUNT(DISTINCT ml.id) as total_liquidations,
                         SUM(CASE WHEN ml.status = "verified" THEN 1 ELSE 0 END) as verified_liquidations,
                         SUM(CASE WHEN ml.status = "approved" THEN 1 ELSE 0 END) as approved_liquidations,
                         SUM(CASE WHEN ml.status = "rejected" THEN 1 ELSE 0 END) as rejected_liquidations,
                         COALESCE(SUM(ml.amount), 0) as total_amount_processed,
                         COUNT(DISTINCT sr.id) as total_recipients_managed');
        
        $builder->join('manual_liquidations ml', 'ml.scholarship_coordinator_id = u.id', 'left');
        $builder->join('scholarship_recipients sr', 'sr.campus = u.campus', 'left');
        
        $builder->where('u.role', 'scholarship_coordinator');
        
        if (!empty($filters['campus'])) {
            $builder->where('u.campus', $filters['campus']);
        }
        
        if (!empty($filters['semester'])) {
            $builder->where('ml.semester', $filters['semester']);
        }
        
        if (!empty($filters['academic_year'])) {
            $builder->where('ml.academic_year', $filters['academic_year']);
        }
        
        $builder->groupBy('u.id, u.username, u.campus');
        $builder->orderBy('total_amount_processed', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    private function applyFilters($builder, $filters)
    {
        if (!empty($filters['campus'])) {
            $builder->where('sr.campus', $filters['campus']);
        }
        
        if (!empty($filters['scholarship_type'])) {
            $builder->where('sr.scholarship_type', $filters['scholarship_type']);
        }
        
        if (!empty($filters['status'])) {
            $builder->groupStart();
            $builder->where('ml.status', $filters['status']);
            $builder->orWhere('ald.status', $filters['status']);
            $builder->groupEnd();
        }
        
        if (!empty($filters['semester'])) {
            $builder->groupStart();
            $builder->where('ml.semester', $filters['semester']);
            $builder->orWhere('ald.semester', $filters['semester']);
            $builder->groupEnd();
        }
        
        if (!empty($filters['academic_year'])) {
            $builder->groupStart();
            $builder->where('ml.academic_year', $filters['academic_year']);
            $builder->orWhere('ald.academic_year', $filters['academic_year']);
            $builder->groupEnd();
        }
        
        if (!empty($filters['date_from'])) {
            $builder->groupStart();
            $builder->where('ml.liquidation_date >=', $filters['date_from']);
            $builder->orWhere('ald.transaction_date >=', $filters['date_from']);
            $builder->groupEnd();
        }
        
        if (!empty($filters['date_to'])) {
            $builder->groupStart();
            $builder->where('ml.liquidation_date <=', $filters['date_to']);
            $builder->orWhere('ald.transaction_date <=', $filters['date_to']);
            $builder->groupEnd();
        }
    }

    private function applyFiltersToQuery($builder, $filters)
    {
        if (!empty($filters['campus'])) {
            $builder->where('sr.campus', $filters['campus']);
        }
        
        if (!empty($filters['scholarship_type'])) {
            $builder->where('sr.scholarship_type', $filters['scholarship_type']);
        }
        
        if (!empty($filters['semester'])) {
            $builder->groupStart()
                   ->where('ml.semester', $filters['semester'])
                   ->orWhere('ald.semester', $filters['semester'])
                   ->groupEnd();
        }
        
        if (!empty($filters['academic_year'])) {
            $builder->groupStart()
                   ->where('ml.academic_year', $filters['academic_year'])
                   ->orWhere('ald.academic_year', $filters['academic_year'])
                   ->groupEnd();
        }
        
        if (!empty($filters['date_from'])) {
            $builder->groupStart()
                   ->where('ml.liquidation_date >=', $filters['date_from'])
                   ->orWhere('ald.transaction_date >=', $filters['date_from'])
                   ->groupEnd();
        }
        
        if (!empty($filters['date_to'])) {
            $builder->groupStart()
                   ->where('ml.liquidation_date <=', $filters['date_to'])
                   ->orWhere('ald.transaction_date <=', $filters['date_to'])
                   ->groupEnd();
        }
    }

    public function export()
    {
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($this->session->get('user_id'));
        
        if ($user['role'] !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $filters = [
            'campus' => $this->request->getVar('campus'),
            'scholarship_type' => $this->request->getVar('scholarship_type'),
            'status' => $this->request->getVar('status'),
            'semester' => $this->request->getVar('semester'),
            'academic_year' => $this->request->getVar('academic_year'),
            'date_from' => $this->request->getVar('date_from'),
            'date_to' => $this->request->getVar('date_to'),
            'report_type' => $this->request->getVar('report_type') ?? 'submissions_by_recipient'
        ];

        $reportData = $this->getReportData($filters);
        
        // Generate CSV content based on report type
        $csvContent = $this->generateCSV($reportData, $filters['report_type']);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $csvContent,
            'filename' => 'admin_report_' . $filters['report_type'] . '_' . date('Y-m-d') . '.csv'
        ]);
    }

    private function generateCSV($data, $reportType)
    {
        $csv = [];
        
        switch ($reportType) {
            case 'submissions_by_recipient':
                $csv[] = ['Recipient ID', 'Name', 'Campus', 'Scholarship Type', 'Manual Liquidations', 'ATM Liquidations', 'Total Amount', 'Approved', 'Pending'];
                foreach ($data as $row) {
                    $csv[] = [
                        $row['recipient_id'],
                        $row['first_name'] . ' ' . $row['last_name'],
                        $row['campus'],
                        $row['scholarship_type'],
                        $row['manual_liquidations'],
                        $row['atm_liquidations'],
                        number_format($row['total_amount'], 2),
                        $row['approved_count'],
                        $row['pending_count']
                    ];
                }
                break;
            
            case 'submissions_by_campus':
                $csv[] = ['Campus', 'Total Recipients', 'Manual Liquidations', 'ATM Liquidations', 'Total Amount', 'Avg Manual Amount', 'Avg ATM Amount'];
                foreach ($data as $row) {
                    $csv[] = [
                        $row['campus'],
                        $row['total_recipients'],
                        $row['manual_liquidations'],
                        $row['atm_liquidations'],
                        number_format($row['total_amount'], 2),
                        number_format($row['avg_manual_amount'] ?? 0, 2),
                        number_format($row['avg_atm_amount'] ?? 0, 2)
                    ];
                }
                break;
                
            case 'officer_performance':
                $csv[] = ['Officer', 'Campus', 'Total Liquidations', 'Total Disbursements', 'Approved', 'Pending', 'Rejected', 'Amount Liquidated', 'Amount Disbursed'];
                foreach ($data as $row) {
                    $csv[] = [
                        $row['username'],
                        $row['campus'],
                        $row['total_liquidations'],
                        $row['total_disbursements'],
                        $row['approved_liquidations'],
                        $row['pending_liquidations'],
                        $row['rejected_liquidations'],
                        number_format($row['total_amount_liquidated'], 2),
                        number_format($row['total_amount_disbursed'], 2)
                    ];
                }
                break;
                
            // Add more report types as needed
            default:
                $csv[] = ['Data'];
                foreach ($data as $row) {
                    $csv[] = [json_encode($row)];
                }
        }
        
        return $csv;
    }
}