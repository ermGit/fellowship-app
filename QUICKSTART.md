# Quick Start Guide - The Fellowship of the Tee

## Option 1: View the Demo (No Installation Required)

Open `demo.html` in your browser to see a working demonstration of the application. This standalone HTML file shows exactly how the application works without needing to install Laravel, PHP, or any dependencies.

## Option 2: Full Laravel Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- npm

### Setup Steps

1. **Navigate to the project folder**
   ```bash
   cd fellowship-app
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Create database**
   ```bash
   mkdir -p database
   touch database/database.sqlite
   ```

5. **Start the application**
   
   Terminal 1 - Laravel server:
   ```bash
   php artisan serve
   ```
   
   Terminal 2 - Vite dev server:
   ```bash
   npm run dev
   ```

6. **Open your browser**
   Navigate to: http://localhost:8000

## Project Structure

```
fellowship-app/
├── app/
│   └── Http/
│       ├── Controllers/
│       │   └── BookController.php          # API controller
│       └── Middleware/
│           └── HandleInertiaRequests.php   # Inertia middleware
├── public/
│   └── images/
│       └── poster.jpg                      # LOTR poster
├── resources/
│   ├── css/
│   │   └── app.css                         # Tailwind CSS
│   ├── js/
│   │   ├── Components/
│   │   │   └── Layout.jsx                  # Navigation layout
│   │   ├── Pages/
│   │   │   ├── Home.jsx                    # Home page
│   │   │   └── Search.jsx                  # Search page
│   │   ├── app.jsx                         # React entry point
│   │   └── bootstrap.js                    # Axios setup
│   └── views/
│       └── app.blade.php                   # Main template
├── routes/
│   └── web.php                             # Application routes
├── demo.html                               # Standalone demo
├── package.json                            # NPM dependencies
├── composer.json                           # PHP dependencies
├── vite.config.js                          # Vite configuration
└── tailwind.config.js                      # Tailwind configuration
```

## Features

✅ **Home Page**
- Custom "ForeUp" Lord of the Rings poster
- Welcome message with Middle-earth theme
- Navigation to Search page

✅ **Search Page**
- Fetches books from The One API (https://the-one-api.dev/v2/book)
- Real-time search filtering
- Responsive grid layout
- Loading states and error handling

✅ **Navigation**
- Persistent top menu bar
- Home and Search menu items
- LOTR-themed color scheme (amber/brown)

## Tech Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: React 18, Inertia.js
- **Styling**: Tailwind CSS
- **Build Tool**: Vite
- **API**: The One API for Lord of the Rings data

## Troubleshooting

**Port in use?**
```bash
php artisan serve --port=8001
```

**Assets not loading?**
Make sure both servers are running:
- `php artisan serve` (Terminal 1)
- `npm run dev` (Terminal 2)

**API not working?**
Check internet connection - the app fetches from https://the-one-api.dev

## Development Commands

```bash
# Install dependencies
composer install
npm install

# Development
php artisan serve          # Start Laravel server
npm run dev               # Start Vite dev server

# Production build
npm run build             # Build assets for production

# Code quality
php artisan route:list    # View all routes
php artisan config:clear  # Clear config cache
```

## Next Steps

1. Customize the styling in Tailwind configuration
2. Add authentication (Laravel Breeze/Jetstream)
3. Create additional pages for book details
4. Add API authentication for The One API
5. Implement favorites/bookmarks functionality

Enjoy your journey through Middle-earth! 🗡️📚✨
