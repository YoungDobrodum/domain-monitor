# 🌐 Domain Monitor

A lightweight **SaaS platform for monitoring domain and website availability**.

The system automatically checks website status on a schedule, records detailed logs (response time, HTTP codes, errors), and sends **instant notifications via Telegram** when a domain becomes unavailable.

The project is built using a **modern API-first architecture** with a decoupled frontend and backend.

---

# ✨ Features

### 📊 Dashboard

* List of all monitored domains
* Current availability status
* Latest response time
* Quick access to domain history

### ⚙️ Monitoring Settings

Each domain can be configured individually:

* Check interval
* Request timeout
* HTTP method (**GET / HEAD**)

### 📜 History & Logs

Detailed history for every check:

* HTTP response code
* Response time
* Error messages
* Timestamp of the check

### 🔔 Notifications

Integration with **Telegram Bot API**:

* Instant alerts when a domain becomes unavailable
* Notifications when service recovers

### 👥 Multi-user System

* User registration
* Authentication with API tokens
* Personal domain lists for each user

---

# 🛠 Tech Stack

## Backend

* Laravel 12 (API mode)
* PHP 8.3
* Queue system using Redis
* Scheduled tasks with Laravel Scheduler

## Frontend

* Vue.js 3
* TypeScript
* Vite
* Composition API

## Database

* MySQL 8

## Infrastructure

* Docker
* Nginx
* Laravel Queue Worker

---

# 📦 Project Structure

```
domain-monitor

backend/
   Laravel API
   Domain monitoring logic
   Queue jobs
   Scheduler

frontend/
   Vue 3 SPA
   API communication
   Dashboard UI

docker/
   nginx
   php
   mysql

.gitignore
Dockerfile.frontend
Dockerfile.prod
README.md
docker-compose.yml
```

---

# 🚀 Getting Started

## 1️⃣ Clone Repository

```bash
git clone <your-repository-url>
cd domain-monitor
```

---

## 2️⃣ Create Environment File

```bash
cp backend/.env.example backend/.env
```

---

## 3️⃣ Start Docker Environment

Build and start all services:

```bash
docker-compose up -d --build
```

This will start the following containers automatically:

* PHP (Laravel application)
* Nginx web server
* MySQL database
* Redis (queue system)
* Frontend container (Vue app)
* Queue Worker

---

## 4️⃣ Initialize Backend

Install dependencies and generate application key:

```bash
docker-compose exec php composer install
docker-compose exec php php artisan key:generate
```

Run database migrations:

```bash
docker-compose exec php php artisan migrate
```

---

## 5️⃣ Initialize Frontend

Install frontend dependencies:

```bash
docker-compose exec frontend npm install
```

---

# 🔗 Local Development URLs

After the project is started locally:

| Service                  | URL                   |
| ------------------------ | --------------------- |
| Frontend Application     | http://localhost:5173 |
| Backend API              | http://localhost:8080 |
| Database Admin (Adminer) | http://localhost:8081 |

---
# 🖥️ Live Demo URL

Production Deployment

| Service        | URL                   |
|----------------| --------------------- |
| Domain Monitor | https://domain-monitor-frontend-production.up.railway.app |

---

# ⚙️ Monitoring System

The monitoring system uses Laravel background processes.

## Scheduler

Responsible for triggering domain checks on schedule.

Manual run:

```bash
docker-compose exec php php artisan schedule:run
```

---

## Queue Worker

Handles HTTP requests to monitored domains and saves results to the database.

The queue system is powered by **Redis**.

---

# 🤖 Telegram Notifications

To enable Telegram alerts, configure the following variables in:

```
backend/.env
```

```
TELEGRAM_BOT_TOKEN=your_bot_token
TELEGRAM_CHAT_ID=your_chat_id
```

When a monitored domain becomes unavailable, the system will automatically send a notification.

---

# 🧰 Useful Commands

Stop all containers

```bash
docker-compose stop
```

Restart containers

```bash
docker-compose restart
```

View logs

```bash
docker-compose logs -f
```

Run scheduler manually

```bash
docker-compose exec php php artisan schedule:run
```

---

# 📌 Future Improvements

Possible future features:

* Email notifications
* Uptime statistics charts
* Domain grouping
* SSL certificate monitoring
* Public monitoring API
* WebSockets

---

# 📄 License

This project is open-source and available under the MIT License.
