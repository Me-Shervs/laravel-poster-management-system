## Stack: Laravel 12.59.0 · MySQL

## Installation
```bash
    composer install
    cp .env.example .env
    php artisan key:generate
```

## Run Migrations and Seed Database
```bash
    php artisan migrate:fresh --seed
```

## Verify Seeded Data
```bash
    php artisan tinker

    User::count();
    Poster::count();
    Category::count();
```

## Task Scheduling Commands
```bash
    posters:cleanup-drafts  
    posters:dispatch-scheduled  
    posters:expire  
    posters:report
```  

## Run Tests
```bash
    php artisan test
```