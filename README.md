# RG QR Voucher & Ticket Redemption Engine

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777bb4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)

> [!IMPORTANT]
> **Commercial Intellectual Property Notice & Source Code Sale:**
> This repository contains a production-ready, proprietary software solution engineered by **Ronit Goswami (RG Creators)**. It is available for commercial acquisition, full IP buyout, or custom enterprise licensing.
> 
> - **Asset Classification:** Retail Tech & Atomic Redemption
> - **Commercial Valuation / Fair Market Estimate:** **₹25,000 – ₹45,000 INR** *(Full Source Code & Commercial Rights)*
> - **Full IP Buyout & Exclusive Ownership:** **₹60,000 INR**
> - **Target Industry:** Retail Chains, Restaurants, Marketing Agencies & Loyalty Platforms
> - **Handover Deliverables:**
>   - Complete QR Voucher generation and atomic redemption engine source code
>   - Modern Tailwind CSS responsive merchant scanner interface
>   - Atomic database transactions preventing double-redemption under race conditions
>   - Campaign management, voucher expiration logic, and redemption audit logging
>   - Database migrations, seeders, API documentation, and deployment runbooks
>   - 30 days of dedicated technical handover support
> - **Official Portfolio:** [https://rgcreators.netlify.app](https://rgcreators.netlify.app)
> - **Acquisition & Licensing Inquiries:** [rgcreatorsofficial@gmail.com](mailto:rgcreatorsofficial@gmail.com)

---

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


---

## 🚀 Future Roadmap & Next-Gen Implementation Ideas (Ready for Expansion)

The following extensive architectural and feature expansions have been designed and architected for rapid implementation, transforming this solution into a high-capacity, enterprise-grade platform:

1. **Redis Distributed Lock Concurrency Engine:** Sub-millisecond atomic locking preventing high-concurrency race condition redemptions.
2. **Dynamic Time-Rotating Encrypted QR Codes (TOTP):** Moving QR codes that refresh every 10 seconds preventing screenshot and photo fraud.
3. **Multi-Merchant Settlement Clearinghouse:** Cross-brand voucher redemption with automated inter-merchant financial settlement calculations.
4. **Geo-Fenced Voucher Activation:** Vouchers only activate and display redemption button when customer is within 100 meters of the outlet.
5. **High-Volume Bulk Campaign Generator with PDF Export:** Instant generation of 100,000+ unique voucher batches with print-ready vector PDFs.
6. **Drop-In Webhook Bridge for Cloud POS & E-Commerce:** 1-click plugins for Shopify, WooCommerce, and custom cloud POS cash registers.
7. **Apple Wallet & Google Pay Pass Generation:** Add vouchers directly to native smartphone mobile wallets with automated location lock-screen prompts.
8. **WhatsApp 1-Click Voucher Claim & Redeem Bot:** Customers receive, store, and present vouchers directly inside WhatsApp.
9. **Granular Campaign Velocity Analytics:** Real-time dashboards showing redemption speed, peak footfall hours, and basket size uplift.
10. **Tiered Basket Value Validation:** Minimum spend rules and category-specific redemption restrictions validated at scan time.

---

## 💼 Commercial Acquisition & Licensing Agreement

For source code inspection, live technical walkthrough, pricing negotiations, or exclusive enterprise buyout:
- **Lead Architect & Developer:** Ronit Goswami (RG Creators)
- **Primary Contact Email:** [rgcreatorsofficial@gmail.com](mailto:rgcreatorsofficial@gmail.com)
- **Official Portfolio:** [https://rgcreators.netlify.app](https://rgcreators.netlify.app)
- **Location:** Kolkata, West Bengal, India
