# PHP Tasks API

A simple RESTful API for managing tasks, built with PHP 8.3, SQLite, Docker, and Nginx.

## Features

- Full CRUD operations (Create, Read, Update, Delete)
- RESTful API endpoints
- SQLite database for data persistence
- Docker containerization with PHP-FPM and Nginx
- Simple routing system
- JSON API responses

## Prerequisites

- Docker and Docker Compose installed
- Composer (for local development)

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/mendelsh/php-tasks-api.git
cd php-tasks-api
```

### 2. Start the Docker containers

```bash
docker-compose up -d --build
```

This will build the PHP-FPM container with PHP 8.3 and SQLite extensions, start the Nginx web server, and create the necessary network for container communication.

### 3. Initialize the database (if needed)

The database will be created automatically on first use. If you need to initialize it manually:

```bash
docker-compose exec php php -r "
\$pdo = new PDO('sqlite:/var/www/html/data/tasks.db');
\$pdo->exec('CREATE TABLE IF NOT EXISTS tasks (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL)');
"
```

### 4. Access the API

The API is available at: `http://localhost:8080`

## API Endpoints

### List all tasks
```bash
GET /tasks
```

**Response:**
```json
[
  {"id": 1, "title": "First task"},
  {"id": 2, "title": "Another task"}
]
```

### Get a single task
```bash
GET /tasks/:id
```

**Response:**
```json
{"id": 1, "title": "My first task"}
```

### Create a new task
```bash
POST /tasks
Content-Type: application/json

{
  "title": "New task title"
}
```

**Response:** (201 Created)
```json
{"id": 3, "title": "New task title"}
```

### Update a task
```bash
PUT /tasks/:id
Content-Type: application/json

{
  "title": "Updated task title"
}
```

**Response:**
```json
{"id": 1, "title": "Updated task title"}
```

### Delete a task
```bash
DELETE /tasks/:id
```

**Response:** (204 No Content)

## Example Usage

### Using cURL

```bash
# List all tasks
curl http://localhost:8080/tasks

# Get a specific task
curl http://localhost:8080/tasks/1

# Create a new task
curl -X POST http://localhost:8080/tasks \
  -H "Content-Type: application/json" \
  -d '{"title":"Learn Docker"}'

# Update a task
curl -X PUT http://localhost:8080/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{"title":"Updated title"}'

# Delete a task
curl -X DELETE http://localhost:8080/tasks/1
```

## Docker Services

### PHP-FPM Service
- Container: `php-tasks-api-php`
- Port: 9000 (internal)
- Image: Custom PHP 8.3-FPM with SQLite extensions

### Nginx Service
- Container: `php-tasks-api-nginx`
- Port: 8080 (mapped to host)
- Image: nginx:alpine

## Development

### View logs
```bash
docker-compose logs -f
```

### Access PHP container
```bash
docker-compose exec php sh
```

### Access Nginx container
```bash
docker-compose exec nginx sh
```

### Stop containers
```bash
docker-compose down
```

### Rebuild containers
```bash
docker-compose up -d --build
```

## Database

The application uses SQLite for simplicity. The database file is stored in the `data/` directory and is persisted via Docker volumes.

**Database Schema:**
```sql
CREATE TABLE tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL
);
```

## Error Handling

The API returns appropriate HTTP status codes:
- `200 OK` - Successful GET/PUT/PATCH requests
- `201 Created` - Successful POST requests
- `204 No Content` - Successful DELETE requests
- `400 Bad Request` - Invalid input data
- `404 Not Found` - Resource not found

Error responses follow this format:
```json
{"error": "Error message"}
```

## License

This is a simple example project for learning purposes.
