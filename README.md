# Finance Manager Bot

A Dockerized Laravel Finance Manager Bot using Laravel Sail and Nutgram.

## Features
- **Telegram Integration**: Add expenses directly from Telegram.
- **Conversational UI**: Easy-to-use conversation flow for adding records.
- **Dashboard**: View your expenses in a clean, light blue and green themed web interface.
- **Dockerized**: 1-click launch using Laravel Sail.

## Quick Start (1-Click Launch)

1. **Clone and Enter**:
   ```bash
   cd finance-bot
   ```

2. **Setup Environment**:
   ```bash
   cp .env.example .env
   ```
   *Edit `.env` and set your `TELEGRAM_TOKEN`.*

3. **Launch Containers**:
   ```bash
   ./vendor/bin/sail up -d
   ```

4. **Run Migrations**:
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

5. **Start the Bot**:
   ```bash
   ./vendor/bin/sail artisan nutgram:run
   ```

## Accessing the Dashboard
Open [http://localhost:8080](http://localhost:8080) in your browser to view your transactions.

## Services
- **PHP 8.5** (Laravel Sail)
- **PostgreSQL 18** (Port 5433)
- **Redis** (Port 6380)
- **Nutgram** (Telegram Bot Framework)
