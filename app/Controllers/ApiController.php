<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ScholarshipRecipientModel;

class ApiController extends BaseController
{
    protected $recipientModel;

    public function __construct()
    {
        $this->recipientModel = new ScholarshipRecipientModel();
    }

    /**
     * Search scholarship recipients for AJAX calls
     */
    public function searchRecipients()
    {
        try {
            $search = $this->request->getGet('search') ?? $this->request->getGet('query') ?? '';
            $campus = $this->request->getGet('campus') ?? '';
            $limit = $this->request->getGet('limit') ?? 20;
            
            // Always use search if provided
            if (!empty($search)) {
                $recipients = $this->recipientModel->searchRecipients($search, $campus);
            } elseif ($campus) {
                // If only campus is provided (no search term), get all recipients by campus
                $recipients = $this->recipientModel->getRecipientsByCampus($campus);
            } else {
                // No search term and no campus
                $recipients = [];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $recipients,
                'recipients' => $recipients, // Add this for compatibility with different views
                'count' => count($recipients)
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error searching recipients: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get single recipient details
     */
    public function getRecipient($id)
    {
        try {
            $recipient = $this->recipientModel->find($id);
            
            if (!$recipient) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Recipient not found'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $recipient
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error retrieving recipient: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get all recipients for dropdown/selection
     */
    public function getAllRecipients()
    {
        try {
            $recipients = $this->recipientModel->findAll();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $recipients,
                'count' => count($recipients)
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error retrieving recipients: ' . $e->getMessage()
            ]);
        }
    }
}