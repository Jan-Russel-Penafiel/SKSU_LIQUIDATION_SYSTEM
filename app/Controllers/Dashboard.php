<?php

namespace App\Controllers;

use App\Models\ManualLiquidationModel;
use App\Models\AtmLiquidationModel;
use App\Models\AtmLiquidationDetailModel;
use App\Models\ScholarshipRecipientModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $manualLiquidationModel;
    protected $atmLiquidationModel;
    protected $atmLiquidationDetailModel;
    protected $recipientModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->manualLiquidationModel = new ManualLiquidationModel();
        $this->atmLiquidationModel = new AtmLiquidationModel();
        $this->atmLiquidationDetailModel = new AtmLiquidationDetailModel();
        $this->recipientModel = new ScholarshipRecipientModel();
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        // Check if user is logged in
        if (!$this->session->get('user_id')) {
            return redirect()->to('/login');
        }

        $filters = [
            'semester' => $this->request->getGet('semester'),
            'academic_year' => $this->request->getGet('academic_year'),
            'campus' => $this->request->getGet('campus')
        ];

        // Get dashboard statistics
        $manualStats = $this->manualLiquidationModel->getDashboardStatistics($filters);
        $atmStats = $this->atmLiquidationModel->getDashboardStatistics($filters);

        // Get recent activities
        $recentManual = $this->manualLiquidationModel->getLiquidationsWithRecipients($filters + ['limit' => 5]);
        $recentAtm = $this->atmLiquidationModel->getBatchesWithUploader($filters + ['limit' => 5]);

        // Get summary by campus
        $campuses = ['Main Campus', 'Kalamansig Campus', 'Palimbang Campus', 'Isulan Campus'];
        $campusStats = [];
        foreach ($campuses as $campus) {
            $campusFilter = $filters + ['campus' => $campus];
            
            // Manual liquidations for this campus
            $campusManualStats = $this->manualLiquidationModel->getDashboardStatistics($campusFilter);
            
            // Count unique recipients for manual liquidations in this campus
            $manualRecipientsBuilder = $this->manualLiquidationModel->db->table('manual_liquidations');
            $manualRecipientsBuilder->select('recipient_id');
            $manualRecipientsBuilder->distinct();
            $manualRecipientsBuilder->where('campus', $campus);
            $manualRecipientsBuilder->where('status !=', 'hidden');
            
            if (!empty($filters['semester'])) {
                $manualRecipientsBuilder->where('semester', $filters['semester']);
            }
            if (!empty($filters['academic_year'])) {
                $manualRecipientsBuilder->where('academic_year', $filters['academic_year']);
            }
            
            $manualRecipientCount = $manualRecipientsBuilder->countAllResults();
            $campusManualStats['recipient_count'] = $manualRecipientCount;
            
            // ATM liquidation details for this campus (get from details table joined with recipients)
            $atmBuilder = $this->atmLiquidationDetailModel->db->table('atm_liquidation_details ald');
            $atmBuilder->join('scholarship_recipients sr', 'ald.recipient_id = sr.id', 'left');
            $atmBuilder->where('sr.campus', $campus);
            
            if (!empty($filters['semester'])) {
                $atmBuilder->where('ald.semester', $filters['semester']);
            }
            if (!empty($filters['academic_year'])) {
                $atmBuilder->where('ald.academic_year', $filters['academic_year']);
            }
            
            $atmCount = $atmBuilder->countAllResults(false);
            
            // Get total amount for ATM in this campus
            $atmAmountResult = $atmBuilder->selectSum('ald.amount', 'total_amount')->get()->getRowArray();
            $atmAmount = $atmAmountResult['total_amount'] ?: 0;
            
            $campusStats[$campus] = [
                'manual' => $campusManualStats,
                'atm' => [
                    'total_records' => $atmCount,
                    'total_amount' => $atmAmount
                ]
            ];
        }

        // Get monthly trends data
        $monthlyTrends = $this->getMonthlyTrendsData($filters);

        $data = [
            'title' => 'Scholarship Liquidation Dashboard',
            'manualStats' => $manualStats,
            'atmStats' => $atmStats,
            'recentManual' => $recentManual,
            'recentAtm' => $recentAtm,
            'campusStats' => $campusStats,
            'monthlyTrends' => $monthlyTrends,
            'filters' => $filters,
            'user' => $this->session->get('user') ?: $this->userModel->find($this->session->get('user_id'))
        ];

        return view('dashboard/index', $data);
    }

    private function getMonthlyTrendsData($filters = [])
    {
        $monthlyData = [
            'labels' => [],
            'manual' => [],
            'atm' => []
        ];

        // Get data for the last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = strtotime("-$i months");
            $month = date('Y-m', $date);
            $monthLabel = date('M', $date);
            
            $monthlyData['labels'][] = $monthLabel;
            
            // Count manual liquidations for this month
            $manualBuilder = $this->manualLiquidationModel->where("DATE_FORMAT(created_at, '%Y-%m')", $month)
                                                          ->where('status !=', 'hidden');
            
            if (!empty($filters['semester'])) {
                $manualBuilder->where('semester', $filters['semester']);
            }
            if (!empty($filters['academic_year'])) {
                $manualBuilder->where('academic_year', $filters['academic_year']);
            }
            if (!empty($filters['campus'])) {
                $manualBuilder->where('campus', $filters['campus']);
            }
            
            $monthlyData['manual'][] = $manualBuilder->countAllResults();
            
            // Count ATM batches for this month
            $atmBuilder = $this->atmLiquidationModel->where("DATE_FORMAT(created_at, '%Y-%m')", $month);
            
            if (!empty($filters['semester'])) {
                $atmBuilder->where('semester', $filters['semester']);
            }
            if (!empty($filters['academic_year'])) {
                $atmBuilder->where('academic_year', $filters['academic_year']);
            }
            
            $monthlyData['atm'][] = $atmBuilder->countAllResults();
        }

        return $monthlyData;
    }

    public function getChartData()
    {
        if (!$this->session->get('user_id')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $filters = [
            'semester' => $this->request->getPost('semester'),
            'academic_year' => $this->request->getPost('academic_year'),
            'campus' => $this->request->getPost('campus')
        ];

        // Monthly liquidation trends
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthFilter = $filters + ['month' => $month];
            
            $manualCount = $this->manualLiquidationModel->where('DATE_FORMAT(created_at, "%Y-%m")', $month)->countAllResults(false);
            $atmCount = $this->atmLiquidationModel->where('DATE_FORMAT(created_at, "%Y-%m")', $month)->countAllResults();
            
            $monthlyData[] = [
                'month' => date('M Y', strtotime($month . '-01')),
                'manual' => $manualCount,
                'atm' => $atmCount
            ];
        }

        // Status distribution
        $manualStats = $this->manualLiquidationModel->getDashboardStatistics($filters);
        $atmStats = $this->atmLiquidationModel->getDashboardStatistics($filters);

        return $this->response->setJSON([
            'monthlyData' => $monthlyData,
            'statusData' => [
                'manual' => $manualStats,
                'atm' => $atmStats
            ]
        ]);
    }
}