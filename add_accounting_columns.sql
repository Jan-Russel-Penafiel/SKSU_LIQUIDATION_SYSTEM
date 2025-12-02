-- Add accounting_received_date and completed_at columns to atm_liquidation_details table
-- Also update the status enum to include sent_to_accounting and completed

ALTER TABLE `atm_liquidation_details` 
ADD COLUMN `accounting_received_date` datetime NULL AFTER `approved_at`,
ADD COLUMN `completed_at` datetime NULL AFTER `accounting_received_date`;

-- Update the status enum to include new statuses
ALTER TABLE `atm_liquidation_details` 
MODIFY COLUMN `status` ENUM('pending', 'verified', 'approved', 'rejected', 'sent_to_accounting', 'completed') DEFAULT 'pending';
