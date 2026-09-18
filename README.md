# Marketshop

Laravel-based E-Commerce Backend API

A clean, structured Laravel backend for an online marketplace.
Built with Repository pattern, Action classes, Services, API versioning (V1 & V2), Sanctum authentication, Fortify, Spatie Permission, and full test coverage using Pest.

---

## Features

- **Products Management**
  CRUD products with images, stock, price, discount, SKU, soft deletes.

- **Brands & Categories**
  Full CRUD with relationships to products.

- **Shopping Cart**
  Cart + Cart Items management.

- **Wishlist**
  User wishlist functionality.

- **Orders & Order Items**
  Order creation, management, and order items.

- **Coupons**
  Coupon system with usage tracking and validation rules.

- **Reviews**
  Product reviews.

- **Payments**
  Payment records and status handling.

- **Authentication & Authorization**
  - Laravel Fortify
  - Laravel Sanctum (API tokens)
  - Social Authentication
  - Two-Factor Authentication (2FA)
  - Passkeys
  - Spatie Laravel Permission (roles & permissions)

- **API Versioning**
  Separate V1 and V2 API resources.

- **Architecture**
  - Actions (Create / Update / Delete)
  - Repositories + Interfaces
  - Services
  - DTOs
  - Policies
  - Observers
  - Events & Listeners
  - Notifications
  - Jobs
  - Custom Validation Rules

- **Testing**
  Comprehensive Feature & Unit tests (API + Actions + Repositories + Services) using **Pest**.

---

## Tech Stack

| Technology          | Purpose                       |
|---------------------|-------------------------------|
| Laravel             | Framework                     |
| Laravel Sanctum     | API Authentication            |
| Laravel Fortify     | Authentication scaffolding    |
| Spatie Permission   | Roles & Permissions           |
| Pest                | Testing                       |
| Repository Pattern  | Data access layer             |
| Action Classes      | Business logic encapsulation  |

---

## Project Structure

```
app/
├── Actions/              # Create / Update / Delete actions
├── DTOs/
├── Events/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── V1/
│   │   │   └── V2/
│   │   ├── Auth/
│   │   └── ...
│   ├── Requests/
│   └── Resources/
├── Jobs/
├── Listeners/
├── Models/
├── Notifications/
├── Observers/
├── Policies/
├── Providers/
├── Repositories/         # Interfaces + Implementations
├── Rules/
├── Services/
└── ...
```

---

## Requirements

- PHP 8.2+
- Composer
- Node.js & NPM (for frontend assets if needed)
- MySQL / SQLite / PostgreSQL
- Redis (optional – for queues & cache)

---

## Installation

```bash
# Clone the repository
git clone <repository-url>
cd Marketshop

# Install PHP dependencies
composer install

# Install Node dependencies (if using frontend assets)
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
# Then run migrations & seeders
php artisan migrate --seed

# Create storage link
php artisan storage:link

# (Optional) Run queue worker
php artisan queue:work
```

---

## API Endpoints

All API routes are prefixed with `/api`.

### Authentication

- Sanctum token-based authentication
- Fortify authentication endpoints
- Social Auth
- Two-Factor Authentication

### API Resources (V1 & V2)

| Resource    | Endpoint (example)          |
|-------------|-----------------------------|
| Brands      | `/api/V1/brands`            |
| Categories  | `/api/V1/categories`        |
| Products    | `/api/V1/products`          |
| Carts       | `/api/V1/carts`             |
| Cart Items  | `/api/V1/cartitems`         |
| Orders      | `/api/V1/orders`            |
| Order Items | `/api/V1/ordersitems`       |
| Coupons     | `/api/V1/coupons`           |

Same resources are available under `/api/V2/...`.

Authenticated routes require `Authorization: Bearer {token}` header.

---

## Architecture Overview

### 1. Repository Pattern

All data access goes through repositories that implement interfaces.

Bound in `RepositoryServiceProvider`.

### 2. Action Classes

Business logic for create / update / delete operations is isolated in Action classes under `app/Actions/`.

### 3. Services

Higher-level business logic and orchestration live in `app/Services/`.

### 4. Events & Listeners

- `OrderCreated` → stock deduction + notifications
- `CouponUsed` → logging & notifications
- Product & Brand related events

### 5. Policies & Authorization

Fine-grained authorization using Laravel Policies + Spatie Permission.

---

## Testing

The project uses **Pest** for testing.

```bash
# Run all tests
./vendor/bin/pest

# Run specific test suite
./vendor/bin/pest --group=api
```

### Covered areas

- Feature tests for all API resources
- Unit tests for Actions
- Unit tests for Repositories
- Unit tests for Services
- Authentication flows

---

## Database

Main tables:

- `users`
- `categories`
- `brands`
- `products`
- `product_images`
- `carts`
- `cart_items`
- `orders`
- `orders_items`
- `coupons`
- `coupon_user`
- `wishlists`
- `reviews`
- `payments`
- Spatie permission tables
- Sanctum personal access tokens
- Two-factor & Passkeys tables

---

## Security Features

- SQL Injection protection trait
- Custom validation rules (price, quantity, coupon expiry, brand logo, ...)
- Policies for every major model
- Rate limiting on API routes
- Soft deletes on products
- Two-Factor Authentication
- Passkeys support

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
