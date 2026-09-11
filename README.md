<div align="center">

# 👟 Shahi Peshawari

### Premium Peshawari Chappal E-Commerce Store

A complete, production-ready e-commerce solution built with **PHP + Firebase**, featuring a professional admin panel, real-time order management, and a beautiful responsive storefront.

[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Firebase](https://img.shields.io/badge/Firebase-Realtime_DB-FFCA28?style=for-the-badge&logo=firebase&logoColor=white)](https://firebase.google.com)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://html5.org)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://css3.org)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://javascript.com)

</div>

---

## ✨ Features

### 🛍️ Customer Storefront
- **Responsive Design** — Mobile-first, works on all devices
- **Product Catalog** — Categories (Classic, Premium, Casual, New Design)
- **Product Cards** — Hover image swap, star ratings, quick add-to-cart
- **Quick Buy Modal** — Image slider, size selector, payment methods
- **Product Detail** — Image gallery, similar products, Buy Now
- **Shopping Cart** — Persistent cart (localStorage), quantity controls
- **Order Tracking** — Track by phone or order ID with 4-step progress
- **Order Confirmation** — PDF receipt, confetti animation, share/copy

### 🔧 Admin Panel
- **Secure Login** — Firebase Auth with re-authentication
- **Dashboard** — Stats cards, recent orders overview
- **Orders Management** — Filter by date/status, view details, update status, delete
- **PDF Export** — Download orders report with product images
- **Product CRUD** — Add/edit products with image upload from device
- **Star Ratings** — Interactive 5-star rating system
- **Inventory** — Stock management, sync static products
- **User Management** — View/delete Firebase Auth users
- **Settings** — WhatsApp config, store info, payment methods with QR upload

### 💳 Payment System
- **Multiple Methods** — COD, EasyPaisa, JazzCash, Bank Transfer
- **QR Code Support** — Upload from device or enter URL
- **Admin Configurable** — Change payment details in real-time from admin panel

### 📧 Email System
- **Order Confirmation** — Professional HTML email via SMTP
- **Branded Design** — Custom email template with order details

---

## 🚀 Quick Setup

### Prerequisites
- PHP 7.4 or higher
- Web server (Apache/Nginx/XAMPP/Laragon)
- Firebase account (free tier works)
- Gmail account (for SMTP emails)

### Step 1: Clone the Repository

```bash
git clone https://github.com/Muzamilshah11/shahi-peshawari.git
cd shahi-peshawari
```

### Step 2: Configure Firebase

1. Go to [Firebase Console](https://console.firebase.google.com)
2. Create a new project
3. Enable **Realtime Database** (start in test mode)
4. Enable **Authentication** → Email/Password + Google sign-in
5. Go to **Project Settings** → **General** → **Your apps** → Add web app
6. Copy the config values

### Step 3: Configure the Store

1. Copy `config.example.php` to `config.php`
2. Open `config.php` and fill in your values:

```php
// Firebase Config
define('FIREBASE_API_KEY',             'your-actual-api-key');
define('FIREBASE_AUTH_DOMAIN',         'your-project.firebaseapp.com');
define('FIREBASE_DATABASE_URL',        'https://your-project-default-rtdb.firebaseio.com');
define('FIREBASE_PROJECT_ID',          'your-project-id');
define('FIREBASE_STORAGE_BUCKET',      'your-project.appspot.com');
define('FIREBASE_MESSAGING_SENDER_ID', '123456789');
define('FIREBASE_APP_ID',              '1:123456789:web:abcdef');

// SMTP Config (Gmail)
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');

// Admin
define('ADMIN_EMAIL', 'admin@yourstore.com');
define('ADMIN_PASS',  'YourSecurePassword');
```

### Step 4: Create Admin Account

1. Go to Firebase Console → Authentication → Users
2. Click "Add user"
3. Enter admin email and password
4. These credentials will be used to login to admin panel

### Step 5: Upload to Your Server

```bash
# Upload all files EXCEPT:
# - config.php (keep this local)
# - uploads/products/*.png (product images)
```

### Step 6: Access Your Store

| Page | URL |
|------|-----|
| Home | `http://yourdomain.com/` |
| Shop | `http://yourdomain.com/shop.php` |
| Admin | `http://yourdomain.com/admin.php` |

---

## 📁 Project Structure

```
shahi-peshawari/
├── config.php              # Your config (gitignored)
├── config.example.php      # Config template
├── header.php              # CSS, nav, Firebase SDK
├── footer.php              # Cart JS, utilities
├── index.php               # Home page
├── shop.php                # Shop with filters
├── product.php             # Product detail
├── cart.php                # Cart & checkout
├── auth.php                # Login / Register
├── orders.php              # User order history
├── track.php               # Order tracking
├── order_success.php       # Receipt + PDF
├── send_order_email.php    # SMTP email sender
├── upload_product_image.php # Image upload handler
├── product_data.php        # Static product catalog
├── admin.php               # Admin dashboard
├── admin_sidebar.php       # Sidebar + auth
├── admin_orders.php        # Orders management
├── admin_products.php      # Product CRUD
├── admin_users.php         # User management
├── admin_settings.php      # Store settings
├── admin_customers.php     # Customer management
├── admin_categories.php    # Category management
├── admin_subscribers.php   # Subscribers
├── admin_reviews.php       # Reviews
├── admin_auth.php          # Admin auth endpoint
└── uploads/
    └── products/           # Product images
```

---

## 🔐 Security Notes

- `config.php` is **gitignored** — never commit secrets
- All Firebase rules should be configured in Firebase Console
- Admin panel uses Firebase Auth with re-authentication for sensitive ops
- Image uploads are validated (type, size)
- SMTP credentials stored in server-side config only

---

## 🎨 Customization

### Change Store Name
Edit `config.php`:
```php
define('SITE_NAME', 'Your Store Name');
```

Then update in:
- `header.php` — Brand name in nav
- `admin_sidebar.php` — Admin panel brand
- `send_order_email.php` — Email header

### Change Colors
Edit CSS variables in `header.php`:
```css
:root {
    --blue: #0984e3;      /* Primary color */
    --blue-dark: #0769b5;
    --black: #1a1a2e;
    --white: #ffffff;
    --red: #dc2626;
    --green: #16a34a;
}
```

### Add Products
1. Login to admin panel
2. Go to Inventory → Add Product
3. Fill in details, upload images
4. Or edit `product_data.php` for static products

---

## 📱 Mobile Responsive

The store is fully responsive with:
- Mobile-first CSS design
- Touch-friendly buttons and forms
- Auto-hiding header on scroll
- Bottom navigation bar
- Collapsible admin sidebar

---

## 🛠️ Tech Stack Details

| Component | Technology |
|-----------|-----------|
| Backend | PHP 7.4+ |
| Database | Firebase Realtime Database |
| Auth | Firebase Authentication |
| Email | Raw SMTP via PHP sockets |
| Frontend | Vanilla HTML/CSS/JS |
| PDF | html2canvas + jsPDF |
| Icons | Font Awesome 6 |
| Hosting | Any PHP-capable server |

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Author

Built with ❤️ by **Muzamil Shah**

---

<div align="center">

**If this project helped you, give it a ⭐!**

</div>
