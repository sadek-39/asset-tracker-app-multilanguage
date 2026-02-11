# Asset Tracker - Shared React Frontend

This is the shared React frontend for the Asset Tracker application, designed to work with both the Golang and Java Spring Boot backends.

## Features
- **Dynamic Backend Switcher**: Seamlessly switch between Go and Java backends from the sidebar or login page.
- **Modern UI**: Dark-themed, responsive dashboard built with TailwindCSS.
- **Data Visualization**: Real-time spending breakdown and portfolio charts using Chart.js.
- **Responsive Design**: Mobile-first architecture with custom navigation.
- **PWA Ready**: Offline-first UI capability and app manifest.

## Tech Stack
- **Framework**: Vite + React 18
- **Styling**: TailwindCSS
- **State Management**: React Context API
- **Data Fetching**: TanStack React Query + Axios
- **Icons**: Lucide React
- **Charts**: react-chartjs-2

## Setup Instructions
1. Install Node.js 18+.
2. Run `npm install` to download dependencies.
3. Run `npm run dev` to start the development server.
4. Ensure at least one backend (Go or Java) is running.

## Project Structure
- `src/context/AuthContext.jsx`: Handles authentication and backend selection logic.
- `src/services/api.js`: Axios instance with interceptors and dynamic base URL.
- `src/pages/`: Contains Dashboard, Assets, Income, and Expenses modules.
- `src/components/Layout.jsx`: The main layout wrapper with navigation.
