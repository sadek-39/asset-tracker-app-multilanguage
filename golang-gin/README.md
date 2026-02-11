# Asset Tracker - Golang Gin Implementation

This is the Golang backend implementation of the Asset Tracker application.

## Tech Stack
- **Framework**: Gin
- **ORM**: GORM
- **Authentication**: JWT
- **Database**: MySQL 8.0
- **Logging**: Zap

## Project Structure
- `main.go`: Application entry point and routing.
- `handlers/`: Request handlers for auth, income, expenses, and assets.
- `models/`: GORM database models.
- `middleware/`: Auth and logging middleware.
- `repository/`: Database connection and auto-migration logic.

## Setup Instructions
1. Install Go 1.21+
2. Copy `.env.example` to `.env` and configure your database settings.
3. Run `go mod tidy` to download dependencies.
4. Run `go run main.go` to start the server.

## API Documentation
| Endpoint | Method | Description | Auth |
|----------|--------|-------------|------|
| `/api/register` | POST | Register a new user | No |
| `/api/login` | POST | Login and get JWT | No |
| `/api/dashboard/summary` | GET | Get monthly summary | Yes |
| `/api/income-sources` | POST/GET | Manage income sources | Yes |
| `/api/income-transactions` | POST/GET | Manage income txs | Yes |
| `/api/expense-transactions` | POST/GET | Manage expense txs | Yes |
| `/api/assets` | POST/GET/PUT/DELETE | Manage assets | Yes |

## Architecture Explanation
This implementation follows a **Handler-Repository-Model** pattern. 
- **Models** define the database schema and JSON structure.
- **Handlers** treat the HTTP request, perform business logic (like updating balances in a transaction), and return responses.
- **Middleware** intercepts requests for JWT verification and structured logging.
- **Atomic Transactions**: All operations that update multiple tables (e.g., adding an expense and deducting balance) are wrapped in `db.Transaction` blocks to ensure data integrity.
