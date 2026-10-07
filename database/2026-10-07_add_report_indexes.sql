-- =====================================================================
-- Add indexes for report performance (report/list)
-- Database : dbxwoccqevnkts (MySQL 8.4, InnoDB)
-- Date     : 2026-10-07
--
-- Schema-only change: adds secondary indexes on foreign key / filter
-- columns. It does NOT insert, update or delete any data.
--
-- ALGORITHM=INPLACE, LOCK=NONE = online DDL, reads/writes keep working
-- while each index is built (tables are small, each takes seconds).
--
-- How to run:
--   1. Back up the database first (phpMyAdmin > Export)
--   2. Run STEP 1 and confirm only PRIMARY indexes exist
--   3. Run STEP 2 (preferably outside office hours)
--   4. Run STEP 3 to verify
-- If an ALTER fails with "Duplicate key name", that index already exists,
-- skip it and continue with the next statement.
-- =====================================================================


-- ---------------------------------------------------------------------
-- STEP 1: Check existing indexes (read-only)
-- ---------------------------------------------------------------------
SELECT TABLE_NAME, INDEX_NAME, GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX) AS columns
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('booking_products', 'bookings_no', 'booking_paid', 'booking_product_rates',
                     'booking_transfer', 'booking_extra_charge', 'booking_order_transfer',
                     'customers', 'invoices', 'receipts', 'booking_order_boat')
GROUP BY TABLE_NAME, INDEX_NAME
ORDER BY TABLE_NAME, INDEX_NAME;


-- ---------------------------------------------------------------------
-- STEP 2: Add indexes
-- ---------------------------------------------------------------------

-- Travel date range filter (report, booking list, orders)
ALTER TABLE `booking_products`
  ADD INDEX `idx_travel_date_booking` (`travel_date`, `booking_id`),
  ADD INDEX `idx_booking_id` (`booking_id`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `bookings_no`
  ADD INDEX `idx_booking_id` (`booking_id`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `booking_paid`
  ADD INDEX `idx_booking_id` (`booking_id`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `booking_product_rates`
  ADD INDEX `idx_booking_products_id` (`booking_products_id`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `booking_transfer`
  ADD INDEX `idx_booking_products_id` (`booking_products_id`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `booking_extra_charge`
  ADD INDEX `idx_booking_id` (`booking_id`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `booking_order_transfer`
  ADD INDEX `idx_booking_transfer_id` (`booking_transfer_id`),
  ADD INDEX `idx_order_id` (`order_id`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `customers`
  ADD INDEX `idx_booking_id` (`booking_id`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `invoices`
  ADD INDEX `idx_booking_id` (`booking_id`),
  ADD INDEX `idx_cover_id` (`cover_id`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `receipts`
  ADD INDEX `idx_cover_id` (`cover_id`),
  ALGORITHM=INPLACE, LOCK=NONE;

ALTER TABLE `booking_order_boat`
  ADD INDEX `idx_booking_id` (`booking_id`),
  ADD INDEX `idx_manage_id` (`manage_id`),
  ALGORITHM=INPLACE, LOCK=NONE;


-- ---------------------------------------------------------------------
-- STEP 3: Verify (re-run STEP 1 query) and refresh statistics
-- ---------------------------------------------------------------------
ANALYZE TABLE `booking_products`, `bookings_no`, `booking_paid`, `booking_product_rates`,
              `booking_transfer`, `booking_extra_charge`, `booking_order_transfer`,
              `customers`, `invoices`, `receipts`, `booking_order_boat`;


-- ---------------------------------------------------------------------
-- ROLLBACK (only if needed): drop the indexes added above
-- ---------------------------------------------------------------------
-- ALTER TABLE `booking_products`       DROP INDEX `idx_travel_date_booking`, DROP INDEX `idx_booking_id`;
-- ALTER TABLE `bookings_no`            DROP INDEX `idx_booking_id`;
-- ALTER TABLE `booking_paid`           DROP INDEX `idx_booking_id`;
-- ALTER TABLE `booking_product_rates`  DROP INDEX `idx_booking_products_id`;
-- ALTER TABLE `booking_transfer`       DROP INDEX `idx_booking_products_id`;
-- ALTER TABLE `booking_extra_charge`   DROP INDEX `idx_booking_id`;
-- ALTER TABLE `booking_order_transfer` DROP INDEX `idx_booking_transfer_id`, DROP INDEX `idx_order_id`;
-- ALTER TABLE `customers`              DROP INDEX `idx_booking_id`;
-- ALTER TABLE `invoices`               DROP INDEX `idx_booking_id`, DROP INDEX `idx_cover_id`;
-- ALTER TABLE `receipts`               DROP INDEX `idx_cover_id`;
-- ALTER TABLE `booking_order_boat`     DROP INDEX `idx_booking_id`, DROP INDEX `idx_manage_id`;
