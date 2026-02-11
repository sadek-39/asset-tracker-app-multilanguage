package main

import (
	"log"
	"os"

	"github.com/gin-contrib/cors"
	"github.com/gin-gonic/gin"
	"github.com/joho/godotenv"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/handlers"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/middleware"
	"github.com/sadekinflack/asset-tracker-app/golang-gin/repository"
	"go.uber.org/zap"
)

func main() {
	// Initialize Logger
	logger, _ := zap.NewProduction()
	defer logger.Sync()

	// Initialize DB
	repository.InitDB()

	// Load .env
	_ = godotenv.Load()
	port := os.Getenv("PORT")
	if port == "" {
		port = "8080"
	}

	r := gin.New()

	// Middleware
	r.Use(middleware.LoggerMiddleware(logger))
	r.Use(gin.Recovery())
	r.Use(cors.Default())

	// Public routes
	r.POST("/api/register", handlers.Register)
	r.POST("/api/login", handlers.Login)

	// Protected routes
	auth := r.Group("/api")
	auth.Use(middleware.AuthMiddleware())
	{
		// Dashboard
		auth.GET("/dashboard/summary", handlers.GetDashboardSummary)

		// Income Sources
		auth.POST("/income-sources", handlers.CreateIncomeSource)
		auth.GET("/income-sources", handlers.GetIncomeSources)
		auth.PUT("/income-sources/:id", handlers.UpdateIncomeSource)
		auth.DELETE("/income-sources/:id", handlers.DeleteIncomeSource)

		// Income Transactions
		auth.POST("/income-transactions", handlers.CreateIncomeTransaction)
		auth.GET("/income-transactions", handlers.GetIncomeTransactions)
		auth.DELETE("/income-transactions/:id", handlers.DeleteIncomeTransaction)

		// Expense Transactions
		auth.POST("/expense-transactions", handlers.CreateExpenseTransaction)
		auth.GET("/expense-transactions", handlers.GetExpenseTransactions)
		auth.DELETE("/expense-transactions/:id", handlers.DeleteExpenseTransaction)

		// Assets
		auth.POST("/assets", handlers.CreateAsset)
		auth.GET("/assets", handlers.GetAssets)
		auth.PUT("/assets/:id", handlers.UpdateAsset)
		auth.DELETE("/assets/:id", handlers.DeleteAsset)
	}

	log.Printf("Server starting on port %s", port)
	if err := r.Run(":" + port); err != nil {
		log.Fatal("Failed to run server: ", err)
	}
}
