# Movie API Backend - Laravel 5

Backend API untuk technical test movie app menggunakan Laravel 5 dan OMDb API.

## Features

- ✅ Authentication dengan API Token
- ✅ Search movies dari OMDb API
- ✅ Get movie details
- ✅ Localization (EN/ID)
- ✅ CORS support untuk React frontend
- ✅ Infinite scroll ready (pagination)
- ✅ Secure authentication dengan password hashing

## Tech Stack

- Laravel 5.4
- PHP >= 5.6.4
- MySQL
- Guzzle HTTP Client
- OMDb API

## Installation & Setup

### 1. Install Dependencies

```bash
cd laravel
composer install
```

### 2. Setup Environment

File `.env` sudah ada, pastikan konfigurasi database sudah benar:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

**IMPORTANT:** Update `OMDB_API_KEY` di `.env` dengan API key kamu:

```env
OMDB_API_KEY=your_omdb_api_key_here
OMDB_API_URL=http://www.omdbapi.com/
```

Dapatkan API key gratis di: https://www.omdbapi.com/apikey.aspx

### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Seed Database

```bash
php artisan db:seed
```

Ini akan membuat user dengan credentials:
- **Username:** aldmic
- **Password:** 123abc123

### 5. Run Server

```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

## API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Authentication

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "username": "aldmic",
  "password": "123abc123"
}

Response:
{
  "success": true,
  "message": "Login successful.",
  "data": {
    "user": {
      "id": 1,
      "name": "Aldmic",
      "email": "aldmic@example.com"
    },
    "token": "your_api_token_here"
  }
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}

Response:
{
  "success": true,
  "message": "Logout successful."
}
```

#### Get User
```http
GET /api/user
Authorization: Bearer {token}

Response:
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Aldmic",
    "email": "aldmic@example.com"
  }
}
```

### Movies (Protected - Requires Authentication)

#### Search Movies
```http
GET /api/movies/search?s={query}&page={page}
Authorization: Bearer {token}

Example:
GET /api/movies/search?s=batman&page=1

Response:
{
  "success": true,
  "message": "Movies retrieved successfully.",
  "data": [
    {
      "Title": "Batman Begins",
      "Year": "2005",
      "imdbID": "tt0372784",
      "Type": "movie",
      "Poster": "https://..."
    },
    ...
  ],
  "total_results": "450"
}
```

#### Get Movie Details
```http
GET /api/movies/{imdbId}
Authorization: Bearer {token}

Example:
GET /api/movies/tt0372784

Response:
{
  "success": true,
  "message": "Movie details retrieved successfully.",
  "data": {
    "Title": "Batman Begins",
    "Year": "2005",
    "Rated": "PG-13",
    "Released": "15 Jun 2005",
    "Runtime": "140 min",
    "Genre": "Action, Crime, Drama",
    "Director": "Christopher Nolan",
    "Writer": "Bob Kane, David S. Goyer, Christopher Nolan",
    "Actors": "Christian Bale, Michael Caine, Liam Neeson",
    "Plot": "After training with his mentor...",
    "Language": "English, Mandarin",
    "Country": "USA, UK",
    "Awards": "Nominated for 1 Oscar...",
    "Poster": "https://...",
    "Ratings": [...],
    "Metascore": "70",
    "imdbRating": "8.2",
    "imdbVotes": "1,392,345",
    "imdbID": "tt0372784",
    "Type": "movie",
    "DVD": "18 Oct 2005",
    "BoxOffice": "$206,852,432",
    "Production": "Warner Bros. Pictures",
    "Website": "http://www.batmanbegins.com/"
  }
}
```

## Localization

API mendukung 2 bahasa: English (EN) dan Indonesian (ID)

### Cara Menggunakan

Tambahkan parameter `lang` pada request atau gunakan header `Accept-Language`:

**Via Query Parameter:**
```http
GET /api/movies/search?s=batman&lang=id
```

**Via Header:**
```http
GET /api/movies/search?s=batman
Accept-Language: id
```

Default: `en`

## CORS Configuration

CORS sudah dikonfigurasi untuk semua origin (`*`). Untuk production, sebaiknya update [Cors middleware](app/Http/Middleware/Cors.php) untuk specify allowed origins.

## Security Features

1. **Password Hashing**: Password di-hash menggunakan bcrypt
2. **API Token Authentication**: Token di-hash menggunakan SHA256
3. **Middleware Protection**: Routes movie protected dengan auth middleware
4. **CSRF Protection**: Built-in Laravel CSRF protection
5. **Input Validation**: Semua input divalidasi

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── API/
│   │       ├── AuthController.php     # Authentication endpoints
│   │       └── MovieController.php    # Movie endpoints
│   ├── Middleware/
│   │   ├── Cors.php                   # CORS handling
│   │   └── SetLocale.php              # Localization handling
│   └── Kernel.php
├── Services/
│   └── MovieService.php               # OMDb API integration
└── User.php                           # User model

config/
├── auth.php                           # Authentication config
├── services.php                       # Third-party services config (OMDb)
└── app.php                            # App config

database/
├── migrations/
│   ├── 2014_10_12_000000_create_users_table.php
│   └── 2024_03_01_000000_add_api_token_to_users_table.php
└── seeds/
    ├── DatabaseSeeder.php
    └── UsersTableSeeder.php           # Seed user aldmic

resources/
└── lang/
    ├── en/                            # English translations
    │   ├── auth.php
    │   └── movie.php
    └── id/                            # Indonesian translations
        ├── auth.php
        └── movie.php

routes/
└── api.php                            # API routes definition
```

## Testing

Gunakan Postman, Insomnia, atau curl untuk testing API.

### Example dengan curl:

```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"username":"aldmic","password":"123abc123"}'

# Search movies (ganti {TOKEN} dengan token dari login)
curl -X GET "http://localhost:8000/api/movies/search?s=batman&page=1" \
  -H "Authorization: Bearer {TOKEN}"

# Get movie detail
curl -X GET "http://localhost:8000/api/movies/tt0372784" \
  -H "Authorization: Bearer {TOKEN}"

# Logout
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer {TOKEN}"
```

## Next Steps for React Frontend

Frontend React dapat consume API ini dengan:

1. **Authentication Flow:**
   - Login page → POST `/api/login` → simpan token di localStorage/context
   - Add token ke setiap request header: `Authorization: Bearer {token}`

2. **Movie List dengan Infinite Scroll:**
   - GET `/api/movies/search?s={query}&page={page}`
   - Increment page saat scroll ke bottom

3. **Movie Detail:**
   - GET `/api/movies/{imdbId}`

4. **Localization:**
   - Add `Accept-Language` header atau `?lang=id` parameter

## Troubleshooting

### Guzzle not found
```bash
composer install
```

### Migration Error
Pastikan database sudah dibuat dan credentials di `.env` benar:
```bash
php artisan migrate:fresh --seed
```

### CORS Error
Pastikan CORS middleware sudah terdaftar di `app/Http/Kernel.php`

### OMDb API Error
Pastikan `OMDB_API_KEY` di `.env` sudah benar dan valid

---

**Author**: Technical Test - Movie App
**Version**: 1.0
**Laravel Version**: 5.4
