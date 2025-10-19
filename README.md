# 🎓 Edunook LMS (Learning Management System)

**Live Demo:** [https://dawitedunooklms.eagletechafrica.com](https://dawitedunooklms.eagletechafrica.com)

---

## 📘 Overview

Edunook LMS is a lightweight, secure Learning Management System built with **PHP** and **MySQL**, featuring user authentication, form security, and email verification.  
Developed as part of the **RITA Africa Cloud Computing Program**, this project demonstrates real-world web security and deployment practices.

---

## 🔒 Key Features

✅ **Honeypot Protection** – Blocks bot registrations by detecting hidden form traps.  
✅ **Email Verification** – Ensures only verified users can log in.  
✅ **Account Management** – Includes verification reminders, activation/suspension logic.  
✅ **Responsive Design** – CSS-enhanced login and registration forms.  
✅ **Database Integration** – Stores and validates user records securely.  
✅ **Live Deployment** – Hosted on subdomain with real database connection.

---

## 🗄️ Tech Stack

| Category | Technology |
|-----------|-------------|
| Backend | PHP (Core) |
| Database | MySQL |
| Frontend | HTML, CSS, JS |
| Hosting | hPanel (Hostinger) |
| Version Control | Git + GitHub |

---

## ⚙️ Database Structure (students table)

| Field | Type | Description |
|-------|------|-------------|
| sid | INT (Primary Key) | Unique student ID |
| firstname | VARCHAR(100) | User’s first name |
| lastname | VARCHAR(100) | User’s last name |
| email | VARCHAR(150) | User email (unique) |
| password | VARCHAR(255) | Hashed password |
| gender | VARCHAR(10) | Male/Female |
| dob | DATE | Date of birth |
| token | VARCHAR(20) | Email verification token |
| email_verified | TINYINT(1) | 1 if verified |
| active | TINYINT(1) | 1 if active |

---

## 🚀 Deployment

The project is live at:  
🔗 [https://dawitedunooklms.eagletechafrica.com](https://dawitedunooklms.eagletechafrica.com)

Deployed using **Hostinger hPanel** with **MySQL database** `u376937047_dawitsamuel_lm`.

---

## 🧠 Learning Outcomes

By completing this project, I gained hands-on experience in:

- Web form hardening techniques (honeypot, validation)
- Backend authentication flows
- Database security practices
- Email verification implementation
- Professional Git & GitHub workflow
- Live web deployment and troubleshooting

---

## 🧩 Project Structure

dawitedunooklms/
├── assets/
│ ├── css/ (dashboard.css, login.css, styles.css)
│ ├── js/ (dashboard.js, login.js)
│ └── images/ (logo.png)
├── config/
│ └── db_config.php
├── dashboard.php
├── index.html
├── login.html
├── login.php
├── logout.php
├── register.php
├── resend_verification.php
└── verify_email.php


---

## 🏁 Credits

👨‍💻 **Developer:** Dawit Samuel  
🌍 **Subdomain:** [dawitedunooklms.eagletechafrica.com](https://dawitedunooklms.eagletechafrica.com)  
🎓 **Training:** RITA Africa Cloud Computing Program  
📅 **Year:** 2025  

---

## 📬 Contact

For collaborations or inquiries:  
📧 Email: dawitsamuel.dev@gmail.com  
💼 GitHub: [@DawitSamuel-Dev](https://github.com/DawitSamuel-Dev)  
🌐 LinkedIn: [Dawit Samuel](https://linkedin.com/in/dawit-samuel)

---

### ⭐ If you found this project useful, give it a star on GitHub!
