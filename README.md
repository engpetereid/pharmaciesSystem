# pharmacies system — Pharmacy Management System

A full-stack web application built with **Laravel 13** that provides a centralised platform for managing pharmacies, drug inventory, orders, and invoicing. The system supports two distinct user roles — **Admin** and **Supervisor** — each with a dedicated portal and a parallel RESTful API layer secured with Laravel Sanctum.

---

## Table of Contents

- [Description](#description)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [API Documentation](#api-documentation)
- [Database Design](#database-design)
- [Architecture & Design Patterns](#architecture--design-patterns)
- [Future Improvements](#future-improvements)
- [License](#license)

---

## Description

ELApharma solves the operational complexity of multi-pharmacy management by providing:

- **Centralized admin control** over pharmacies, drugs, categories, users, and orders.
- **Per-pharmacy supervisor portals** for managing local inventory, placing purchase orders, and generating sales invoices.
- **Automatic stock tracking** — invoices decrement warehouse stock, accepted orders increment it, and threshold alerts fire when stock drops below a configurable minimum.
- **A versioned REST API** (`/api/v1`) that mirrors every web feature, enabling mobile or third-party integrations.

---

## Features

### Admin Panel
- **Dashboard** with live KPIs: total pharmacies, users, pending orders, today's invoices, and recent low-stock notifications.
- **Drug management** — full CRUD for drugs with category assignment and pricing.
- **Category management** — group drugs into categories.
- **Pharmacy management** — create pharmacies, assign supervisor users, and seed initial drug inventory.
- **User management** — create/edit/delete admin and supervisor accounts.
- **Order review** — view all incoming pharmacy orders and accept them (stock is updated atomically in a DB transaction).
- **Invoice management** — create admin-level invoices with automatic line-item price calculation.
- **Stock notifications** — view alerts raised when a pharmacy's drug stock falls below the configured minimum.

### Supervisor Panel
- **Dashboard** with per-pharmacy KPIs: total stock lines, low-stock count, pending orders, and today's invoices.
- **Warehouse view** — browse current drug quantities and pending purchase orders scoped to the supervisor's pharmacy.
- **Minimum threshold management** — set per-drug minimum quantities to trigger low-stock alerts.
- **Purchase orders** — place drug replenishment orders that appear in the Admin order queue.
- **Invoice management** — create, edit, and delete sales invoices with real-time stock deduction, line-item calculation, and minimum-threshold alerting. Editing restores old stock before applying new items.

### REST API (`/api/v1`)
- Token-based authentication with Laravel Sanctum.
- Mirrors all Admin and Supervisor web functionality.
- Rate-limited auth endpoints (10 requests/minute) to mitigate brute-force attacks.
- Role-enforced middleware on every protected route.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.3 |
| Framework | Laravel 13 |
| Authentication | Laravel Breeze (web) · Laravel Sanctum (API) |
| Database | SQLite (default) — easily switched to MySQL/PostgreSQL via `.env` |
| Frontend | Blade templates · Tailwind CSS · Vite |
| Testing | PHPUnit 12 |
| Dev Tools | Laravel Pail (log tailing) · Laravel Pint (code style) · Faker |

---

## Installation

### Prerequisites

- PHP ≥ 8.3 with the `pdo_sqlite` extension (or configure MySQL/PostgreSQL in `.env`)
- Composer
- Node.js ≥ 18 + npm

### Steps

```bash
# 1. Clone the repository
git clone <repository-url> ELApharma
cd ELApharma

# 2. Install PHP dependencies
composer install

# 3. Set up the environment file
cp .env.example .env
php artisan key:generate

# 4. Run database migrations and seed the default admin account
php artisan migrate --seed

# 5. Install and build frontend assets
npm install
npm run build
```

> **Default admin credentials (seeded):**
> - Email: `admin@example.com`
> - Password: `12345678`

### One-Command Setup (via Composer script)

```bash
composer setup
```

This runs `composer install`, copies `.env`, generates the app key, runs migrations, installs npm packages, and builds assets in one step.

---

## Usage

### Development Server

```bash
composer dev
```

This concurrently starts the PHP dev server, the queue worker, the Pail log watcher, and the Vite HMR server.

### Running Tests

```bash
composer test
# or directly
php artisan test
```

### Manual Workflow

1. **Log in** at `/login` with admin credentials.
2. **Create supervisor users** under Admin → Users.
3. **Create categories and drugs** under Admin → Categories / Drugs.
4. **Create pharmacies** and assign them to supervisor accounts (Admin → Pharmacies).
5. **Add drug stock** to a pharmacy via the "Add Drugs" action on the pharmacy detail page.
6. **Log in as a supervisor** to access the Supervisor portal.
7. **Supervisors** can view warehouse stock, set minimum thresholds, place orders, and create invoices.
8. **Admins** accept pending orders — this atomically increments warehouse stock.

---

## Project Structure

```
ELApharma/
├── app/
│   ├── DTOs/                           # Data Transfer Objects (one per entity)
│   │   ├── SaveCategoryDTO.php
│   │   ├── SaveDrugDTO.php
│   │   ├── SaveInvoiceDTO.php
│   │   ├── SaveInvoiceItemDTO.php
│   │   ├── SaveNotificationDTO.php
│   │   ├── SaveOrderDTO.php
│   │   ├── SavePharmaDTO.php
│   │   ├── SaveUserDTO.php
│   │   └── SaveWarehouseDTO.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Web controllers for admin panel
│   │   │   ├── Supervisor/     # Web controllers for supervisor panel
│   │   │   └── Api/            # Stateless API controllers (Sanctum)
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php      # Role guard: admin
│   │   │   └── SupervisorMiddleware.php # Role guard: supervisor
│   │   └── Requests/           # Form Request validation classes (per-entity)
│   ├── Models/                 # Eloquent models
│   │   ├── User.php            # Authenticatable; role field (admin|supervisor)
│   │   ├── Pharma.php          # Pharmacy; mapped to 'pharmacies' table
│   │   ├── Drug.php            # Drug with category and price
│   │   ├── Category.php        # Drug category
│   │   ├── Order.php           # Purchase order (pharmacy → admin)
│   │   ├── Invoice.php         # Sales invoice with line items
│   │   ├── InvoiceItem.php     # Single line item on an invoice
│   │   ├── Warehouse.php       # Stock record per pharmacy+drug
│   │   └── StockNotification.php # Low-stock alert (maps to 'notifications' table)
│   ├── Providers/
│   │   └── AppServiceProvider.php  # IoC bindings: interfaces → implementations
│   ├── Repositories/               # Repository contracts (interfaces)
│   │   ├── ICategoryRepository.php
│   │   ├── IDrugRepository.php
│   │   ├── IInvoiceRepository.php
│   │   ├── IInvoiceItemRepository.php
│   │   ├── INotificationRepository.php
│   │   ├── IOrderRepository.php
│   │   ├── IPharmaRepository.php
│   │   ├── IUserRepository.php
│   │   ├── IWarehouseRepository.php
│   │   └── Implementation/         # Eloquent implementations of each contract
│   │       ├── CategoryRepository.php
│   │       ├── DrugRepository.php
│   │       ├── InvoiceRepository.php
│   │       ├── InvoiceItemRepository.php
│   │       ├── NotificationRepository.php
│   │       ├── OrderRepository.php
│   │       ├── PharmaRepository.php
│   │       ├── UserRepository.php
│   │       └── WarehouseRepository.php
│   └── Services/
│       ├── Admin/              # Service contracts + implementations for admin
│       │   ├── ICategoryService.php
│       │   ├── IDrugService.php
│       │   ├── IInvoiceService.php
│       │   ├── INotificationService.php
│       │   ├── IOrderService.php
│       │   ├── IPharmaService.php
│       │   ├── IUserService.php
│       │   ├── IWarehouseService.php
│       │   └── Implementation/
│       │       ├── CategoryService.php
│       │       ├── DrugService.php
│       │       ├── InvoiceService.php  # Admin invoice CRUD (no stock check)
│       │       ├── NotificationService.php
│       │       ├── OrderService.php    # accept() runs in DB transaction
│       │       ├── PharmaService.php
│       │       ├── UserService.php
│       │       └── WarehouseService.php
│       └── Supervisor/         # Service contracts + implementations for supervisor
│           ├── IInvoiceService.php
│           ├── IWarehouseService.php
│           └── Implementation/
│               ├── InvoiceService.php  # Stock-aware invoice create/update/delete
│               └── WarehouseService.php # Orders and minimum threshold logic
├── database/
│   ├── migrations/             # 18 versioned schema migrations
│   └── seeders/
│       └── DatabaseSeeder.php  # Seeds default admin user
├── resources/
│   └── views/
│       ├── admin/              # Admin Blade views (dashboard, CRUD pages)
│       ├── supervisor/         # Supervisor Blade views
│       ├── layouts/            # Shared layouts
│       └── components/         # Reusable Blade components
├── routes/
│   ├── web.php                 # Web routes (admin + supervisor + profile)
│   ├── api.php                 # API v1 routes (Sanctum protected)
│   └── auth.php                # Breeze auth routes (login, register, etc.)
└── tests/
    └── Feature/                # Feature tests per entity
        ├── CategoryTest.php
        ├── DrugTest.php
        ├── InvoiceTest.php
        ├── OrderTest.php
        ├── PharmaciesTest.php
        ├── SupervisorInvoiceTest.php
        └── UserTest.php
```

---

## API Documentation

All API routes are prefixed with `/api/v1`. Protected routes require a Bearer token obtained from `/api/v1/login`.

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/v1/register` | Register a new user |
| `POST` | `/api/v1/login` | Obtain a Sanctum token |
| `POST` | `/api/v1/logout` | Revoke the current token |

> Auth endpoints are rate-limited to **10 requests per minute**.

**Login Request**
```json
POST /api/v1/login
{
  "email": "admin@example.com",
  "password": "12345678"
}
```

**Login Response**
```json
{
  "success": true,
  "message": "Logged in successfully",
  "data": {
    "token": "1|abc123..."
  }
}
```

---

### Admin-Only Endpoints

> Require `Authorization: Bearer <token>` with an admin account.

#### Drugs

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/drugs` | List all drugs (paginated) |
| `POST` | `/api/v1/drugs` | Create a drug |
| `GET` | `/api/v1/drugs/{id}` | Get a drug |
| `PUT/PATCH` | `/api/v1/drugs/{id}` | Update a drug |
| `DELETE` | `/api/v1/drugs/{id}` | Delete a drug |

#### Categories

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/categories` | List all categories |
| `POST` | `/api/v1/categories` | Create a category |
| `GET` | `/api/v1/categories/{id}` | Get a category |
| `PUT/PATCH` | `/api/v1/categories/{id}` | Update a category |
| `DELETE` | `/api/v1/categories/{id}` | Delete a category |

#### Pharmacies

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/pharmacies` | List all pharmacies |
| `POST` | `/api/v1/pharmacies` | Create a pharmacy |
| `GET` | `/api/v1/pharmacies/{id}` | Get a pharmacy with its stock |
| `PUT/PATCH` | `/api/v1/pharmacies/{id}` | Update a pharmacy |
| `DELETE` | `/api/v1/pharmacies/{id}` | Delete a pharmacy |
| `POST` | `/api/v1/pharmacies/store-drugs` | Bulk-add drugs to a pharmacy |

#### Orders

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/orders` | List all orders |
| `PATCH` | `/api/v1/orders/{id}/accept` | Accept an order (updates warehouse stock atomically) |

#### Users

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/users` | List all users |
| `POST` | `/api/v1/users` | Create a user |
| `GET` | `/api/v1/users/{id}` | Get a user |
| `PUT/PATCH` | `/api/v1/users/{id}` | Update a user |
| `DELETE` | `/api/v1/users/{id}` | Delete a user |

#### Admin Invoices

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/invoices` | List all invoices |
| `POST` | `/api/v1/invoices` | Create an invoice |
| `GET` | `/api/v1/invoices/{id}` | Get an invoice |
| `PUT/PATCH` | `/api/v1/invoices/{id}` | Update an invoice |
| `DELETE` | `/api/v1/invoices/{id}` | Delete an invoice |

---

### Supervisor-Only Endpoints

> Require `Authorization: Bearer <token>` with a supervisor account.

#### Supervisor Invoices

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/v1/supervisor-invoices` | List invoices scoped to this supervisor's pharmacy |
| `POST` | `/api/v1/supervisor-invoices` | Create invoice (deducts stock, triggers low-stock alert if needed) |
| `GET` | `/api/v1/supervisor-invoices/{id}` | Get a single invoice (ownership enforced) |
| `PUT/PATCH` | `/api/v1/supervisor-invoices/{id}` | Update invoice (stock restored then re-applied) |
| `DELETE` | `/api/v1/supervisor-invoices/{id}` | Delete invoice (stock restored) |

**Create Invoice Request**
```json
POST /api/v1/supervisor-invoices
{
  "pharmacy_id": 1,
  "date": "2026-05-04",
  "items": [
    { "drug_id": 3, "quantity": 10 },
    { "drug_id": 7, "quantity": 5 }
  ]
}
```

**Create Invoice Response** (`201 Created`)
```json
{
  "status": true,
  "data": {
    "id": 42,
    "pharmacy_id": 1,
    "date": "2026-05-04",
    "price": 1250.00,
    "pharmacy": { "id": 1, "name": "City Pharmacy" },
    "items": [
      { "drug_id": 3, "quantity": 10, "unit_price": 100.00, "price": 1000.00, "drug": { ... } },
      { "drug_id": 7, "quantity": 5,  "unit_price": 50.00,  "price": 250.00,  "drug": { ... } }
    ]
  }
}
```

---

## Database Design

### Entity-Relationship Summary

```
users ──────────────── pharmacies
  │   1          0..1      │
  │                        │ 1
  │                     ┌──┴──┬─────────────────────────────┐
  │                  orders  warehouses             invoices
  │                (drug_id) (drug_id, minimum)    (pharmacy_id)
  │                                                      │
  │                                               invoice_details
  │                                               (drug_id, qty,
  │                                                unit_price, price)
  │
drugs ── categories
  │  N         1
  └── notifications (low-stock alerts)
```

### Tables

| Table | Key Columns | Notes |
|-------|-------------|-------|
| `users` | `name`, `email`, `password`, `role` | `role ∈ {admin, supervisor}` |
| `pharmacies` | `name`, `user_id` | No timestamps by design |
| `categories` | `name` | No timestamps |
| `drugs` | `name`, `price`, `category_id` | Price cast to `float` |
| `orders` | `pharmacy_id`, `drug_id`, `quantity`, `accepted` | Pending until admin accepts |
| `warehouses` | `pharmacy_id`, `drug_id`, `quantity`, `minimum` | Unique composite index on `(pharmacy_id, drug_id)` |
| `invoices` | `pharmacy_id`, `price`, `date` | Total price computed from items |
| `invoice_details` | `invoice_id`, `drug_id`, `quantity`, `unit_price`, `price` | Line items |
| `notifications` | `pharmacy_id`, `drug_id`, `message` | Low-stock alerts |
| `personal_access_tokens` | — | Laravel Sanctum tokens |

---

## Architecture & Design Patterns

The codebase follows **SOLID principles** and a layered architecture, separating HTTP, business logic, data access, and data transfer concerns into distinct, independently testable layers.

---

### SOLID Principles Applied

| Principle | How It Is Applied |
|-----------|-------------------|
| **S** — Single Responsibility | Controllers only handle HTTP. Services only contain business logic. Repositories only contain data-access queries. DTOs only carry data. |
| **O** — Open/Closed | All services and repositories are consumed through interfaces, so new implementations can be swapped in (`AppServiceProvider`) without touching consumer code. |
| **L** — Liskov Substitution | Every concrete repository/service class fully satisfies its interface contract, so they are interchangeable. |
| **I** — Interface Segregation | Repository and service interfaces are kept narrow and entity-specific (e.g., `ICategoryRepository`, `IOrderService`), not monolithic. |
| **D** — Dependency Inversion | High-level classes (controllers, services) depend on abstractions (interfaces), not on concrete Eloquent repositories. Laravel's IoC container resolves the bindings at runtime. |

---

### Repository Pattern

All database access is hidden behind repository interfaces (`app/Repositories/I*Repository.php`). Concrete Eloquent implementations live under `app/Repositories/Implementation/`. Services receive a repository interface via constructor injection — they never reference an Eloquent model directly for queries.

```
Interface (contract)          →  Eloquent Implementation
─────────────────────────────────────────────────────────
ICategoryRepository           →  CategoryRepository
IDrugRepository               →  DrugRepository
IInvoiceRepository            →  InvoiceRepository
IInvoiceItemRepository        →  InvoiceItemRepository
IOrderRepository              →  OrderRepository
IPharmaRepository             →  PharmaRepository
IUserRepository               →  UserRepository
IWarehouseRepository          →  WarehouseRepository
INotificationRepository       →  NotificationRepository
```

**Benefit**: swapping the underlying storage (e.g., to an external API or a different ORM) only requires a new implementation class and a one-line change in `AppServiceProvider` — zero changes to service or controller code.

---

### Data Transfer Objects (DTOs)

All data flowing from an HTTP request into a service method is first mapped to a typed, immutable DTO (`app/DTOs/Save*DTO.php`). Each DTO exposes a static `fromRequest()` factory that accepts the validated `FormRequest` and returns a populated DTO.

```php
// Example: SaveCategoryDTO
class SaveCategoryDTO
{
    public function __construct(
        public readonly string $name,
    ) {}

    public static function fromRequest(
        CreateCategoryRequest|EditCategoryRequest $request
    ): self {
        return new self(name: $request->name);
    }
}
```

**Benefits**:
- Services are decoupled from the HTTP layer — they accept a DTO, not a raw request object.
- `readonly` properties make DTOs immutable, preventing accidental mutation.
- Clear, type-safe API boundary between controllers and the domain layer.

| DTO | Purpose |
|-----|---------|
| `SaveCategoryDTO` | Create/update a drug category |
| `SaveDrugDTO` | Create/update a drug |
| `SaveInvoiceDTO` | Create/update an invoice with its items |
| `SaveInvoiceItemDTO` | Single line item within an invoice |
| `SaveOrderDTO` | Place a purchase order |
| `SavePharmaDTO` | Create/update a pharmacy |
| `SaveUserDTO` | Create/update a user account |
| `SaveWarehouseDTO` | Set/update warehouse stock or minimum threshold |
| `SaveNotificationDTO` | Create a low-stock alert record |

---

### Service Layer & Interface Contracts

Business logic is fully extracted into dedicated service classes (`app/Services/`), each backed by an interface. Controllers receive the interface via constructor injection — the IoC container resolves the concrete class.

```
Admin services          Supervisor services
──────────────────      ───────────────────
ICategoryService        IInvoiceService
IDrugService            IWarehouseService
IInvoiceService
INotificationService
IOrderService
IPharmaService
IUserService
IWarehouseService
```

---

### IoC Container Bindings

`AppServiceProvider` is the single place where every interface is bound to its concrete implementation:

```php
$this->app->bind(ICategoryRepository::class, CategoryRepository::class);
$this->app->bind(ICategoryService::class,    CategoryService::class);
// … repeated for all 9 repositories and all service pairs
```

Changing an implementation is a one-line edit in `AppServiceProvider`; no other file needs to change.

---

### Dual Service Split (Admin vs. Supervisor)
Two parallel `InvoiceService` classes exist by design:
- **`Admin\InvoiceService`** — operates globally, no stock validation. Used by admin and the admin API.
- **`Supervisor\InvoiceService`** — stock-aware. Validates availability before decrementing, restores stock on update/delete, and fires `StockNotification` records when quantity falls below the threshold.

### Role-Based Access Control (RBAC)
Two custom middleware classes (`AdminMiddleware`, `SupervisorMiddleware`) guard both web and API route groups, returning JSON 403 for API clients and a redirect for browser clients.

### IDOR Prevention
All supervisor routes scope database queries to the authenticated user's pharmacy ID before performing mutations. An order or invoice cannot be modified by a supervisor from a different pharmacy, even if the ID is guessed.

### Atomic Transactions
All mutating service operations that touch more than one table are wrapped in `DB::transaction()`, preventing partial state in the event of an error (e.g., accepting an order both updates warehouse stock and marks the order accepted in a single atomic unit).

### Form Request Validation
All incoming data is validated via dedicated `FormRequest` classes organized per entity (`app/Http/Requests/`), keeping validation logic out of controllers and ensuring reuse across web and API layers.

### Unique Warehouse Constraint
A composite unique index on `warehouses(pharmacy_id, drug_id)` prevents duplicate stock rows under concurrent `firstOrCreate()` calls.

---

## Future Improvements

- **Email/SMS notifications** — send real alerts to supervisors when stock drops below the minimum threshold instead of only storing a DB record.
- **Role management UI** — allow admins to promote/demote users without direct DB access.
- **Reporting & analytics** — charts for sales trends, top-selling drugs, and low-stock frequency per pharmacy.
- **Pagination on API responses** — already implemented on most endpoints; ensure consistency across all list endpoints.
- **API resource classes** — replace raw model serialization with Laravel API Resources for stable, versioned response contracts.
- **Multi-currency / tax support** — add tax rates and support for multiple currencies on invoices.
- **Audit logging** — record who accepted an order or created/modified an invoice for compliance.
- **Docker / Sail setup** — containerize the stack for easier onboarding and environment parity.
- **Refresh token strategy** — extend the Sanctum API with token expiry and refresh flows for production use.
- **Two-factor authentication** — strengthen admin account security.
- **Unit tests for repositories & services** — now that the repository and service layers are interface-driven, pure unit tests (with mock repositories) can be added without hitting the database.

---

## Contributing

1. Fork the repository and create a feature branch (`git checkout -b feature/my-feature`).
2. Follow PSR-12 coding standards (enforced by Laravel Pint: `./vendor/bin/pint`).
3. Write feature tests for any new functionality.
4. Ensure the full test suite passes (`composer test`) before opening a pull request.

---

## License

This project is open-sourced under the [MIT License](https://opensource.org/licenses/MIT).
