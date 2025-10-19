# Super Todo List - API Implementation Guide

## Project Overview
A comprehensive task management system with personal and team task tracking, user authentication, and file upload capabilities.

## Technology Stack
- **Backend**: Java 17+
- **Framework**: Spring Boot 3.x
- **Database**: PostgreSQL/MySQL
- **Build Tool**: Maven/Gradle
- **Authentication**: JWT (JSON Web Tokens)
- **Documentation**: OpenAPI 3.0 (Swagger)

## Project Structure
```
src/main/java/mobile/doan/supertodolist/
├── config/              # Configuration classes
├── controller/          # REST API Controllers
├── dto/                 # Data Transfer Objects
│   ├── request/         # Request DTOs
│   └── response/        # Response DTOs
├── entity/              # JPA Entities
├── repository/          # Data Access Layer
├── security/            # Security configurations
└── service/             # Business logic
```

## Prerequisites
1. Java 17 or higher
2. Maven 3.8+ or Gradle 7.5+
3. PostgreSQL 13+ or MySQL 8.0+
4. Git

## Setup Instructions
1. Clone the repository
2. Configure database in `application.properties`
3. Run `mvn spring-boot:run` or `./gradlew bootRun`

## API Documentation

## Table of Contents
1. [Authentication](#authentication)
2. [Personal Tasks](#personal-tasks)
3. [Team Tasks](#team-tasks)
4. [Categories](#categories)
5. [User Management](#user-management)
6. [File Upload](#file-upload)
7. [Data Transfer Objects (DTOs)](#data-transfer-objects-dtos)

## API Endpoints

### Base URL
`http://your-domain.com/api/v1`

## Authentication

### Login
- **URL**: `/auth/login`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "email": "user@example.com",
    "password": "yourpassword"
  }
  ```
- **Response**:
  ```json
  {
    "status": 200,
    "message": "Login successful",
    "data": {
      "token": "jwt-token",
      "user": {
        "id": 1,
        "email": "user@example.com",
        "fullName": "User Name"
      }
    }
  }
  ```

### Register
- **URL**: `/auth/register`
- **Method**: `POST`
- **Request Body**:
  ```json
  {
    "email": "newuser@example.com",
    "password": "newpassword",
    "fullName": "New User"
  }
  ```
- **Response**:
  ```json
  {
    "status": 200,
    "message": "Register successful",
    "data": {
      "id": 2,
      "email": "newuser@example.com",
      "fullName": "New User"
    }
  }
  ```

## Personal Tasks

### Get Tasks Count for a Day
- **URL**: `/task/count/day/total`
- **Method**: `GET`
- **Query Parameters**:
  - `userId`: User ID (Long)
  - `date`: Date in format yyyy-MM-dd
- **Response**:
  ```json
  {
    "status": 200,
    "message": "Total tasks in day",
    "data": 5
  }
  ```

### Get Completed Tasks Count for a Day
- **URL**: `/task/count/day/completed`
- **Method**: `GET`
- **Query Parameters**:
  - `userId`: User ID (Long)
  - `date`: Date in format yyyy-MM-dd
- **Response**:
  ```json
  {
    "status": 200,
    "message": "Completed tasks in day",
    "data": 3
  }
  ```

## Team Tasks

### Get Team Tasks
- **URL**: `/team/task`
- **Method**: `GET`
- **Headers**:
  - `Authorization: Bearer <token>`
- **Query Parameters**:
  - `teamId`: Team ID (Long)
- **Response**:
  ```json
  {
    "status": 200,
    "message": "Get team tasks successful",
    "data": [
      {
        "id": 1,
        "title": "Team Task 1",
        "description": "Description",
        "dueDate": "2025-12-31"
      }
    ]
  }
  ```

## Categories

### Get All Categories
- **URL**: `/category`
- **Method**: `GET`
- **Response**:
  ```json
  {
    "status": 200,
    "message": "Get all categories successful",
    "data": [
      {
        "id": 1,
        "name": "Work",
        "color": "#FF5733"
      },
      {
        "id": 2,
        "name": "Personal",
        "color": "#33FF57"
      }
    ]
  }
  ```

## User Management

### Get User Profile
- **URL**: `/user/profile`
- **Method**: `GET`
- **Headers**:
  - `Authorization: Bearer <token>`
- **Response**:
  ```json
  {
    "status": 200,
    "message": "Get profile successful",
    "data": {
      "id": 1,
      "email": "user@example.com",
      "fullName": "User Name",
      "avatar": "http://example.com/avatar.jpg"
    }
  }
  ```

## File Upload

### Upload File
- **URL**: `/file/upload`
- **Method**: `POST`
- **Headers**:
  - `Content-Type: multipart/form-data`
  - `Authorization: Bearer <token>`
- **Form Data**:
  - `file`: The file to upload
- **Response**:
  ```json
  {
    "status": 200,
    "message": "Upload file successful",
    "data": {
      "url": "http://example.com/uploads/filename.jpg",
      "fileName": "filename.jpg",
      "fileSize": 1024
    }
  }
  ```

## Implementation Details

### Database Schema
```sql
-- Users table
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by VARCHAR(255),
    updated_by VARCHAR(255)
);

-- Personal Tasks table
CREATE TABLE personal_tasks (
    id BIGSERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date TIMESTAMP WITH TIME ZONE,
    priority VARCHAR(50),
    completed BOOLEAN DEFAULT false,
    notification_time TIME,
    user_id BIGINT REFERENCES users(id),
    category_id BIGINT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by VARCHAR(255),
    updated_by VARCHAR(255)
);
```

### Security Configuration
```java
@Configuration
@EnableWebSecurity
@RequiredArgsConstructor
public class SecurityConfig {
    private final JwtAuthenticationFilter jwtAuthFilter;
    
    @Bean
    public SecurityFilterChain securityFilterChain(HttpSecurity http) throws Exception {
        http
            .csrf(AbstractHttpConfigurer::disable)
            .authorizeHttpRequests(auth -> auth
                .requestMatchers("/api/v1/auth/**").permitAll()
                .anyRequest().authenticated()
            )
            .sessionManagement(session -> 
                session.sessionCreationPolicy(SessionCreationPolicy.STATELESS)
            )
            .addFilterBefore(jwtAuthFilter, UsernamePasswordAuthenticationFilter.class);
            
        return http.build();
    }
}
```

### Error Handling
```java
@ControllerAdvice
public class GlobalExceptionHandler extends ResponseEntityExceptionHandler {
    
    @ExceptionHandler(MethodArgumentNotValidException.class)
    protected ResponseEntity<Object> handleValidationExceptions(
            MethodArgumentNotValidException ex) {
        List<String> errors = ex.getBindingResult()
            .getFieldErrors()
            .stream()
            .map(DefaultMessageSourceResolvable::getDefaultMessage)
            .collect(Collectors.toList());
            
        return ResponseEntity.badRequest()
            .body(ApiResponse.error("Validation failed", errors));
    }
    
    @ExceptionHandler(AppException.class)
    public ResponseEntity<ApiResponse<?>> handleAppException(AppException ex) {
        return ResponseEntity.status(ex.getStatus())
            .body(ApiResponse.error(ex.getMessage(), ex.getErrors()));
    }
}
```

## Audit Trail

The application maintains a comprehensive audit trail for all data modifications. Each entity includes audit information to track who created/updated the data and when.

### Audit Fields

All response DTOs that represent database entities include the following audit fields:

1. **Creation Audit**
   - `createdAt`: Timestamp when the record was created (ISO-8601 format)
   - `createdBy`: Username or identifier of the user who created the record

2. **Update Audit**
   - `updatedAt`: Timestamp when the record was last updated (ISO-8601 format)
   - `updatedBy`: Username or identifier of the user who last updated the record

### Audit DTOs

#### CreatedAuditDTO
```java
public class CreatedAuditDTO {
    Instant createdAt;  // Timestamp of creation
    String createdBy;   // Username who created the record
}
```

#### UpdatedAuditDTO
```java
public class UpdatedAuditDTO {
    Instant updatedAt;  // Timestamp of last update
    String updatedBy;   // Username who last updated the record
}
```

### Example Response with Audit Fields
```json
{
  "id": 123,
  "title": "Complete API Documentation",
  "description": "Add audit trail documentation",
  "created": {
    "createdAt": "2025-10-19T14:30:00Z",
    "createdBy": "admin@example.com"
  },
  "updated": {
    "updatedAt": "2025-10-19T15:45:00Z",
    "updatedBy": "developer@example.com"
  }
}
```

## Implementation Checklist

### Required Dependencies (pom.xml)
```xml
<dependencies>
    <!-- Spring Boot Starter -->
    <dependency>
        <groupId>org.springframework.boot</groupId>
        <artifactId>spring-boot-starter-web</artifactId>
    </dependency>
    <dependency>
        <groupId>org.springframework.boot</groupId>
        <artifactId>spring-boot-starter-data-jpa</artifactId>
    </dependency>
    <dependency>
        <groupId>org.springframework.boot</groupId>
        <artifactId>spring-boot-starter-security</artifactId>
    </dependency>
    
    <!-- Database -->
    <dependency>
        <groupId>org.postgresql</groupId>
        <artifactId>postgresql</artifactId>
        <scope>runtime</scope>
    </dependency>
    
    <!-- JWT -->
    <dependency>
        <groupId>io.jsonwebtoken</groupId>
        <artifactId>jjwt-api</artifactId>
        <version>0.11.5</version>
    </dependency>
    
    <!-- Lombok -->
    <dependency>
        <groupId>org.projectlombok</groupId>
        <artifactId>lombok</artifactId>
        <optional>true</optional>
    </dependency>
    
    <!-- Validation -->
    <dependency>
        <groupId>org.springframework.boot</groupId>
        <artifactId>spring-boot-starter-validation</artifactId>
    </dependency>
    
    <!-- Documentation -->
    <dependency>
        <groupId>org.springdoc</groupId>
        <artifactId>springdoc-openapi-starter-webmvc-ui</artifactId>
        <version>2.1.0</version>
    </dependency>
</dependencies>
```

### Configuration (application.properties)
```properties
# Server
server.port=8080

# Database
spring.datasource.url=jdbc:postgresql://localhost:5432/todolist
spring.datasource.username=postgres
spring.datasource.password=yourpassword
spring.jpa.hibernate.ddl-auto=update
spring.jpa.show-sql=true

# JWT
jwt.secret=your-jwt-secret-key-here
jwt.expiration=86400000  # 24 hours in milliseconds

# File Upload
spring.servlet.multipart.max-file-size=10MB
spring.servlet.multipart.max-request-size=10MB
file.upload-dir=uploads/
```

## Data Transfer Objects (DTOs)

### Authentication DTOs

#### ReqLoginDTO
```java
public class ReqLoginDTO {
    @NotBlank(message = "Username is required")
    String username;

    @Min(value = 6, message = "Password must be at least 6 characters long")
    @NotBlank(message = "Password is required")
    String password;
}
```

#### ResLoginDTO
```java
public class ResLoginDTO {
    String accessToken;
    ResUserDTO user;
}
```

### Task DTOs

#### ReqPersonalTaskDTO
```java
public class ReqPersonalTaskDTO {
    Long id;  // Optional for updates
    String title;  // Required
    String description;  // Optional
    Date dueDate;  // Required
    String priority;  // Optional
    boolean completed;  // Default: false
    @JsonFormat(pattern = "HH:mm") LocalTime notificationTime;  // Optional
    Long categoryId;  // Optional
}
```

#### ResPersonalTaskDTO
```java
public class ResPersonalTaskDTO {
    Long id;
    String title;
    String description;
    Date dueDate;
    String priority;
    boolean completed;
    @JsonFormat(pattern = "HH:mm") LocalTime notificationTime;
    Long categoryId;
    CreatedAuditDTO created;
    UpdatedAuditDTO updated;
}
```

### Common DTOs

#### CreatedAuditDTO
```java
public class CreatedAuditDTO {
    Long createdAt;
    String createdBy;
}
```

#### UpdatedAuditDTO
```java
public class UpdatedAuditDTO {
    Long updatedAt;
    String updatedBy;
}
```

## Error Responses

### 400 Bad Request
```json
{
  "status": 400,
  "message": "Validation error",
  "errors": [
    {
      "field": "email",
      "message": "Email is required"
    }
  ]
}
```

### 401 Unauthorized
```json
{
  "status": 401,
  "message": "Unauthorized",
  "data": null
}
```

### 404 Not Found
```json
{
  "status": 404,
  "message": "Resource not found",
  "data": null
}
```

### 500 Internal Server Error
```json
{
  "status": 500,
  "message": "Internal server error",
  "data": null
}
```
