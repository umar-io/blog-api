# Backend API Documentation Guide

## Base URL

```
https://your-api-domain.com/api
```

## Authentication

All protected routes require a Bearer token in the Authorization header.

```http
Authorization: Bearer your-access-token-here
```

---

## Authentication Endpoints

### Register User

**POST** `/auth/register`

**Request Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**

```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2024-01-01T12:00:00Z"
    },
    "token": "your-access-token-here",
    "message": "User registered successfully"
}
```

### Login User

**POST** `/auth/login`

**Request Body:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**

```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "token": "your-access-token-here",
    "message": "Login successful"
}
```

### Logout User

**POST** `/auth/logout`

**Headers:** `Authorization: Bearer your-token`

**Response (200):**

```json
{
    "message": "Logout successful"
}
```

---

## Posts Endpoints

### Get All Posts

**GET** `/posts`

**Query Parameters:**

-   `page` (optional): Page number for pagination
-   `per_page` (optional): Number of posts per page (default: 15)

**Response (200):**

```json
{
    "data": [
        {
            "id": 1,
            "title": "My First Post",
            "content": "This is the content of my first post...",
            "user_id": 1,
            "user": {
                "id": 1,
                "name": "John Doe"
            },
            "created_at": "2024-01-01T12:00:00Z",
            "updated_at": "2024-01-01T12:00:00Z"
        }
    ],
    "links": {
        "first": "http://api.com/posts?page=1",
        "last": "http://api.com/posts?page=10",
        "prev": null,
        "next": "http://api.com/posts?page=2"
    },
    "meta": {
        "current_page": 1,
        "last_page": 10,
        "per_page": 15,
        "total": 150
    }
}
```

### Get Single Post

**GET** `/posts/{id}`

**Response (200):**

```json
{
    "data": {
        "id": 1,
        "title": "My First Post",
        "content": "This is the content of my first post...",
        "user_id": 1,
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "created_at": "2024-01-01T12:00:00Z",
        "updated_at": "2024-01-01T12:00:00Z"
    }
}
```

### Create New Post

**POST** `/posts`

**Headers:** `Authorization: Bearer your-token`

**Request Body:**

```json
{
    "title": "My New Post",
    "content": "This is the content of my new post..."
}
```

**Response (201):**

```json
{
    "data": {
        "id": 2,
        "title": "My New Post",
        "content": "This is the content of my new post...",
        "user_id": 1,
        "created_at": "2024-01-01T13:00:00Z",
        "updated_at": "2024-01-01T13:00:00Z"
    },
    "message": "Post created successfully"
}
```

### Update Post

**PUT** `/posts/{id}`

**Headers:** `Authorization: Bearer your-token`

**Request Body (partial update allowed):**

```json
{
    "title": "Updated Post Title",
    "content": "Updated content..."
}
```

**Response (200):**

```json
{
    "data": {
        "id": 1,
        "title": "Updated Post Title",
        "content": "Updated content...",
        "user_id": 1,
        "created_at": "2024-01-01T12:00:00Z",
        "updated_at": "2024-01-01T14:00:00Z"
    },
    "message": "Successfully updated post"
}
```

### Delete Post

**DELETE** `/posts/{id}`

**Headers:** `Authorization: Bearer your-token`

**Response (200):**

```json
{
    "message": "Post deleted successfully"
}
```

---

## User Profile Endpoints

**PUT** `/user/profile`

**Headers:** `Authorization: Bearer your-token`

**Request Body:**

```json
{
    "name": "John Updated",
    "email": "john.updated@example.com"
}
```

**Response (200):**

```json
{
    "data": {
        "id": 1,
        "name": "John Updated",
        "email": "john.updated@example.com",
        "updated_at": "2024-01-01T15:00:00Z"
    },
    "message": "Profile updated successfully"
}
```

--

## Error Responses

### Validation Errors (422)

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "title": ["The title field is required."],
        "email": ["The email has already been taken."]
    }
}
```

### Unauthorized (401)

```json
{
    "message": "Unauthenticated."
}
```

### Forbidden (403)

```json
{
    "message": "Unauthorized. You do not own this post."
}
```

### Not Found (404)

```json
{
    "message": "Post not found."
}
```

### Server Error (500)

```json
{
    "error": "Something went wrong. Please try again later."
}
```

---

## Frontend Implementation Examples

### JavaScript/Axios Example

```javascript
// Set up axios with base configuration
const api = axios.create({
    baseURL: "https://your-api-domain.com/api",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

// Add token to requests if available
api.interceptors.request.use((config) => {
    const token = localStorage.getItem("auth_token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Login function
async function login(email, password) {
    try {
        const response = await api.post("/auth/login", { email, password });
        localStorage.setItem("auth_token", response.data.token);
        return response.data;
    } catch (error) {
        throw error.response.data;
    }
}

// Get posts function
async function getPosts(page = 1) {
    try {
        const response = await api.get(`/posts?page=${page}`);
        return response.data;
    } catch (error) {
        throw error.response.data;
    }
}

// Create post function
async function createPost(title, content) {
    try {
        const response = await api.post("/posts", { title, content });
        return response.data;
    } catch (error) {
        throw error.response.data;
    }
}
```

### React Hook Example

```javascript
// Custom hook for posts
import { useState, useEffect } from "react";

export const usePosts = () => {
    const [posts, setPosts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchPosts();
    }, []);

    const fetchPosts = async () => {
        try {
            setLoading(true);
            const data = await getPosts();
            setPosts(data.data);
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    return { posts, loading, error, refetch: fetchPosts };
};
```

---

## Rate Limiting

-   Authentication endpoints: 5 requests per minute
-   Post creation: 10 posts per hour per user
-   General API calls: 100 requests per minute

---

## Content-Type Headers

Always include these headers in your requests:

```http
Content-Type: application/json
Accept: application/json
```

---

## CORS Settings

The API supports cross-origin requests from:

-   `localhost:3000` (development)
-   `your-frontend-domain.com` (production)

---

## Testing the API

You can test the API endpoints using tools like:

-   Postman
-   Insomnia
-   curl commands
-   Your browser's developer tools

### Example curl command:

```bash
curl -X POST https://your-api-domain.com/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email": "john@example.com", "password": "password123"}'
```

---

## Support

For questions about this API, contact the development team or check the repository documentation.
