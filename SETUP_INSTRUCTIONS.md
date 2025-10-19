# Super Todo List - Laravel API Setup Instructions

## Prerequisites
1. PHP 8.2 or higher
2. Composer
3. MySQL/PostgreSQL
4. Node.js and NPM (for frontend assets)

## Installation Steps

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Configuration
Create a `.env` file from `.env.example` and configure the following:

```env
APP_NAME="Super Todo List"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_list_laravel
DB_USERNAME=root
DB_PASSWORD=your_password

# JWT Configuration
JWT_SECRET=your-jwt-secret-key-here
JWT_TTL=60
JWT_REFRESH_TTL=20160
JWT_ALGO=HS256

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Generate JWT Secret
```bash
php artisan jwt:secret
```

### 5. Run Database Migrations
```bash
php artisan migrate
```

### 6. Seed Database
```bash
php artisan db:seed
```

### 7. Create Storage Link
```bash
php artisan storage:link
```

### 8. Start Development Server
```bash
php artisan serve
```

## API Endpoints

### Base URL
`http://localhost:8000/api/v1`

### Authentication
- `POST /auth/login` - User login
- `POST /auth/register` - User registration
- `POST /auth/logout` - User logout (requires token)
- `POST /auth/refresh` - Refresh token (requires token)
- `GET /auth/me` - Get user profile (requires token)

### Personal Tasks
- `GET /task` - Get user's personal tasks (requires auth)
- `POST /task` - Create new personal task (requires auth)
- `GET /task/{id}` - Get specific personal task (requires auth)
- `PUT /task/{id}` - Update personal task (requires auth)
- `DELETE /task/{id}` - Delete personal task (requires auth)
- `GET /task/count/day/total?userId={id}&date={date}` - Get total tasks count for a day (public)
- `GET /task/count/day/completed?userId={id}&date={date}` - Get completed tasks count for a day (public)

### Team Tasks
- `GET /team/task?teamId={id}` - Get team tasks
- `POST /team/task` - Create new team task
- `GET /team/task/{id}` - Get specific team task
- `PUT /team/task/{id}` - Update team task
- `DELETE /team/task/{id}` - Delete team task

### Categories
- `GET /category` - Get all categories

### User Management
- `GET /user/profile` - Get user profile
- `PUT /user/profile` - Update user profile
- `POST /user/avatar` - Upload user avatar

### File Upload
- `POST /file/upload` - Upload file
- `GET /file/upload` - Get user's uploaded files
- `DELETE /file/upload/{id}` - Delete file

## Testing the API

### Test User Credentials
- Email: `test@example.com`
- Password: `password`

### Example Login Request
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password"
  }'
```

### Example Task Count Request
```bash
curl -X GET "http://localhost:8000/api/v1/task/count/day/total?userId=1&date=2024-12-31"
```

## Scheduled Tasks

To enable automatic task notifications, add this to your crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## File Storage

Uploaded files are stored in `storage/app/public/uploads/` and can be accessed via the `/storage/` URL.

## Error Handling

The API returns consistent error responses:
```json
{
  "status": 400,
  "message": "Validation error",
  "data": null,
  "errors": {
    "field": ["Error message"]
  }
}
```

## Security Features

1. JWT Authentication
2. Request validation
3. Authorization checks
4. File upload restrictions
5. SQL injection protection
6. XSS protection

## Production Deployment

1. Set `APP_ENV=production`
2. Set `APP_DEBUG=false`
3. Configure proper database credentials
4. Set up SSL certificates
5. Configure mail settings
6. Set up queue workers for email notifications
7. Configure file storage (AWS S3 recommended)
