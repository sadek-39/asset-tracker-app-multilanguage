package handlers

import (
	"net/http"
	"github.com/gin-gonic/gin"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/models"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/repository"
	"gorm.io/gorm"
)

func CreateIncomeTransaction(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	var tx models.IncomeTransaction
	if err := c.ShouldBindJSON(&tx); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	tx.UserID = userID

	err := repository.DB.Transaction(func(db *gorm.DB) error {
		// Create transaction
		if err := db.Create(&tx).Error; err != nil {
			return err
		}

		// Update source balance
		if err := db.Model(&models.IncomeSource{}).Where("id = ? AND user_id = ?", tx.SourceID, userID).
			UpdateColumn("current_balance", gorm.Expr("current_balance + ?", tx.Amount)).Error; err != nil {
			return err
		}

		return nil
	})

	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to record transaction"})
		return
	}

	c.JSON(http.StatusCreated, tx)
}

func GetIncomeTransactions(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	var txs []models.IncomeTransaction
	repository.DB.Where("user_id = ?", userID).Order("transaction_date desc").Find(&txs)
	c.JSON(http.StatusOK, txs)
}

func DeleteIncomeTransaction(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	id := c.Param("id")

	var tx models.IncomeTransaction
	if err := repository.DB.Where("id = ? AND user_id = ?", id, userID).First(&tx).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Transaction not found"})
		return
	}

	err := repository.DB.Transaction(func(db *gorm.DB) error {
		// Update balance (revert)
		if err := db.Model(&models.IncomeSource{}).Where("id = ? AND user_id = ?", tx.SourceID, userID).
			UpdateColumn("current_balance", gorm.Expr("current_balance - ?", tx.Amount)).Error; err != nil {
			return err
		}

		// Delete transaction
		if err := db.Delete(&tx).Error; err != nil {
			return err
		}

		return nil
	})

	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to delete transaction"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Transaction deleted"})
}
