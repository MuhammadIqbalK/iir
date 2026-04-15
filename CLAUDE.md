# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

IIR (Incoming Inspection Reports) is a full-stack web application for quality control inspection of incoming parts. Users can create inspection reports, manage suppliers, examiners, and non-conforming items, and export reports to Excel.

**Stack**: Laravel 11 (PHP) backend + Vue 3 + Vuetify frontend with PostgreSQL database.

## Common Commands

### Backend (Laravel/PHP)
```bash
sa composer install              # Install PHP dependencies
sa php artisan migrate           # Run database migrations
sa php artisan serve             # Start Laravel dev server (port 8000)
sa vendor/bin/phpunit            # Run all tests
sa vendor/bin/phpunit --filter test_name  # Run specific test
sa php artisan tinker            # Interactive REPL
```

### Frontend (Vue/Vite)
```bash
sa npm install                   # Install JS dependencies
sa npm run dev                   # Start Vite dev server with HMR
sa npm run build                 # Build for production
sa npm run lint                  # Run ESLint with auto-fix
```

### Running the Full Stack
The Laravel Vite plugin integrates both servers. Run `php artisan serve` and `npm run dev` simultaneously.

## Architecture

### Backend Structure
- `routes/api.php` - All API routes (protected by `auth:sanctum` middleware)
- `app/Models/` - Domain models (use raw SQL via `DB::select()` for complex queries with joins)
- `app/Http/Controllers/Api/` - API resource controllers
- `app/Exports/` - Excel export classes using maatwebsite/excel

**Key Pattern**: Models like `IncomingPartReport` contain static methods like `getAll()` and `getById()` that return paginated results with joins to related tables (suppliers, examiners, items). Raw SQL uses parameterized queries with `:parameter` syntax.

### Frontend Structure
- `resources/js/pages/` - **File-based routing**: Files here automatically become routes (unplugin-vue-router)
- `resources/js/layouts/` - Layout components (vertical/horizontal nav)
- `resources/js/@core/` - Core UI components, composables, utilities
- `resources/js/components/` - Shared components
- `resources/js/plugins/` - Plugin configurations (i18n, CASL permissions)

**Key Pattern**: Components, Vue APIs, and composables are auto-imported. No need to manually import `ref`, `computed`, Vue components, etc.

### Database Schema
- `users` - Authentication (Laravel Sanctum tokens)
- `incoming_part_reports` - Main inspection records (nullable: samplesize, start, end, duration)
- `suppliers` - Supplier master data
- `examiners` - QC examiner master data
- `itemncs` - Non-conforming item master data with categories

## API Endpoints

All protected routes require `Authorization: Bearer {token}` header.

- `POST /api/login` - Authenticate
- `GET /api/incoming-part-reports` - List with pagination/filtering
- `GET /api/ipr-excel-global` - Export detailed Excel
- `GET /api/ipr-excel-recap` - Export summary Excel
- `GET /api/suppliers-dropdown` - Supplier list for dropdowns
- `GET /api/examiners-dropdown` - Examiner list for dropdowns
- `GET /api/itemncs-dropdown` - Item list for dropdowns

## Development Notes

- When modifying routes, clear route cache: `sa php artisan route:clear`
- When modifying models/configs, clear config cache: `sa php artisan config:clear`
- Frontend hot module reload works automatically via Vite
- Excel exports use Laravel's `Excel::download()` with filter parameters passed to Export classes
