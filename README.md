<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## UniStudentExpenseTracker Features

### New Period-based Filter APIs

Two new API endpoints have been added to filter expense data by different time periods:

#### 1. Expenses by Category Filter
**Endpoint:** `GET /api/expenses-by-category/{period}`

**Parameters:**
- `period`: `weekly`, `monthly`, or `yearly`

**Date Ranges:**
- `weekly`: Current week (Monday to Sunday)
- `monthly`: Current month (1st to last day)
- `yearly`: Current year (January 1st to December 31st)

**Example Usage:**
- `/api/expenses-by-category/weekly` - Get expenses by category for current week
- `/api/expenses-by-category/monthly` - Get expenses by category for current month
- `/api/expenses-by-category/yearly` - Get expenses by category for current year

**Response Format:**
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
    },
    {
      "category_name": "Transport",
      "total_amount": 75.25
    }
  ]
}
```

#### 2. Budget vs Expense Comparison Filter
**Endpoint:** `GET /api/budget-vs-expense/{period}`

**Parameters:**
- `period`: `weekly`, `monthly`, or `yearly`

**Date Ranges:**
- `weekly`: Current week (Monday to Sunday)
- `monthly`: Current month (1st to last day)
- `yearly`: Current year (January 1st to December 31st)

**Example Usage:**
- `/api/budget-vs-expense/weekly` - Get budget vs expense comparison for current week
- `/api/budget-vs-expense/monthly` - Get budget vs expense comparison for current month
- `/api/budget-vs-expense/yearly` - Get budget vs expense comparison for current year

**Response Format:**
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

### Implementation Details

- **Authentication:** Both endpoints require student authentication (protected by `student.auth` middleware)
- **Date Calculation:** Uses Carbon library for accurate period calculations
- **Error Handling:** Returns 400 error for invalid period parameters
- **Session Dependency:** Requires active student session to filter data by student ID

### Usage in Frontend

These endpoints can be called via AJAX to dynamically update charts and displays based on selected time periods.

```javascript
// Example AJAX call for expenses by category
fetch('/api/expenses-by-category/monthly')
  .then(response => response.json())
  .then(data => {
    // Update your charts with data.data
    console.log('Expenses by category:', data);
  });

// Example AJAX call for budget vs expense
fetch('/api/budget-vs-expense/monthly')
  .then(response => response.json())
  .then(data => {
    // Update budget comparison display
    console.log('Budget vs expense:', data);
  });
```
