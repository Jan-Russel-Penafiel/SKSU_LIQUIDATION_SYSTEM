<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $userData = [
            [
                'username' => 'admin',
                'email' => 'admin@sksu.edu.ph',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'campus' => 'Main Campus',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'chairman',
                'email' => 'chairman@sksu.edu.ph',
                'password_hash' => password_hash('chairman123', PASSWORD_DEFAULT),
                'role' => 'scholarship_chairman',
                'campus' => 'Main Campus',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'coordinator',
                'email' => 'coordinator@sksu.edu.ph',
                'password_hash' => password_hash('coord123', PASSWORD_DEFAULT),
                'role' => 'scholarship_coordinator',
                'campus' => 'Main Campus',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'officer1',
                'email' => 'officer1@sksu.edu.ph',
                'password_hash' => password_hash('officer123', PASSWORD_DEFAULT),
                'role' => 'disbursing_officer',
                'campus' => 'Main Campus',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'accounting',
                'email' => 'accounting@sksu.edu.ph',
                'password_hash' => password_hash('account123', PASSWORD_DEFAULT),
                'role' => 'accounting_officer',
                'campus' => 'Main Campus',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('users')->insertBatch($userData);

        // Create sample scholarship recipients
        $recipientData = [
            [
                'recipient_id' => 'SKSU-2024-001',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'middle_name' => 'Santos',
                'email' => 'juan.delacruz@student.sksu.edu.ph',
                'phone' => '09171234567',
                'campus' => 'Main Campus',
                'course' => 'Bachelor of Science in Information Technology',
                'year_level' => '4th Year',
                'scholarship_type' => 'Academic Merit Scholarship',
                'bank_account' => '1234567890',
                'bank_name' => 'Land Bank of the Philippines',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'recipient_id' => 'SKSU-2024-002',
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'middle_name' => 'Garcia',
                'email' => 'maria.santos@student.sksu.edu.ph',
                'phone' => '09181234567',
                'campus' => 'Main Campus',
                'course' => 'Bachelor of Science in Education',
                'year_level' => '3rd Year',
                'scholarship_type' => 'CHED Scholarship',
                'bank_account' => '2345678901',
                'bank_name' => 'Development Bank of the Philippines',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'recipient_id' => 'SKSU-2024-003',
                'first_name' => 'Pedro',
                'last_name' => 'Gonzales',
                'middle_name' => 'Reyes',
                'email' => 'pedro.gonzales@student.sksu.edu.ph',
                'phone' => '09191234567',
                'campus' => 'Kalamansig Campus',
                'course' => 'Bachelor of Science in Agriculture',
                'year_level' => '2nd Year',
                'scholarship_type' => 'DOST Scholarship',
                'bank_account' => '3456789012',
                'bank_name' => 'Philippine National Bank',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'recipient_id' => 'SKSU-2024-004',
                'first_name' => 'Ana',
                'last_name' => 'Rodriguez',
                'middle_name' => 'Cruz',
                'email' => 'ana.rodriguez@student.sksu.edu.ph',
                'phone' => '09201234567',
                'campus' => 'Palimbang Campus',
                'course' => 'Bachelor of Science in Business Administration',
                'year_level' => '1st Year',
                'scholarship_type' => 'TES Scholarship',
                'bank_account' => '4567890123',
                'bank_name' => 'Banco de Oro',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'recipient_id' => 'SKSU-2024-005',
                'first_name' => 'Jose',
                'last_name' => 'Mendoza',
                'middle_name' => 'Lopez',
                'email' => 'jose.mendoza@student.sksu.edu.ph',
                'phone' => '09211234567',
                'campus' => 'Isulan Campus',
                'course' => 'Bachelor of Science in Engineering',
                'year_level' => '4th Year',
                'scholarship_type' => 'Engineering Scholarship',
                'bank_account' => '5678901234',
                'bank_name' => 'Metrobank',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('scholarship_recipients')->insertBatch($recipientData);

        // Create sample manual liquidations
        $manualLiquidationData = [
            [
                'recipient_id' => 1,
                'disbursing_officer_id' => 4,
                'scholarship_coordinator_id' => 3,
                'voucher_number' => 'VO-2024-001',
                'amount' => 5000.00,
                'liquidation_date' => '2024-11-01',
                'semester' => '1st Semester',
                'academic_year' => '2024-2025',
                'campus' => 'Main Campus',
                'description' => 'Academic merit scholarship liquidation for tuition fee',
                'status' => 'approved',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'recipient_id' => 2,
                'disbursing_officer_id' => 4,
                'scholarship_coordinator_id' => 3,
                'voucher_number' => 'VO-2024-002',
                'amount' => 7500.00,
                'liquidation_date' => '2024-11-05',
                'semester' => '1st Semester',
                'academic_year' => '2024-2025',
                'campus' => 'Main Campus',
                'description' => 'CHED scholarship allowance',
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'recipient_id' => 3,
                'disbursing_officer_id' => 4,
                'scholarship_coordinator_id' => 3,
                'voucher_number' => 'VO-2024-003',
                'amount' => 6000.00,
                'liquidation_date' => '2024-11-10',
                'semester' => '1st Semester',
                'academic_year' => '2024-2025',
                'campus' => 'Kalamansig Campus',
                'description' => 'DOST scholarship stipend',
                'status' => 'verified',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('manual_liquidations')->insertBatch($manualLiquidationData);

        echo "Initial data seeded successfully!\n";
        echo "Admin credentials: admin / admin123\n";
        echo "Chairman credentials: chairman / chairman123\n";
        echo "Coordinator credentials: coordinator / coord123\n";
        echo "Officer credentials: officer1 / officer123\n";
        echo "Accounting credentials: accounting / account123\n";
    }
}