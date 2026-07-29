# Reservation Platform

A reservation management platform built with **Laravel 13** and **PHP 8.3**.

The goal of this project is to demonstrate backend development practices using Laravel, focusing on business rules, database consistency, clean code, testing, API design and performance optimization.

The application allows users to browse events, reserve seats, and manage their reservations.

---

# Overview

Reservation Platform manages events with limited capacity and allows authenticated users to reserve available seats.

The main domain concepts are:

- Users
- Events
- Reservations

The project was designed to demonstrate real-world backend concerns:

- Authentication
- Business rules
- Database relationships
- Transactions
- Query optimization
- Automated testing
- REST API evolution

---

# Features

## Implemented

### Authentication

Implemented using Laravel Breeze.

Features:

- User registration
- Login
- Logout
- Profile management

### Event Management

Users can:

- Browse available events
- View event details
- See available seats
- Check reservation status

### Reservation Flow

Users can:

- Reserve an event
- View their reservations
- Prevent duplicate reservations

Business rules:

- A user cannot reserve the same event twice.
- Users cannot reserve events without available seats.
- Seat availability is updated together with reservation creation.

---

# Tech Stack

## Backend

- PHP 8.3
- Laravel 13
- Eloquent ORM

## Database

- PostgreSQL

## Frontend

- Blade Templates
- Laravel Breeze
- Tailwind CSS

## Development

- Docker
- Composer
- Vite

---

# Architecture

The application follows Laravel conventions with separation between controllers, models and business logic.

Current structure:

```
app/

├── Http/
│   └── Controllers/
│
├── Models/
│
├── Services/
│   └── ReservationService.php
│
└── Exceptions/
    ├── NoSeatsAvailableException.php
    └── AlreadyReservedException.php
```

---

# Domain Flow

Reservation creation:

```
User

 |

 v

ReservationController

 |

 v

ReservationService

 |

 +----------------+
 | Business Rules |
 +----------------+

 |

 v

Database Transaction

 |

 v

Reservation + Event Seats Update
```

---

# Database Model

Main relationships:

```
User

 1
 |
 |
 N

Reservation

 N
 |
 |
 1

Event
```

## User

A user can have many reservations.

```php
public function reservations()
{
    return $this->hasMany(Reservation::class);
}
```

## Event

An event contains:

- Name
- Description
- Date
- Total seats
- Available seats

## Reservation

A reservation belongs to:

- User
- Event

---

# Business Rules

## Duplicate Reservation Prevention

A user cannot reserve the same event more than once.

Example:

```
User
 |
 +-- Reservation
       |
       Event

Attempting another reservation
for the same event is blocked.
```

## Seat Availability

Reservations validate available capacity before creating a reservation.

The operation updates:

```
Reservation creation

+

Available seats decrement
```

inside a database transaction.

---

# Database Transactions

Reservation creation uses transactions to guarantee consistency.

Example scenario:

```
Create reservation

+

Decrease available seats
```

Both operations must succeed together.

If one fails, the entire operation is rolled back.

---

# Query Optimization

During development, Laravel Debugbar was used to analyze database queries.

An N+1 query problem was identified in the event listing.

Before:

```
Load events

+

Check reservation status individually
for each event
```

After:

```
Load events

+

Load reservation status
using withExists()
```

This reduces unnecessary database queries and keeps the view layer focused on presentation.

---

# Pagination

Event listing uses Laravel pagination:

```php
Event::paginate(10);
```

Benefits:

- Avoid loading all records
- Better performance
- Ready for large datasets

---

# Testing

Planned implementation using **Pest**.

The main scenarios to cover:

## Reservation Tests

- User can reserve an event
- Available seats are decremented
- User cannot reserve twice
- Sold out events cannot be reserved

## Feature Tests

- Authentication flow
- Event listing
- Reservation endpoints
- Authorization rules

---

# REST API Roadmap

The next evolution of the project is exposing the same domain through a REST API.

Planned stack:

- Laravel Sanctum
- API Resources
- Feature tests

Planned endpoints:

```
POST /api/login

GET /api/events

POST /api/events/{event}/reservations

GET /api/my-reservations
```

The goal is to support:

- Web applications
- Mobile clients
- External integrations

using the same business rules.

---

# Cache Strategy Roadmap

Redis will be introduced for read optimization.

Possible use cases:

```
Events listing

        |

        v

Redis Cache

        |

        v

PostgreSQL
```

Topics to explore:

- Cache Aside pattern
- TTL
- Cache invalidation
- Performance comparison

---

# Local Development

## Requirements

- PHP 8.3+
- Composer
- Docker
- PostgreSQL

# Docker Environment

The project uses Docker Compose to provide the database environment.

Currently, PostgreSQL runs as a Docker container while the Laravel application runs locally using Artisan.

Architecture:

```text
Laravel Application

        |
        |
        ↓

PostgreSQL 17
(Docker Container)
```

## Installation

Clone repository:

```bash
git clone <repository-url>
```

Install dependencies:

```bash
composer install
```

Configure environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

## Start database

Start PostgreSQL container:

```bash
docker compose up -d
```

## Run migrations

```bash
php artisan migrate
```

## Start development server:

```bash
php artisan serve
```

# Application Flow

The web application provides the following routes:

| URL                       | Description              | Authentication |
| ------------------------- | ------------------------ | -------------- |
| `/events`                 | List available events    | Public         |
| `/events/{event}/reserve` | Reserve an event         | Required       |
| `/my-reservations`        | View user's reservations | Required       |
| `/profile`                | Manage user profile      | Required       |

## User Flow

1. Access available events:
   GET /events

2. Authenticate with your account.

3. Reserve an available event:
   POST /events/{event}/reserve

4. View your reservations:
   GET /my-reservations

## Authentication

The application uses Laravel Breeze authentication.

Available authentication features:

- Register
- Login
- Logout
- Password management
- Profile management

---

# Future Improvements

- [ ] Pest automated tests
- [ ] Laravel Sanctum authentication API
- [ ] API Resources
- [ ] Redis caching
- [ ] Docker Compose production setup
- [ ] API documentation
- [ ] Queue based notifications

---

# Author

Backend developer focused on PHP/Laravel and Go.

This project was created as a portfolio application to demonstrate backend engineering practices with Laravel.

```

```

```

```

```

```
