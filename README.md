# The Fellowship of the Tee

A Laravel + Inertia.js + React application featuring Lord of the Rings themed book search functionality.

## Features

- **Home Page**: Displays the custom "ForeUp" Lord of the Rings poster with "The Fellowship of the Tee" branding
- **Search Page**: Fetches and displays books from The One API with real-time search filtering
- **Responsive Design**: Built with Tailwind CSS for a beautiful, mobile-friendly experience
- **Modern Stack**: Laravel 11, Inertia.js, React 18, Vite

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (or another database of your choice)

## Installation

1. **Clone or navigate to the project directory**
   ```bash
   cd fellowship-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Set up environment file**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Create database**
   ```bash
   touch database/database.sqlite
   ```

7. **Run migrations** (if you add any in the future)
   ```bash
   php artisan migrate
   ```

## Running the Application

1. **Start the Laravel development server**
   ```bash
   php artisan serve
   ```

2. **In a separate terminal, start Vite for asset compilation**
   ```bash
   npm run dev
   ```

3. **Visit the application**
   Open your browser and navigate to: `http://localhost:8000`

## Application Structure

### Routes
- `/` - Home page with the LOTR poster
- `/search` - Search page for browsing Middle-earth books
- `/api/books` - API endpoint that fetches books from The One API

### Key Files

#### Backend (Laravel)
- `routes/web.php` - Application routes
- `app/Http/Controllers/BookController.php` - Handles book data fetching
- `app/Http/Middleware/HandleInertiaRequests.php` - Inertia middleware
- `resources/views/app.blade.php` - Main Blade template

#### Frontend (React)
- `resources/js/app.jsx` - React application entry point
- `resources/js/Pages/Home.jsx` - Home page component
- `resources/js/Pages/Search.jsx` - Search page with filtering
- `resources/js/Components/Layout.jsx` - Shared layout with navigation

#### Configuration
- `vite.config.js` - Vite build configuration
- `tailwind.config.js` - Tailwind CSS configuration
- `package.json` - JavaScript dependencies
- `composer.json` - PHP dependencies

## Features Explained

### Home Page
- Displays the custom "ForeUp" poster
- Welcoming message with Middle-earth theme
- Navigation to the Search page

### Search Page
- Automatically loads books from The One API on page load
- Real-time search filtering as you type
- Displays books in a responsive grid layout
- Shows loading state while fetching data
- Displays error messages if API call fails
- Shows count of filtered results

### Navigation
- Top navigation bar with "Home" and "Search" menu items
- Persistent across all pages
- Amber/brown color scheme matching LOTR theme

## Customization

### Styling
The application uses Tailwind CSS with a custom color scheme featuring amber and brown tones to match the Lord of the Rings aesthetic. You can modify the colors in the component files or extend Tailwind's configuration.

### API Configuration
The application currently fetches from `https://the-one-api.dev/v2/book?limit=100`. To modify this:
1. Edit `app/Http/Controllers/BookController.php`
2. Update the API URL or add authentication headers if needed

### Adding More Pages
1. Create a new React component in `resources/js/Pages/`
2. Add a route in `routes/web.php`
3. Add navigation link in `resources/js/Components/Layout.jsx`

## Technologies Used

- **Laravel 11** - PHP framework
- **Inertia.js** - Server-side routing meets client-side rendering
- **React 18** - UI library
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Fast build tool
- **The One API** - Lord of the Rings data source

## Troubleshooting

### Port already in use
If port 8000 is busy, specify a different port:
```bash
php artisan serve --port=8001
```

### Assets not loading
Make sure Vite is running:
```bash
npm run dev
```

### API not working
Check that your server has internet access to reach `https://the-one-api.dev`

## License

This is a demonstration project. The Lord of the Rings imagery and names are property of their respective copyright holders.
