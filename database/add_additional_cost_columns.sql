-- Migration: Add additional cost columns to packages table
-- Run this on existing databases to add the new columns

ALTER TABLE packages
    ADD COLUMN IF NOT EXISTS extra_gear_cost DECIMAL(10, 2) DEFAULT 0.00 AFTER labor_total,
    ADD COLUMN IF NOT EXISTS transportation_cost DECIMAL(10, 2) DEFAULT 0.00 AFTER extra_gear_cost,
    ADD COLUMN IF NOT EXISTS assistant_pay DECIMAL(10, 2) DEFAULT 0.00 AFTER transportation_cost,
    ADD COLUMN IF NOT EXISTS editing_cost DECIMAL(10, 2) DEFAULT 0.00 AFTER assistant_pay,
    ADD COLUMN IF NOT EXISTS additional_cost DECIMAL(10, 2) DEFAULT 0.00 AFTER editing_cost,
    ADD COLUMN IF NOT EXISTS additional_costs_total DECIMAL(10, 2) DEFAULT 0.00 AFTER additional_cost;
