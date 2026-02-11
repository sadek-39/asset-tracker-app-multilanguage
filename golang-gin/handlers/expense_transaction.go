package handlers

import (
	"net/http"
	"github.com/gin-gonic/gin"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/models"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/repository"
	"gorm.io/gorm"
)

func CreateExpenseTransaction(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	var tx models.ExpenseTransaction
	if err := c.ShouldBindJSON(&tx); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	tx.UserID = userID

	err := repository.DB.Transaction(func(db *gorm.DB) error {
		// Deduct from source balance
		var source models.IncomeSource
		if err := db.Where("id = ? AND user_id = ?", tx.SourceID, userID).First(&source).Error; err != nil {
			return err
		}

		if source.CurrentBalance < tx.Amount {
			// In a real app we might allow negative balance, but for now let's just proceed
		}

		if err := db.Model(&source).UpdateColumn("current_balance", gorm.Expr("current_balance - ?", tx.Amount)).Error; err != nil {
			return err
		}

		// Create expense record
		if err := db.Create(&tx).Error; err != nil {
			return err
		}

		return nil
	})

	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to record expense"})
		return
	}

	c.JSON(http.StatusCreated, tx)
}

func GetExpenseTransactions(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	var txs []models.ExpenseTransaction
	repository.DB.Where("user_id = ?", userID).Order("transaction_date desc").Find(&txs)
	c.JSON(http.StatusOK, txs)
}

func DeleteExpenseTransaction(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	id := c.Param("id")

	var tx models.ExpenseTransaction
	if err := repository.DB.Where("id = ? AND user_id = ?", id, userID).First(&tx).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Transaction not found"})
		return
	}

	err := repository.DB.Transaction(func(db *gorm.DB) error {
		// Revert balance
		if err := db.Model(&models.IncomeSource{}).Where("id = ? AND user_id = ?", tx.SourceID, userID).
			UpdateColumn("current_balance", gorm.Expr("current_balance + ?", tx.Amount)).Error; err != nil {
			return err
		}

		// Delete record
		if err := db.Delete(&tx).Error; err != nil {
			return err
		}

		return nil
	})

	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to delete expense"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Expense deleted"})
}
