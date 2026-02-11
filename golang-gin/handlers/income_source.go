package handlers

import (
	"net/http"
	"github.com/gin-gonic/gin"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/models"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/repository"
)

func CreateIncomeSource(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	
	var count int64
	repository.DB.Model(&models.IncomeSource{}).Where("user_id = ?", userID).Count(&count)
	if count >= 5 {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Maximum of 5 income sources allowed"})
		return
	}

	var source models.IncomeSource
	if err := c.ShouldBindJSON(&source); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	source.UserID = userID
	if err := repository.DB.Create(&source).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to create income source"})
		return
	}

	c.JSON(http.StatusCreated, source)
}

func GetIncomeSources(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	var sources []models.IncomeSource
	repository.DB.Where("user_id = ?", userID).Find(&sources)
	c.JSON(http.StatusOK, sources)
}

func UpdateIncomeSource(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	id := c.Param("id")

	var source models.IncomeSource
	if err := repository.DB.Where("id = ? AND user_id = ?", id, userID).First(&source).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Income source not found"})
		return
	}

	if err := c.ShouldBindJSON(&source); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	repository.DB.Save(&source)
	c.JSON(http.StatusOK, source)
}

func DeleteIncomeSource(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	id := c.Param("id")

	if err := repository.DB.Where("id = ? AND user_id = ?", id, userID).Delete(&models.IncomeSource{}).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to delete income source"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Income source deleted"})
}
