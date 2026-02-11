package models

import (
	"time"

	"gorm.io/gorm"
)

type User struct {
	ID           uint           `gorm:"primaryKey" json:"id"`
	Email        string         `gorm:"unique;not null" json:"email"`
	PasswordHash string         `gorm:"column:password;not null" json:"-"`
	Name         string         `gorm:"not null" json:"name"`
	CreatedAt    time.Time      `json:"created_at"`
	UpdatedAt    time.Time      `json:"updated_at"`
	DeletedAt    gorm.DeletedAt `gorm:"index" json:"-"`
}

type SourceType string

const (
	SourceTypeBankAccount   SourceType = "bank_account"
	SourceTypeCash          SourceType = "cash"
	SourceTypeDigitalWallet SourceType = "digital_wallet"
	SourceTypeOther         SourceType = "other"
)

type IncomeSource struct {
	ID             uint           `gorm:"primaryKey" json:"id"`
	UserID         uint           `gorm:"not null" json:"user_id"`
	SourceName     string         `gorm:"size:100;not null" json:"source_name"`
	SourceType     SourceType     `gorm:"type:enum('bank_account','cash','digital_wallet','other');not null" json:"source_type"`
	CurrentBalance float64        `gorm:"type:decimal(15,2);default:0.00" json:"current_balance"`
	CreatedAt      time.Time      `json:"created_at"`
	UpdatedAt      time.Time      `json:"updated_at"`
	DeletedAt      gorm.DeletedAt `gorm:"index" json:"-"`
}

type IncomeTransaction struct {
	ID              uint           `gorm:"primaryKey" json:"id"`
	UserID          uint           `gorm:"not null" json:"user_id"`
	SourceID        uint           `gorm:"not null" json:"source_id"`
	Amount          float64        `gorm:"type:decimal(15,2);not null" json:"amount"`
	Description     string         `gorm:"size:255" json:"description"`
	TransactionDate time.Time      `gorm:"type:date;not null" json:"transaction_date"`
	CreatedAt       time.Time      `json:"created_at"`
	UpdatedAt       time.Time      `json:"updated_at"`
	DeletedAt       gorm.DeletedAt `gorm:"index" json:"-"`
}

type ExpenseCategory string

const (
	CatFood        ExpenseCategory = "food_dining"
	CatTrans       ExpenseCategory = "transportation"
	CatUtil        ExpenseCategory = "utilities"
	CatRent        ExpenseCategory = "rent_mortgage"
	CatHealth      ExpenseCategory = "healthcare_medical"
	CatEnt         ExpenseCategory = "entertainment"
	CatShop        ExpenseCategory = "shopping_retail"
	CatEdu         ExpenseCategory = "education"
	CatIns         ExpenseCategory = "insurance"
	CatNet         ExpenseCategory = "phone_internet"
	CatPers        ExpenseCategory = "personal_care"
	CatSav         ExpenseCategory = "savings_investments"
	CatDebt        ExpenseCategory = "debt_payments"
	CatGift        ExpenseCategory = "gifts_donations"
	CatMisc        ExpenseCategory = "miscellaneous"
)

type ExpenseTransaction struct {
	ID              uint            `gorm:"primaryKey" json:"id"`
	UserID          uint            `gorm:"not null" json:"user_id"`
	SourceID        uint            `gorm:"not null" json:"source_id"`
	Amount          float64         `gorm:"type:decimal(15,2);not null" json:"amount"`
	Category        ExpenseCategory `gorm:"type:enum('food_dining','transportation','utilities','rent_mortgage','healthcare_medical','entertainment','shopping_retail','education','insurance','phone_internet','personal_care','savings_investments','debt_payments','gifts_donations','miscellaneous');not null" json:"category"`
	Description     string          `gorm:"type:text" json:"description"`
	TransactionDate time.Time       `gorm:"type:date;not null" json:"transaction_date"`
	CreatedAt       time.Time       `json:"created_at"`
	UpdatedAt       time.Time       `json:"updated_at"`
	DeletedAt       gorm.DeletedAt  `gorm:"index" json:"-"`
}

type AssetType string

const (
	AssetTypeDPS        AssetType = "dps"
	AssetTypeBond       AssetType = "bond"
	AssetTypeInvestment AssetType = "investment"
	AssetTypeOther      AssetType = "other"
)

type Asset struct {
	ID              uint           `gorm:"primaryKey" json:"id"`
	UserID          uint           `gorm:"not null" json:"user_id"`
	AssetType       AssetType      `gorm:"type:enum('dps','bond','investment','other');not null" json:"asset_type"`
	AssetName       string         `gorm:"size:100;not null" json:"asset_name"`
	CurrentValue    float64        `gorm:"type:decimal(15,2);not null" json:"current_value"`
	InstitutionName string         `gorm:"size:100" json:"institution_name"`
	Description     string         `gorm:"type:text" json:"description"`
	CreatedAt       time.Time      `json:"created_at"`
	UpdatedAt       time.Time      `json:"updated_at"`
	DeletedAt       gorm.DeletedAt `gorm:"index" json:"-"`
}
