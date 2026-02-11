package handlers

import (
	"net/http"
	"github.com/gin-gonic/gin"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/models"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/repository"
)

func CreateAsset(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	var asset models.Asset
	if err := c.ShouldBindJSON(&asset); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	asset.UserID = userID
	if err := repository.DB.Create(&asset).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to create asset"})
		return
	}

	c.JSON(http.StatusCreated, asset)
}

func GetAssets(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	var assets []models.Asset
	repository.DB.Where("user_id = ?", userID).Find(&assets)
	c.JSON(http.StatusOK, assets)
}

func UpdateAsset(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	id := c.Param("id")

	var asset models.Asset
	if err := repository.DB.Where("id = ? AND user_id = ?", id, userID).First(&asset).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Asset not found"})
		return
	}

	if err := c.ShouldBindJSON(&asset); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	repository.DB.Save(&asset)
	c.JSON(http.StatusOK, asset)
}

func DeleteAsset(c *gin.Context) {
	userID := c.MustGet("user_id").(uint)
	id := c.Param("id")

	if err := repository.DB.Where("id = ? AND user_id = ?", id, userID).Delete(&models.Asset{}).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to delete asset"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Asset deleted"})
}
