# Laravel Finance Bot

A comprehensive Telegram bot for tracking personal finances, including income, expenses, and debts.

## Features

- **AI Intent Parsing**: Just type what you did (e.g., "Spent 50k for lunch") and the bot will categorize it.
- **Currency Conversion**: Automatic conversion from USD to UZS using CBU.uz rates.
- **Debt Tracking**: Keep track of who you borrowed from or lent to.
- **Interactive Stats**: View daily, weekly, and monthly summaries directly in Telegram.
- **Visual Dashboard**: A clean, modern web dashboard to view your financial status.

## Telegram Commands

- `/start` or `/add`: Start a new transaction entry.
- `/balance`: Check your current net balance (Total Income - Total Expenses).
- `/stats`: View a breakdown of your finances for Today, This Week, or This Month using interactive buttons.

## Installation

1. Clone the repository.
2. Install dependencies: `composer install && npm install`.
3. Set up your `.env` file with Telegram and Database credentials.
4. Run migrations: `php artisan migrate`.
5. Start the bot: `php artisan nutgram:run`.
6. (Optional) Run Sail: `./vendor/bin/sail up`.

## Tech Stack

- **Laravel 11**
- **Nutgram** (Telegram Bot Framework)
- **Tailwind CSS & Alpine.js** (Dashboard UI)
- **PostgreSQL**
- **CBU.uz API** (Currency Rates)
