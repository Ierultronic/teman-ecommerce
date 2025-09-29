-- Fix PostgreSQL constraint issue
-- Run this SQL command directly in your PostgreSQL database

-- Drop the check constraint
ALTER TABLE promotions DROP CONSTRAINT IF EXISTS promotions_type_check;

-- Alter the column type to varchar (string)
ALTER TABLE promotions ALTER COLUMN type TYPE VARCHAR(100);

-- Query to verify the changes
SELECT column_name, data_type, is_nullable 
FROM information_schema.columns 
WHERE table_name = 'promotions' AND table_schema = 'public';
