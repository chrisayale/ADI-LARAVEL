<div align="center">

# 💼 SACCO Management System
### Loan • Savings • Member • Meetings • Notifications

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge&logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

</div>

---

## 📌 Project Overview

**SACCO Management System** is a modern, secure, and scalable web application designed to manage the daily operations of a Savings and Credit Cooperative Organization (SACCO).

This system digitizes SACCO processes to reduce paperwork, improve accountability, and enhance transparency in financial operations.

It enables SACCO administrators, treasurers, and members to efficiently manage savings, loans, repayments, meetings, and communications in one centralized platform.

Built using **Laravel 12**, **Laravel Breeze**, and **Spatie Roles & Permissions** for secure authentication and role-based access control.

---

## 🚀 Core Features

### 👤 Member Management
- Member registration & profiles
- Membership verification
- Next of kin details
- Profile photo upload
- Member status tracking (Active / Inactive / Suspended)

---

### 💰 Savings Management
- Savings deposits & withdrawals
- Transaction history
- Balance tracking per member
- Approval workflow for transactions

---

### 🏦 Loan Management
- Online loan applications
- Loan eligibility checks
- Collateral management & verification
- Guarantor approval process
- Loan approval & disbursement workflow
- Loan lifecycle tracking

---

### 💳 Repayment & Payment Tracking
- Monthly installment tracking
- Penalty calculations for overdue payments
- Receipts & payment records
- Multiple payment methods support

---

### ⚠️ Defaulters Tracking
- Automatic overdue detection
- Follow-up stages management
- Assignment to treasurer for action
- Tracking of recovery progress

---

### 📅 Meetings Management
- Meeting scheduling
- Notices & agendas
- Attendance tracking
- Meeting status management

---

### 🔔 Notifications System
- Loan status notifications
- Payment reminders
- Meeting alerts
- System announcements

---

### 🔐 Security & Access Control
- Authentication via Laravel Breeze
- Role & Permission management via Spatie
- Protected routes & actions
- Audit-ready structure

---

## 👥 User Roles

### 👑 Admin
- Full system control
- Approves loans
- Assigns treasurer for verification
- Oversees defaulters tracking
- Manages meetings & notifications

### 💰 Treasurer
- Verifies collateral
- Contacts guarantors
- Records payments & savings
- Follows up on defaulters

### 👤 Member
- Applies for loans online
- Views savings & loan status
- Makes repayments
- Receives notifications

---

## 🛠️ Technology Stack

| Technology | Purpose |
|------------|---------|
Laravel 12 | Backend Framework
PHP 8+ | Server-side Language
MySQL | Database
Laravel Breeze | Authentication
Spatie Permissions | Roles & Permissions
Blade | Templating Engine
Bootstrap / Tailwind | Frontend UI

---

## 📂 Project Structure

app/
├── Http/
│ └── Controllers/
├── Models/
database/
└── migrations/
resources/
└── views/
routes/
└── web.php


---

## ⚙️ Installation Guide

### 1️⃣ Clone Repository

```bash
git clone https://github.com/yourusername/sacco-management-system.git
cd sacco-management-system

2️⃣ Install Dependencies
composer install
npm install && npm run build

3️⃣ Configure Environment

Copy environment file:

cp .env.example .env


Update database credentials inside .env.

4️⃣ Generate App Key
php artisan key:generate

5️⃣ Run Database Migrations
php artisan migrate

6️⃣ Seed Roles & Permissions (if available)
php artisan db:seed

7️⃣ Start Development Server
php artisan serve


Open in browser:

http://127.0.0.1:8000

🧠 System Workflow

Member registers and saves funds

Member applies for a loan online

Admin reviews application

Treasurer verifies collateral & guarantors

Admin approves loan

Loan is disbursed

Member repays in installments

Defaulters tracked for recovery

🔒 Security Features

Password hashing

CSRF protection

Role-based authorization

Secure financial data handling

📸 Screenshots

(Add system screenshots here after deployment)

📈 Future Improvements

Mobile Money Integration

SMS Notifications

Advanced Financial Reports

Mobile App Version

Online Member Portal

🤝 Contributing

Contributions are welcome.

Fork the repository and submit a pull request.
