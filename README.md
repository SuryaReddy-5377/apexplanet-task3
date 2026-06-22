# 🚀 ApexPlanet Internship - Task 3

## Backend Development & Database Integration

---

## 📋 Project Overview

This is a complete **User Management System** built with PHP and MySQL as part of the ApexPlanet Internship Task 3. The system includes user authentication, role-based access control, complete CRUD operations, and profile management with picture upload functionality.

---

## ✨ Features

### 🔐 Authentication System
- ✅ **User Registration** - Secure registration with password hashing
- ✅ **User Login** - Session-based login system
- ✅ **Role-Based Access** - Admin and User roles with different permissions
- ✅ **Logout** - Secure session destruction

### 📊 CRUD Operations
- ✅ **Create** - Add new users (Admin only)
- ✅ **Read** - View all users in a table (Admin only)
- ✅ **Update** - Edit existing user details (Admin only)
- ✅ **Delete** - Delete users with confirmation popup (Admin only)

### 👤 Profile Management
- ✅ View Profile Information
- ✅ Edit Profile (Name, Email, Password)
- ✅ Profile Picture Upload
- ✅ File Validation (Type: JPG, PNG, GIF, WEBP | Size: Max 5MB)

### 🛡️ Security Features
- ✅ **Prepared Statements** - SQL Injection prevention
- ✅ **Server-Side Validation** - All inputs validated
- ✅ **Password Hashing** - Using `password_hash()`
- ✅ **Session Management** - Secure session handling
- ✅ **Role-Based Access** - Admin/User separation

### 🎨 UI/UX
- ✅ **Dark Mode Toggle** - Beautiful dark/light theme switching
- ✅ **Responsive Design** - Mobile-first approach
- ✅ **Smooth Animations** - Fade-in, hover effects, transitions
- ✅ **Toast Notifications** - Success/error messages
- ✅ **Bootstrap 5** - Modern, clean UI

---

## 🗄️ Database Design

### ER Diagram
┌─────────────────┐ ┌─────────────────┐
│ users │ │ roles │
├─────────────────┤ ├─────────────────┤
│ id (PK) │────▶│ id (PK) │
│ first_name │ │ role_name │
│ last_name │ │ created_at │
│ email (Unique) │ └─────────────────┘
│ password │
│ role_id (FK) │
│ profile_pic │
│ created_at │
│ updated_at │
└─────────────────┘

### Database Schema

```sql
-- Create database
CREATE DATABASE apexplanet_task3;
USE apexplanet_task3;

-- Roles table
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT DEFAULT 2,
    profile_pic VARCHAR(255) DEFAULT 'default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Insert default roles
INSERT INTO roles (role_name) VALUES ('admin'), ('user');

-- Insert admin user (password: Admin@123)
INSERT INTO users (first_name, last_name, email, password, role_id) 
VALUES ('Admin', 'Apex', 'admin@apexplanet.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);