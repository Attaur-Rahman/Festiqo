# FESTIQO Backend API

<p align="center">
  <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel Logo" width="120">
</p>

<p align="center">
  <strong>FESTIQO</strong><br>
  A scalable RESTful Backend API for the College Fest Management System.
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel">
    <img src="https://img.shields.io/badge/PHP-8.3-blue?style=for-the-badge&logo=php">
    <img src="https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge&logo=mysql">
    <img src="https://img.shields.io/badge/Sanctum-Authentication-success?style=for-the-badge">
    <img src="https://img.shields.io/badge/REST-API-blueviolet?style=for-the-badge">
    <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge">
</p>

---

# 📖 About FESTIQO

FESTIQO is a modern **College Fest Management System (CFMS)** designed to simplify the management of college festivals through a secure, scalable, and well-structured REST API.

The backend is built using **Laravel 12** following industry best practices, including:

- Service Layer Architecture
- RESTful API Design
- Role-Based Access Control (RBAC)
- Standardized API Responses
- Secure Authentication
- Clean Code Principles
- Modular Development

The project is designed to support multiple user roles such as:

- Administrator
- Event Coordinator
- Volunteer / Student Coordinator

---

# 🚀 Tech Stack

| Technology        | Purpose              |
| ----------------- | -------------------- |
| Laravel 12        | Backend Framework    |
| PHP 8.3+          | Programming Language |
| MySQL             | Database             |
| Laravel Sanctum   | API Authentication   |
| Spatie Permission | Roles & Permissions  |
| Laravel Mail      | Email Notifications  |
| Apidog / Swagger  | API Documentation    |
| Git & GitHub      | Version Control      |

---

# 🏗 Architecture

The project follows a layered architecture to keep the codebase clean, maintainable, and scalable.

```
Request
    │
    ▼
Form Request (Validation)
    │
    ▼
Controller
    │
    ▼
Service Layer
    │
    ▼
Model (Database)
    │
    ▼
API Resource
    │
    ▼
Standardized JSON Response
```

---

# ✨ Current Features

## Authentication

- Login using Email or Phone
- Logout
- Get Authenticated User
- Forgot Password
- OTP Verification
- Resend OTP
- Reset Password
- Change Password

### Security Features

- Laravel Sanctum Authentication
- Password Hashing
- Secure Password Reset Tokens
- OTP Expiration
- Token Revocation on Logout
- Centralized Exception Handling
- Input Validation
- Standard Error Responses

---

# 🔐 Role-Based Access Control (RBAC)

Authorization is implemented using **Spatie Laravel Permission**.

Current roles:

- Admin
- Event Coordinator
- Volunteer / Student Coordinator

Permissions are assigned through roles to ensure secure access to protected resources.

---

# 📦 Planned Modules

The backend is designed to support the following modules:

- Authentication
- Authorization (RBAC)
- Dashboard
- User Management
- Event Management
- Event Registration
- Ticket Verification
- Quiz Management
- Leaderboard
- Notifications
- Reports & Analytics
- Settings

---

# 📁 Project Structure

```
app
├── Http
│   ├── Controllers
│   ├── Requests
│   └── Resources
│
├── Models
├── Services
├── Notifications
├── Enums
└── Helpers

database
├── migrations
├── seeders
└── factories

routes
└── api.php

docs
└── API Documentation
```

---

# 📡 API Response Format

## Success Response

```json
{
    "success": true,
    "message": "Operation completed successfully.",
    "data": {}
}
```

## Error Response

```json
{
    "success": false,
    "message": "Validation failed.",
    "errors": {}
}
```

This consistent response format is used throughout the API.

---

# 🔑 Authentication

Protected routes require a Bearer Token.

```
Authorization: Bearer YOUR_ACCESS_TOKEN
```

Authentication is handled using Laravel Sanctum.

---

# 📚 API Documentation

Complete API documentation is available through:

- Apidog
- Swagger (coming soon)

Documentation includes:

- Endpoint Details
- Request Examples
- Response Examples
- Error Codes
- Validation Rules
- Test Cases

---

# ⚙ Installation

Clone the repository

```bash
git clone https://github.com/your-username/festiqo-backend.git
```

Move into the project

```bash
cd festiqo-backend
```

Install dependencies

```bash
composer install
```

Copy environment file

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Configure your database inside `.env`

Run migrations

```bash
php artisan migrate
```

Seed default data

```bash
php artisan db:seed
```

Start the development server

```bash
php artisan serve
```

---

# 🧪 Testing

Run feature and unit tests using:

```bash
php artisan test
```

---

# 📌 Coding Standards

This project follows:

- PSR-12 Coding Style
- REST API Best Practices
- Service Layer Pattern
- Form Request Validation
- API Resources
- Standardized API Responses
- Centralized Exception Handling
- Clean Architecture Principles

---

# 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to your branch
5. Open a Pull Request

---

# 📄 License

This project is licensed under the **MIT License**.

---

# 👨‍💻 Developed By

**FESTIQO Team**

A College Fest Management System built to provide a secure, scalable, and modern backend API for managing college events efficiently.
