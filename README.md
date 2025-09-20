# Blog API

A Laravel-based REST API for a blog application with user authentication and post management.

## Features

-   User authentication (register, login, logout)
-   CRUD operations for blog posts
-   API token authentication (Laravel Sanctum)
-   Pagination support

## Requirements

-   PHP >= 8.1
-   Composer
-   MySQL/PostgreSQL
-   Laravel >= 10.x

## Quick Setup

1. **Clone the repository**

    ```bash
    git clone https://github.com/umar-io/blog-api.git
    cd blog-api
    ```

2. **Install dependencies**

    ```bash
    composer install
    ```

3. **Setup environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configure database**

    Update your `.env` file:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=blog_api
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

5. **Run migrations**

    ```bash
    php artisan migrate
    ```

6. **Start the server**

    ```bash
    php artisan serve
    ```

    API available at: `http://localhost:8000/api`

## API Endpoints

### Authentication

-   `POST /api/auth/register` - Register new user
-   `POST /api/auth/login` - Login user
-   `POST /api/auth/logout` - Logout user (requires token)

### Posts

-   `GET /api/posts` - Get all posts (paginated)
-   `GET /api/posts/{id}` - Get single post
-   `POST /api/posts` - Create new post (requires auth)
-   `PUT /api/posts/{id}` - Update post (requires auth + ownership)
-   `DELETE /api/posts/{id}` - Delete post (requires auth + ownership)

## Authentication

Include the Bearer token in your requests:

```
Authorization: Bearer your-access-token-here
```

## Testing

Create test database and run:

```bash
php artisan test
```

## License

Open source software license.
