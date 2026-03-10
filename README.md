# Full PHP + MySQL E-Commerce Web Application

## 1) Recommended Architecture

For your assignment requirements (sessions, server-side validation, CRUD, and quick deployment), **PHP + MySQL** is the best fit.

### System design
- **Frontend (SSR)**: PHP-rendered HTML, CSS for responsive UI.
- **Backend**: PHP actions (form handlers) + optional JSON API endpoints.
- **Database**: MySQL with normalized tables for users, products, orders, reviews, wishlist, discounts.
- **Auth**: Session-based login/logout.
- **Security**: Password hashing (`password_hash`), PDO prepared statements, server-side validation.

## 2) Project folder structure

```txt
.
├── actions/                 # Form submission handlers (server-side logic)
├── api/                     # JSON endpoints (optional API integration)
├── assets/
│   └── css/style.css        # Styling
├── database/schema.sql      # MySQL schema + seed data
├── includes/
│   ├── config.php           # Session + DB config
│   ├── helpers.php          # Shared helpers + validation
│   ├── header.php
│   └── footer.php
├── public/                  # Main pages
│   ├── index.php
│   ├── products.php
│   ├── product.php
│   ├── register.php
│   ├── login.php
│   ├── cart.php
│   ├── checkout.php
│   ├── orders.php
│   ├── wishlist.php
│   ├── admin.php
│   └── admin_product_edit.php
└── README.md
```

## 3) Features implemented

### Core
- Homepage with featured products.
- Product listing with search + category filter.
- Product details.
- Register/Login/Logout.
- Shopping cart (session-based add/remove).
- Checkout with payment simulation (Stripe/PayPal/COD options).
- Order history for customers.
- Admin dashboard to add/edit/delete products + view orders/users.

### Bonus
- Product reviews + ratings.
- Discount code system.
- Wishlist per user.

## 4) Database schema

See `database/schema.sql` for complete schema and sample data.

Main entities:
- `users`
- `categories`
- `products`
- `reviews`
- `wishlists`
- `discounts`
- `orders`
- `order_items`

## 5) Security checklist

- Passwords hashed with `password_hash`.
- Login validated with `password_verify`.
- SQL injection mitigated by prepared statements (PDO).
- Server-side validation for required fields.
- Session-based authorization checks for protected/admin pages.

## 6) Local run instructions

### Requirements
- PHP 8+
- MySQL 8+

### Setup
1. Create DB + tables:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
2. Update DB credentials via env vars or edit `includes/config.php` defaults:
   ```bash
   export DB_HOST=127.0.0.1
   export DB_PORT=3306
   export DB_NAME=ecommerce_app
   export DB_USER=root
   export DB_PASS=
   ```
3. Start PHP server from repo root:
   ```bash
   php -S 0.0.0.0:8000
   ```
4. Open:
   - `http://localhost:8000/public/index.php`

### Admin account
Create a user first, then promote to admin in MySQL:
```sql
UPDATE users SET role='admin' WHERE email='your-email@example.com';
```

## 7) Step-by-step build roadmap used

1. Designed architecture and entities.
2. Created SQL schema + seed data.
3. Built reusable config/helpers/layout.
4. Implemented storefront pages.
5. Implemented auth + cart + checkout + orders.
6. Implemented admin product CRUD.
7. Added reviews/ratings, wishlist, discounts.
8. Added simple JSON API endpoints.
9. Validated syntax and documented run steps.
