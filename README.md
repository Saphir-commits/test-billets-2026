# Subscription Management System

A take-home backend developer assignment for Billets.ca. A subscription management application built in vanilla PHP demonstrating authentication, role-based access control, relational database design, price snapshotting, and subscription lifecycle management.

---

## Requirements

- PHP 8.1 or higher
- Composer
- MariaDB / MySQL

---

## Installation

**1. Clone and install dependencies**

```bash
composer install
```

**2. Configure the environment**

Copy the example environment file and fill in your database credentials:

```bash
cp .env.exemple .env
```

```env
APP_TIMEZONE=America/Toronto

DB_HOST=localhost
DB_PORT=3306
DB_NAME=subscription_management
DB_USER=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
```

**3. Run migrations and seed the database**

This command creates all tables and inserts the default seed data (roles, users, product types, pricing options, and products):

```bash
php console/migration
```

**4. Start a local PHP server**

```bash
php -S localhost:8000 -t public
```

---

## Running the Tests

Run a specific test file:

```bash
php vendor/bin/phpunit tests/AuthenticationTest.php
php vendor/bin/phpunit tests/RoleTest.php
php vendor/bin/phpunit tests/UserTest.php
php vendor/bin/phpunit tests/ProductTypeTest.php
php vendor/bin/phpunit tests/PricingOptionTest.php
php vendor/bin/phpunit tests/ProductTest.php
php vendor/bin/phpunit tests/SubscriptionCreateTest.php
php vendor/bin/phpunit tests/ExpiredAtTest.php
php vendor/bin/phpunit tests/PriceSnapshotTest.php
php vendor/bin/phpunit tests/AdminAccessTest.php
```

Run the full end-to-end fuzzy lifecycle test:

```bash
php vendor/bin/phpunit tests/SubscriptionLifecycleTest.php
```

Run the entire test suite:

```bash
php vendor/bin/phpunit
```

---

## Default Credentials

### Admin

```
Email:    admin@billets.ca
Password: test-billet-2026
```

Admin has full CRUD access across all resources. This was a deliberate technical choice: in a production system, permissions would be more granular, but for the scope of this assignment the role boundary is kept simple — admin manages everything, users manage their own subscriptions.

### User

```
Email:    user@billets.ca
Password: user-billet-2026
```

Users can create, view, edit, and cancel their own subscriptions. They can also browse products, product types, and pricing options in read-only mode.

---

## Database Schema

The relational diagram is available at `relationnal_diagram.png`. The DBML source is at `db.dbml`.

```
roles
  id, name, created_at, edited_at

users
  id, name, email, password, role_id -> roles.id, created_at, edited_at

products_types
  id, name, created_at, edited_at

products_pricing_options
  id, name, nb_days, created_at, edited_at

products
  id, product_type_id -> products_types.id
      product_pricing_option_id -> products_pricing_options.id
      price, created_at, edited_at

subscriptions
  id, user_id -> users.id
      product_id -> products.id
      price (snapshot), expired_at, canceled_at, created_at, edited_at
```

### Key design decisions

**Price snapshot** — The `subscriptions.price` column stores the product price at the moment the subscription is created. This is intentional: if the product price changes later, existing subscriptions are unaffected. This mimics real-world billing behavior.

**`expired_at` calculation** — When a subscription is created, the expiration date is computed by adding the product's `nb_days` (from the linked pricing option) to the current timestamp. If the product changes on the edit form, the `expired_at` is recalculated via an AJAX call before the form submits.

**Soft cancellation** — Subscriptions are never hard-deleted by users. Cancellation sets `canceled_at` to the current timestamp. Only an admin can permanently delete a subscription. This preserves audit history.

**No ORM** — Raw PDO with prepared statements is used throughout. This is a deliberate choice: without an ORM, the model ends up being a collection of queries rather than a genuine entity that mirrors the database structure. Given strong SQL knowledge across MySQL/MariaDB, SQL Server, and Oracle, this was an opportunity to demonstrate relational thinking directly.

In a modern production application, an ORM would be the natural choice. Eloquent is a familiar tool from daily Laravel work, and Doctrine ORM follows a very similar pattern — both allow the model to behave as a true entity rather than a query wrapper.

**MariaDB** — Chosen as the database engine. The schema uses `TIMESTAMP` columns, foreign key constraints, and standard SQL — all fully compatible with MariaDB.

---

## Components

These Symfony and community components were selected. Several were already familiar from daily Laravel work, since Laravel ships several of them internally.

| Component | Purpose |
|---|---|
| `symfony/http-foundation` | Request/Response abstraction, session management |
| `symfony/password-hasher` | Secure password hashing (bcrypt) |
| `symfony/security-csrf` | CSRF token generation and validation |
| `symfony/var-dumper` | Debug output (dev only) |
| `nesbot/carbon` | Date arithmetic for expiration calculation |
| `vlucas/phpdotenv` | `.env` file loading |
| `phpunit/phpunit` | Unit and integration testing |

---

## Project Structure

```
app/
  controllers/       # Route handlers (Auth, User, Role, Product, Subscription, etc.)
  models/            # PDO-based models with base CRUD (Database, Model, and entity models)
  helpers/           # Auth session helpers, CSRF wrapper

public/
  index.php          # Front controller and router

ressources/
  views/             # PHP view templates organized by resource

console/
  migration          # CLI script: creates tables and seeds data

tests/               # PHPUnit test files
```

---

## AI Assistance

This project was designed and built with the help of Claude Code (Anthropic). The full conversation transcript is available in `claude_trancript.md`.
