# Student Management System

A simple student management system built with PHP and MySQL, using PDO for database operations.

## Features

- User authentication (login, register, logout)
- Student management (add, edit, delete, view)
- Role-based access (admin and regular user)
- Responsive UI using Bootstrap

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

## Installation

1. Clone or download this repository to your web server's document root
2. Create a database named `student_management`
3. Import the `database.sql` file to create tables and sample data
4. Update the database configuration in `config/config.php` if needed
5. Access the application through your web browser

## Default Login

- Username: `admin`
- Password: `password` (default hashed password in database)

## Project Structure

```
student-management/
│
├── config/
│   └── config.php
│
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
│
├── students/
│   ├── index.php
│   ├── add.php
│   ├── edit.php
│   └── delete.php
│
├── users/
│   └── index.php
│
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── auth_check.php
│
├── assets/
│   └── style.css
│
├── dashboard.php
├── index.php
├── database.sql
└── README.md
```

## Database Schema

The system uses two main tables:

- `users`: Stores user information (id, username, password, email, role)
- `students`: Stores student information (id, first_name, last_name, email, phone, address, date_of_birth, gender)

## Security Features

- Password hashing using PHP's password_hash()
- SQL injection prevention using prepared statements
- Session-based authentication
- Input validation and sanitization

## Usage

1. Register a new account or use the default admin account
2. Log in to access the dashboard
3. Use the navigation menu to manage students
4. Admin users can also view the list of all users