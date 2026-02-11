package handlers

import (
	"net/http"
	"time"
	"github.com/gin-gonic/gin"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/models"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/repository"
)

type DashboardSummary struct {
	TotalIncome      float64 `json:"total_income"`
	TotalExpenses    float64 `json:"total_expenses"`
	NetSavings       float64 `json:"net_savings"`
	TotalAssetsValue float64 `json:"total_assets_value"`
}

func GetDashboardSummary(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	now := time.Now()
	firstOfMonth := time.Date(now.Year(), now.Month(), 1, 0, 0, 0, 0, now.Location())

	var summary DashboardSummary

	// Total Income (Current Month)
	repository.DB.Model(&models.IncomeTransaction{}).
		Where("user_id = ? AND transaction_date >= ?", userID, firstOfMonth).
		Select("COALESCE(SUM(amount), 0)").Scan(&summary.TotalIncome)

	// Total Expenses (Current Month)
	repository.DB.Model(&models.ExpenseTransaction{}).
		Where("user_id = ? AND transaction_date >= ?", userID, firstOfMonth).
		Select("COALESCE(SUM(amount), 0)").Scan(&summary.TotalExpenses)

	summary.NetSavings = summary.TotalIncome - summary.TotalExpenses

	// Total Assets Value (Sources + Assets)
	var totalSources float64
	repository.DB.Model(&models.IncomeSource{}).
		Where("user_id = ?").
		Select("COALESCE(SUM(current_balance), 0)").Scan(&totalSources)

	var totalAssets float64
	repository.DB.Model(&models.Asset{}).
		Where("user_id = ?").
		Select("COALESCE(SUM(current_value), 0)").Scan(&totalAssets)

	summary.TotalAssetsValue = totalSources + totalAssets

	c.JSON(http.StatusOK, summary)
}
