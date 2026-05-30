# Personal Finance App

A comprehensive personal finance management application built with Laravel. This application allows users to track their expenses, manage budgets, set financial goals, and gain insights into their financial habits through detailed reports. It features AI-powered transaction scanning using the Google Gemini API.

## Features

- **User Authentication**: Secure login, registration, and profile management using Laravel Breeze.
- **Dashboard**: A central hub providing an overview of your financial status.
- **Transaction Management**: 
  - Add, edit, delete, and categorize your income and expenses.
  - **AI Receipt Scanning**: Automatically extract transaction details from receipts/images using the Gemini API.
  - Export transactions for external use.
- **Budgeting**: Set and monitor budgets for different categories to keep your spending in check.
- **Financial Goals**: Define savings goals and adjust your progress over time.
- **Interactive Reports**: Visualize your financial data with beautiful, interactive charts powered by Chart.js.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: MongoDB (via `mongodb/laravel-mongodb`)
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Assets Bundler**: Vite
- **Charts**: Chart.js
- **AI Integration**: Google Gemini API

## Prerequisites

Before you begin, ensure you have the following installed on your local machine:

- PHP >= 8.2
- Composer
- Node.js & npm
- MongoDB server (running locally or a remote MongoDB Atlas URI)
- Google Gemini API Key

## Installation

1. **Navigate to the project directory**:
   ```bash
   cd finance-app
   ```

2. **Run the setup script** (this will install dependencies, create the `.env` file, generate the app key, run migrations, and build assets):
   ```bash
   composer run setup
   ```
   
   *Alternatively, you can run the commands manually:*
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   npm install
   npm run build
   ```

3. **Configure Environment Variables**:
   Open your `.env` file and configure your database settings to use MongoDB. You also need to add your Gemini API key for the receipt scanning feature to work.
   
   ```env
   DB_CONNECTION=mongodb
   DB_URI=mongodb://localhost:27017/finance_app
   
   GEMINI_API_KEY=your_gemini_api_key_here
   ```

4. **Run Database Migrations** (if not done by the setup script):
   ```bash
   php artisan migrate
   ```

## Running the Application Locally

You can run the development server which will start the Laravel server, Vite, queue listener, and logs concurrently:

```bash
composer run dev
```

The application will be accessible at `http://localhost:8000`.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
