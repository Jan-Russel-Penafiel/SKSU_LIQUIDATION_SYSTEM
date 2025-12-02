-- Migration script to convert ATM liquidations from batch-based to per-recipient system
-- Run this script to update your database structure

-- Add new columns to atm_liquidation_details table
ALTER TABLE `atm_liquidation_details` 
    ADD COLUMN `semester` varchar(20) NULL AFTER `status`,
    ADD COLUMN `academic_year` varchar(20) NULL AFTER `semester`,
    ADD COLUMN `file_path` varchar(255) NULL AFTER `reference_number`,
    ADD COLUMN `file_type` ENUM('csv', 'excel', 'pdf') NULL AFTER `file_path`,
    ADD COLUMN `created_by` int(11) unsigned NULL AFTER `file_type`,
    ADD COLUMN `verified_by` int(11) unsigned NULL AFTER `created_by`,
    ADD COLUMN `verified_at` datetime NULL AFTER `verified_by`,
    ADD COLUMN `approved_by` int(11) unsigned NULL AFTER `verified_at`,
    ADD COLUMN `approved_at` datetime NULL AFTER `approved_by`,
    ADD COLUMN `remarks` text NULL AFTER `approved_at`;

-- Make atm_liquidation_id nullable (optional) since we're moving to per-recipient
ALTER TABLE `atm_liquidation_details` 
    MODIFY COLUMN `atm_liquidation_id` int(11) unsigned NULL;

-- Remove bank_certification column if it exists (replaced by file_path)
ALTER TABLE `atm_liquidation_details` 
    DROP COLUMN IF EXISTS `bank_certification`;

-- Update existing records to have proper values (optional - adjust as needed)
-- UPDATE `atm_liquidation_details` 
--     SET `status` = 'pending' 
--     WHERE `status` IS NULL;

-- Note: The atm_liquidations table is kept for backward compatibility
-- New liquidations will be created directly in atm_liquidation_details table
-- without requiring a batch in atm_liquidations table
