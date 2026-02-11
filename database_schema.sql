-- Database Schema for Asset Tracker App
-- Support for multi-user isolation, income tracking (max 5 sources), expenses, and assets.

CREATE DATABASE IF NOT EXISTS asset_tracker;
USE asset_tracker;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (email)
);

-- Income Sources Table (Max 5 sources per user enforced at app level)
CREATE TABLE income_sources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    source_name VARCHAR(100) NOT NULL,
    source_type ENUM('bank_account', 'cash', 'digital_wallet', 'other') NOT NULL,
    current_balance DECIMAL(15, 2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id)
);

-- Income Transactions Table
CREATE TABLE income_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    source_id INT NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    description VARCHAR(255),
    transaction_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (source_id) REFERENCES income_sources(id) ON DELETE CASCADE,
    INDEX (user_id),
    INDEX (transaction_date),
    INDEX (user_id, transaction_date)
);

-- Expense Transactions Table
CREATE TABLE expense_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    source_id INT NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    category ENUM(
        'food_dining', 'transportation', 'utilities', 'rent_mortgage', 
        'healthcare_medical', 'entertainment', 'shopping_retail', 'education', 
        'insurance', 'phone_internet', 'personal_care', 'savings_investments', 
        'debt_payments', 'gifts_donations', 'miscellaneous'
    ) NOT NULL,
    description TEXT,
    transaction_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (source_id) REFERENCES income_sources(id) ON DELETE CASCADE,
    INDEX (user_id),
    INDEX (transaction_date),
    INDEX (user_id, transaction_date)
);

-- Assets Table (DPS, Bonds, etc.)
CREATE TABLE assets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    asset_type ENUM('dps', 'bond', 'investment', 'other') NOT NULL,
    asset_name VARCHAR(100) NOT NULL,
    current_value DECIMAL(15, 2) NOT NULL,
    institution_name VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (user_id)
);
