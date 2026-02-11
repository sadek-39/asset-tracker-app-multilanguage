# Asset Tracker - Multi-Stack Application

Asset Tracker is a comprehensive financial tracking application implemented across three major technology stacks. This project serves as an educational resource to compare architectures, patterns, and best practices in modern web development.

## Project Overview
Track your income, expenses, and assets in a responsive, secure environment.

### Tech Stacks
1.  **Golang + Gin**: High-performance REST API with JWT authentication.
2.  **Java + Spring Boot**: Enterprise-grade REST API using Spring Security and JPA.
3.  **Laravel + Blade**: Rapid development MVC implementation with session-based auth.
4.  **Shared React Frontend**: Modern Vite-based SPA that communicates with both Go and Java backends.

## Project Structure
```text
asset-tracker-app/
├── golang-gin/          # Go Backend (Port 8080)
├── java-spring-boot/    # Java Backend (Port 8081)
├── laravel-blade/       # Laravel Full-Stack (Port 8000)
├── frontend-react/      # Shared React SPA (Port 5173)
├── database/            # Shared Database Schema (MySQL)
└── README.md            # Master Documentation
```

## Getting Started

### Prerequisites
- MySQL 8.0+
- Node.js 18+
- Go 1.21+
- Java 17+ (Maven)
- PHP 8.1+ (Composer)

### Database Setup
1. Create a database named `asset_tracker`.
2. Apply the schema from `database/schema.sql` (or run migrations in any of the stacks).

### Running Implementations
Refer to the `README.md` in each subdirectory for specific setup and execution instructions.

## Learning Objectives
- Compare **Dependency Injection** in Spring Boot vs manual wiring in Go.
- Contrast **Session-based** auth (Laravel) with **Token-based** (JWT) auth.
- Understand the **Repository Pattern** across different ORMs (GORM, JPA, Eloquent).
- Explore **Shared Frontend** architectures with dynamic backend switching.

## License
MIT
