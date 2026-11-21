
# ✨ Jewelry E-Commerce Website

A modern and elegant online jewelry store developed with **Laravel**. The platform is designed to offer customers a beautiful and seamless shopping experience where they can discover, explore, and purchase unique jewelry pieces.

The website focuses on elegance, simplicity, and functionality, combining a luxury-inspired interface with a powerful administration dashboard for managing the online store.

---

## 💎 About the Project

This project is an online jewelry shopping platform dedicated to selling elegant and handmade jewelry, including necklaces, rings, bracelets, earrings, and other accessories.

Customers can browse collections, explore product details, add their favorite pieces to their cart, and place orders online.

An integrated admin dashboard allows the store administrator to manage products, collections, categories, customers, orders, and website content.

---

## ✨ Features

### 🛍️ Customer Interface

- Elegant and responsive homepage.
- Browse jewelry collections.
- Explore products by category.
- Search and filter jewelry.
- View detailed product descriptions and images.
- Add products to the shopping cart.
- Manage cart quantities.
- Add favorite products to the wishlist.
- User registration and authentication.
- Manage personal profiles.
- Secure checkout process.
- Place and track orders.
- View previous purchases.

### 👑 Administration Dashboard

- Admin authentication and authorization.
- Dashboard with sales statistics.
- Product management (CRUD).
- Category management.
- Brand management.
- Customer management.
- Order management.
- Update order statuses.
- Manage homepage banners and slides.
- Manage discount coupons.
- Manage website settings.
- Manage product images and descriptions.

---

## 🎨 Design & Identity

The website follows a **Minimalist Luxury Jewelry** design concept.

### Color Palette

| Color | Hex Code | Usage |
|---|---|---|
| Warm Beige | `#E8DCCB` | Background |
| Soft Nude | `#D8C3B5` | Secondary sections |
| Champagne Gold | `#C6A56B` | Highlights and buttons |
| Soft Pink | `#F3E4E1` | Decorative elements |
| Ivory White | `#FAF8F5` | Main background |
| Dark Brown | `#463A32` | Text |

### Design Characteristics

- Minimalist and elegant layouts.
- Luxury-inspired typography.
- High-quality jewelry photography.
- Soft and neutral colors.
- Clean product cards.
- Responsive navigation.
- Mobile-friendly interface.
- Elegant shopping experience.

---

## 🛠️ Technologies Used

| Technology | Purpose |
|---|---|
| Laravel | Backend framework |
| PHP | Server-side programming |
| MySQL | Database management |
| HTML5 | Page structure |
| CSS3 | Styling and design |
| Bootstrap | Responsive layouts |
| JavaScript | Interactive features |
| Blade | Laravel templating engine |
| Composer | PHP dependencies |
| npm | Frontend dependencies |
| Git & GitHub | Version control |

---

## 📂 Project Structure

```text
jewelry-ecommerce/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Mail/
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── public/
│   ├── images/
│   ├── css/
│   └── js/
│
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── layouts/
│   │   ├── products/
│   │   ├── cart/
│   │   ├── orders/
│   │   └── auth/
│   ├── css/
│   └── js/
│
├── routes/
│   └── web.php
│
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

## 🚀 Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/YOUR-USERNAME/jewelry-ecommerce.git
```

### 2. Open the Project

```bash
cd jewelry-ecommerce
```

### 3. Install Dependencies

```bash
composer install
npm install
```

### 4. Configure Environment Variables

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Configure MySQL Database

Create a database named `jewelry_ecommerce` in phpMyAdmin.

Update your `.env` file:

```env
APP_NAME="Jewelry E-Commerce"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jewelry_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Run Migrations

```bash
php artisan migrate --seed
```

### 8. Create Storage Link

```bash
php artisan storage:link
```

### 9. Build Frontend Assets

```bash
npm run build
```

### 10. Start the Development Server

```bash
php artisan serve
```

Visit:

http://127.0.0.1:8000

---

## 📸 Screenshots

Add screenshots of your website here:

| Homepage | Shop Page |
|---|---|
| Add homepage screenshot | Add shop screenshot |

| Product Details | Admin Dashboard |
|---|---|
| Add product screenshot | Add dashboard screenshot |

---

## 🔮 Future Improvements

- Stripe online payment integration.
- Google authentication.
- Customer product reviews and ratings.
- Personalized jewelry recommendations.
- Email order confirmations.
- Multilingual website (French and English).
- Advanced sales analytics.
- Live customer support chatbot.
- Loyalty and rewards program.

---

## 👩‍💻 Developer

**Developed by:** Your Name

**Project Type:** Jewelry E-Commerce Website

**Framework:** Laravel

**Academic Field:** Software Development & Web Technologies

---

## 📄 License

This project is developed for educational and personal purposes.

---

⭐ If you like this project, don't forget to give the repository a star!
