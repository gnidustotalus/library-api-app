# Library Management API

REST API do zarządzania biblioteką z książkami, autorami, wydawcami i kategoriami. 

## Wymagania

- **PHP** 8.1+
- **MySQL** 8.0+
- **Composer** 2.x
- **Docker** (opcjonalnie)

## Instalacja

```bash
# Instalacja zależności
composer install

# Konfiguracja środowiska
cp .env.example .env
php artisan key:generate

# Konfiguracja bazy danych w .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_db
DB_USERNAME=root
DB_PASSWORD=

# Migracje i seeders
php artisan migrate --seed
```

## Uruchomienie

```bash
# Serwer deweloperski
php artisan serve

# Lub z Docker
docker-compose up -d
```

API dostępne pod: `http://localhost:8000`

## Autentykacja

API używa **Laravel Sanctum** dla autoryzacji. POST/PUT/DELETE wymagają tokenu.

### Login
```bash
POST /api/auth/login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "password123"
}
```

### Użycie tokenu
```bash
Authorization: Bearer YOUR_TOKEN_HERE
```

### Testowy użytkownik
- **Email:** admin@example.com
- **Password:** password123

## Endpointy

### Authentication
- `POST /api/auth/login` - Logowanie
- `POST /api/auth/logout` - Wylogowanie (🔒)
- `GET /api/auth/user` - Info o użytkowniku (🔒)

### Authors
- `GET /api/authors` - Lista autorów
- `POST /api/authors` - Nowy autor (🔒)
- `GET /api/authors/{id}` - Szczegóły autora
- `PUT /api/authors/{id}` - Aktualizacja (🔒)
- `DELETE /api/authors/{id}` - Usunięcie (🔒)

### Publishers
- `GET /api/publishers` - Lista wydawców
- `POST /api/publishers` - Nowy wydawca (🔒)
- `GET /api/publishers/{id}` - Szczegóły
- `PUT /api/publishers/{id}` - Aktualizacja (🔒)
- `DELETE /api/publishers/{id}` - Usunięcie (🔒)

### Categories
- `GET /api/categories` - Lista kategorii
- `POST /api/categories` - Nowa kategoria (🔒)
- `GET /api/categories/{id|slug}` - Szczegóły
- `PUT /api/categories/{id}` - Aktualizacja (🔒)
- `DELETE /api/categories/{id}` - Usunięcie (🔒)

### Books
- `GET /api/books` - Lista książek (z filtrami)
- `POST /api/books` - Nowa książka (🔒)
- `GET /api/books/{id}` - Szczegóły książki
- `PUT /api/books/{id}` - Aktualizacja (🔒)
- `DELETE /api/books/{id}` - Usunięcie (🔒)
- `GET /api/books/lists/popular` - Popularne książki (cached)
- `POST /api/books/search` - Zaawansowane wyszukiwanie

**🔒 = Wymaga autoryzacji**

## Filtrowanie Books

```
GET /api/books?search=harry&available_only=true&language=en&category=fiction&sort_by=title&per_page=10
```

## Zaawansowane wyszukiwanie

```bash
POST /api/books/search
Content-Type: application/json

{
    "search": "Harry Potter",
    "available_only": true,
    "language": "en",
    "category": "fantasy",
    "author_id": 1,
    "price_min": 10,
    "price_max": 20,
    "sort_by": "relevance"
}
```

