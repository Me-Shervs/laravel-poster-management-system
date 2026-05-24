# Stack: Laravel 12.59.0 · MySQL

# Installation
```bash
    composer install
    cp .env.example .env
    php artisan key:generate
```

# Run Migrations and Seed Database
```bash
    php artisan migrate:fresh --seed
```

# Verify Seeded Data
```bash
    php artisan tinker

    User::count();
    Poster::count();
    Category::count();

    exit;
```

# Task Scheduling Commands
## Check All Commands
```bash
    php artisan list
```
## Weekly Poster Command
    After Seeded
```bash
    php artisan posters:report
```

## 90 Day Cleanup Drafts Command
```bash
    php artisan posters:cleanup-drafts
```
## Dispatch Scheduled Posters Command
* Run 2 Terminals
* Terminal 1
```bash
    php artisan queue:work
```
* Terminal 2
```bash
    php artisan posters:dispatch-scheduled
```
# Expired Posters Command
```bash
    php artisan tinker
    # this will create expired post
    Poster::factory()->published()->create(); 
    exit;
```
```bash
    php artisan posters:expire
```

## Run Tests
```bash
    php artisan test
```

## API TEST (POSTMAN)
```bash
    # In your terminal
    php artisan tinker

    # Copy & Paste this
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;

    User::create([
        'name' => 'Admin User',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
```
1. Go to postman
```http
    POST /api/v1/login
```
```JSON
    {
    "email": "admin@test.com",
    "password": "password"
    }
```
2. Copy the response token
```JSON
    {
    "token": "1|xxxxxxxxxxxxxxxx",
    }
```
3. To try and post
```http
    POST http://127.0.0.1:8000/api/v1/posters
```
4. Add header
```header
    Authorization: Bearer YOUR_TOKEN_HERE
    Accept: application/json
    Content-Type: application/json
```
5. Add Body
```Body
    {
    "title": "My First Poster",
    "content": {
        "text": "Hello Laravel API"
    }
    }
```
