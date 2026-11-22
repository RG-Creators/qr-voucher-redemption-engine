# RG QR Voucher & Ticket Redemption Engine

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777bb4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

An enterprise-ready, high-performance QR code voucher generation, camera scanning, and atomic redemption platform built for events, retail promotions, and access control. Built with PHP 8.2+ PDO, Tailwind CSS glassmorphic UI, and HTML5 WebCam QR decoding.

---

## 🏗️ System Architecture

```
+--------------------------------------------------------------------------+
|                              END USERS                                   |
|   Landing Portal (index.php)  -->  Customer View Voucher (viewqrforuser) |
+--------------------------------------------------------------------------+
                                     |
                               Shows QR Code
                                     v
+--------------------------------------------------------------------------+
|                      CASHIER & ADMIN SCANNER                             |
|   - Live Camera Scanner (admin/scan.php using html5-qrcode)              |
|   - Manual Token / Phone Lookup (admin/redeem.php)                       |
+------------------------------------|-------------------------------------+
                                     v
+--------------------------------------------------------------------------+
|                        API & VERIFICATION ENGINE                         |
|   - Token Validation Service (api/scan_verify.php)                       |
|   - Atomic Anti-Double-Redeem Lock (api/redeem_customer.php)             |
+------------------------------------|-------------------------------------+
                                     v
+--------------------------------------------------------------------------+
|                             DATABASE TIER                                |
|   - Row-level lock: UPDATE customers SET is_redeemed = 1                 |
|   - Audit Logging: INSERT INTO redemption_logs (admin, timestamp, IP)    |
+--------------------------------------------------------------------------+
```

---

## ⚡ Key Highlights

- **Cryptographic QR Tokens**: 64-character SHA-256 unique hashed tokens generated per voucher to eliminate predictability and counterfeit attacks.
- **Atomic Double-Spend Prevention**: Strict database transactions with conditional row updates (`WHERE is_redeemed = 0`) preventing race-condition multi-claims.
- **Hardware & WebCam Scanner**: Native integration with `html5-qrcode` providing instant rear/front camera scanning with auto-focus.
- **Manual Voucher Lookup Gateway**: Cashier manual redemption fallback for unreadable screens or damaged QR codes.
- **Comprehensive Audit Trail**: Every redemption captures cashier ID, client IP address, and high-precision timestamp in `redemption_logs`.

---

## 🛠️ Tech Stack

- **Backend**: Core PHP 8.2+ (PDO Prepared Statements, OpenSSL Random Generation)
- **Frontend**: Tailwind CSS, HTML5 Canvas QR Rendering, FontAwesome 6, Poppins Typography
- **Database**: MySQL 8.0+ / 9.0 (InnoDB with Foreign Keys and B-Tree Indexes on Tokens)

---

## 🚀 Installation & Local Development

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/RG-Creators/qr-voucher-redemption-engine.git
   cd qr-voucher-redemption-engine
   ```

2. **Database Import**:
   - Create a MySQL database named `scanner`:
     ```sql
     CREATE DATABASE scanner CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
     ```
   - Import `includes/scanner.sql`.

3. **Configure Database Settings**:
   - Edit `config/database.php` with your local MySQL credentials.

4. **Launch Application**:
   ```bash
   php -S localhost:8000
   ```
   - Customer Portal: `http://localhost:8000/index.php`
   - Admin Scanner & Redemption: `http://localhost:8000/admin/scan.php`

---

## 🔒 Security Hardening

- 100% Parameterized PDO queries with `PDO::ATTR_EMULATE_PREPARES => false`.
- Strict direct-access prevention via `APP_RUNNING` constant checks across all config and API scripts.
- Session fixation protection and Bcrypt password hashing (`PASSWORD_BCRYPT`).

---

## 📄 License
This repository is open-sourced under the [MIT License](LICENSE).
