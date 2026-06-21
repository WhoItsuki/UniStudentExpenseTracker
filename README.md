# UniStudentExpenseTracker

<p align="center">
  <img src="https://img.shields.io/badge/PHP-%5E8.2-blue.svg" alt="PHP 8.2" />
  <img src="https://img.shields.io/badge/Laravel-12.x-red.svg" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/Tailwind%20CSS-4.0.0-sky.svg" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="MIT License" />
  <img src="https://img.shields.io/badge/Status-Active-brightgreen.svg" alt="Status Active" />
</p>

## Overview

UniStudentExpenseTracker is a Laravel-based student expense management system that helps students track budgets, expenses, incomes, and categories while providing an admin interface for student management.

Key features include student authentication, expense and budget tracking, category management, income logging, and period-filtered analytics for expenses and budget comparisons.

## Tech Stack

- Laravel 12
- PHP 8.2
- Tailwind CSS 4
- Vite
- MySQL / SQLite compatible with Laravel migrations

## Features

- Student login, signup, profile, and logout
- Admin login and student management dashboard
- Expense creation, update, and deletion
- Budget creation, update, and deletion
- Category management (add, update, delete)
- Income logging and delete support
- Period-based analytics API for:
  - Expenses by category
  - Budget vs expense comparison
- Protected routes via `student.auth` and `admin.auth` middleware

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
```

## Development

```bash
npm run dev
php artisan serve
```

## Authentication Routes

- Student login: `POST /studentLogin`
- Student signup: `POST /studentSignup`
- Student logout: `POST /studentLogout`
- Admin login: `POST /adminLogin`
- Admin logout: `POST /adminLogout`

## Student Interface Routes

- `/loginStudent` - Student login page
- `/signupStudent` - Student signup page
- `/dashboardStudent` - Student dashboard
- `/profileStudent` - Student profile
- `/category` - Category management
- `/expense` - Expense management
- `/budget` - Budget management
- `/income` - Income management

## Admin Interface Routes

- `/loginAdmin` - Admin login page
- `/dashboardAdmin` - Admin dashboard
- `/profileAdmin` - Admin profile
- `/studentAdmin` - Student list
- `/student/{studentID}` - Student detail view

## API Endpoints

### Expenses by Category

- `GET /api/expenses-by-category/{period}`
- `period` values: `weekly`, `monthly`, `yearly`

Response example:

```json
{
  "period": "monthly",
  "date_range": {
    "start": "2026-01-01",
    "end": "2026-01-31"
  },
  "data": [
    {
      "category_name": "Food",
      "total_amount": 150.50
    }
  ]
}
```

### Budget vs Expense

- `GET /api/budget-vs-expense/{period}`
- `period` values: `weekly`, `monthly`, `yearly`

Response example:

```json
{
  "period": "monthly",
  "date_range": {
    "start": "2026-01-01",
    "end": "2026-01-31"
  },
  "data": {
    "total_budget": 1000.00,
    "total_expense": 850.75,
    "remaining_budget": 149.25,
    "budget_status": "within_budget"
  }
}
```

## Notes

- Both API endpoints require a valid student session and are protected by `student.auth` middleware.
- Invalid `period` values return a `400` response.
- Period calculations use Carbon for weekly, monthly, and yearly date ranges.

## Frontend Integration Example

```javascript
async function fetchExpensesByCategory(period) {
  const response = await fetch(`/api/expenses-by-category/${period}`);
  return response.json();
}

async function fetchBudgetVsExpense(period) {
  const response = await fetch(`/api/budget-vs-expense/${period}`);
  return response.json();
}
```

## License

This project is licensed under the MIT License.
