
# Bibo MVC Framework

A comprehensive PHP MVC framework built for educational purposes and rapid application development. This framework provides a solid foundation with modern PHP practices, dependency injection, middleware support, and extensive configuration options.

## Table of Contents

- [Project Overview](#project-overview)
- [Project Structure](#project-structure)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Routing](#routing)
- [Controllers](#controllers)
- [Views and Templates](#views-and-templates)
- [Middleware](#middleware)
- [Database](#database)
- [Caching](#caching)
- [Logging](#logging)
- [Security](#security)
- [Frontend Assets](#frontend-assets)
- [Development Tools](#development-tools)
- [Package Dependencies](#package-dependencies)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## Project Overview

Bibo MVC is a modern PHP framework that follows the Model-View-Controller architectural pattern. It's designed to be educational yet powerful, incorporating industry best practices and modern PHP features.

**Key Characteristics:**
- PSR-compliant (PSR-4 autoloading, PSR-7 HTTP messages, PSR-11 container)
- Service Provider architecture for modular design
- Dependency Injection Container
- Middleware support for request/response processing
- Twig templating engine integration
- RESTful API support
- Comprehensive caching system
- Advanced logging capabilities
- Security features (CSRF, CORS, encryption)
- Asset compilation with Webpack

## Project Structure

```
mvc/
├── app/                          # Application layer
│   ├── Controllers/              # Application controllers
│   ├── Middleware/               # Custom middleware
│   └── Models/                   # Application models
├── bootstrap/                    # Framework bootstrap files
│   ├── bootstrap.php             # Service provider registration
│   ├── middleware.php            # Middleware configuration
│   └── observers.php             # Event observers
├── config/                       # Configuration files
│   ├── api.php                   # API configuration
│   ├── app.php                   # Main application config
│   ├── backup.php                # Backup settings
│   ├── cache.php                 # Cache configuration
│   ├── cookies.php               # Cookie settings
│   ├── cors.php                  # CORS configuration
│   ├── db.php                    # Database configuration
│   ├── endpoints.php             # API endpoints
│   ├── ftp.php                   # FTP settings
│   ├── log.php                   # Logging configuration
│   ├── mail.php                  # Email settings
│   ├── security.php              # Security configuration
│   ├── session.php               # Session settings
│   └── web.php                   # Web configuration
├── docs/                         # Generated documentation
├── install/                      # Installation scripts
├── public/                       # Web server document root
│   ├── css/                      # Compiled CSS files
│   ├── js/                       # Compiled JavaScript files
│   ├── media/                    # Media assets
│   ├── pages/                    # Static pages
│   ├── themes/                   # Theme assets
│   ├── index.php                 # Application entry point
│   └── robots.txt                # Search engine directives
├── resources/                    # Raw assets and views
│   ├── css/                      # Source CSS files
│   ├── js/                       # Source JavaScript files
│   ├── themes/                   # Theme resources
│   ├── views/                    # Twig templates
│   └── index.js                  # Main JavaScript entry point
├── routes/                       # Route definitions
│   └── web.php                   # Web routes
├── scripts/                      # Utility scripts
│   ├── clear_cache.php           # Cache clearing script
│   └── clear_logs.php            # Log clearing script
├── src/                          # Framework core
│   ├── Core/                     # Core framework components
│   ├── constants/                # Framework constants
│   └── functions/                # Helper functions
├── storage/                      # Application storage
│   ├── cache/                    # Cache files
│   ├── logs/                     # Log files
│   ├── session/                  # Session files
│   └── tmp/                      # Temporary files
├── tests/                        # Test suite
│   ├── unit/                     # Unit tests
│   └── bootstrap.php             # Test bootstrap
├── tools/                        # Development tools
└── vendor/                       # Composer dependencies
```

## Features

### Core Framework Features
- **MVC Architecture**: Clean separation of concerns
- **Dependency Injection**: PSR-11 compliant container
- **Service Providers**: Modular service registration
- **Middleware Pipeline**: Request/response processing
- **Routing System**: Flexible route matching with parameters
- **Template Engine**: Twig integration for views
- **HTTP Client**: Built-in REST client for API consumption

### Database & ORM
- **Eloquent ORM**: Laravel's database abstraction layer
- **Query Builder**: Fluent database query interface
- **Migrations**: Database schema versioning
- **Multiple Connections**: Support for multiple databases

### Caching System
- **File Cache**: File-based caching implementation
- **Cache Abstraction**: PSR-16 simple cache interface
- **Cache Tags**: Organized cache invalidation

### Security Features
- **CSRF Protection**: Cross-site request forgery prevention
- **CORS Support**: Cross-origin resource sharing
- **Encryption**: AES-256-CBC encryption
- **Input Validation**: Request data sanitization
- **Security Headers**: HTTP security headers

### Development Tools
- **Webpack Integration**: Asset compilation and optimization
- **SASS Support**: CSS preprocessing
- **Babel Transpilation**: Modern JavaScript support
- **Code Quality Tools**: PHPStan, PHPMD, PHPCS, Psalm
- **Unit Testing**: PHPUnit integration
- **Documentation**: Automated API documentation

## Requirements

### System Requirements
- **PHP**: >= 8.1
- **Web Server**: Apache/Nginx
- **Database**: MySQL/PostgreSQL/SQLite (optional)

### PHP Extensions
- `ext-curl`: HTTP client functionality
- `ext-zlib`: Compression support
- `ext-ftp`: FTP operations
- `ext-iconv`: Character encoding conversion

### Development Requirements
- **Node.js**: >= 16.x (for asset compilation)
- **Composer**: PHP dependency management
- **NPM/Yarn**: JavaScript package management

## Installation

### 1. Clone the Repository
```bash
git clone <repository-url> mvc
cd mvc
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node.js Dependencies
```bash
npm install
# or
yarn install
```

### 4. Set Permissions
```bash
chmod +x permissions.sh
./permissions.sh
```

### 5. Configure Environment
Copy and configure your environment settings:
```bash
# Configure your application settings in config/ directory
# Set up database connection in config/db.php
# Configure application settings in config/app.php
```

### 6. Build Frontend Assets
```bash
npm run build
```

### 7. Web Server Configuration

#### Apache
Ensure your virtual host points to the `public/` directory:
```apache
<VirtualHost *:80>
    DocumentRoot /var/www/html/mvc/public
    ServerName your-domain.com

    <Directory /var/www/html/mvc/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/html/mvc/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Configuration

### Application Configuration
Main application settings are located in `config/app.php`:

```php
return [
    'app' => [
        'name' => 'Your Application Name',
        'url' => 'http://localhost',
        'version' => '1.0.0',
        'env' => 'development', // development, production
        'debug' => true,
        'timezone' => 'Europe/Sofia',
        'locale' => 'en',
        'key' => 'your-encryption-key',
        'cipher' => 'AES-256-CBC',
    ],
];
```

### Database Configuration
Configure your database connection in `config/db.php`:

```php
return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'port' => '3306',
            'database' => 'your_database',
            'username' => 'your_username',
            'password' => 'your_password',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
    ],
];
```

### Cache Configuration
Configure caching in `config/cache.php`:

```php
return [
    'default' => 'file',
    'stores' => [
        'file' => [
            'driver' => 'file',
            'path' => storage_path('cache'),
        ],
    ],
];
```

## Usage

### Basic Application Flow
1. **Request**: HTTP request hits `public/index.php`
2. **Bootstrap**: Framework services are registered via `bootstrap/bootstrap.php`
3. **Routing**: Request is matched against routes in `routes/web.php`
4. **Middleware**: Request passes through middleware pipeline
5. **Controller**: Matched route executes controller method
6. **Response**: Controller returns response (view, JSON, redirect, etc.)

### Creating a Simple Page
1. **Define Route** in `routes/web.php`:
```php
Route::get('/hello', [HelloController::class, 'index']);
```

2. **Create Controller** in `app/Controllers/HelloController.php`:
```php
<?php
namespace Bibo\App\Controllers;

use Bibo\Core\Controller\Controller;

class HelloController extends Controller
{
    public function index(): string
    {
        return $this->render('hello', ['message' => 'Hello World!']);
    }
}
```

3. **Create View** in `resources/views/hello.twig`:
```twig
<!DOCTYPE html>
<html>
<head>
    <title>Hello Page</title>
</head>
<body>
    <h1>{{ message }}</h1>
</body>
</html>
```

## Routing

The routing system supports various HTTP methods and parameter binding:

### Basic Routes
```php
// GET route
Route::get('/users', [UserController::class, 'index']);

// POST route
Route::post('/users', [UserController::class, 'store']);

// PUT route
Route::put('/users/{id}', [UserController::class, 'update']);

// DELETE route
Route::delete('/users/{id}', [UserController::class, 'destroy']);
```

### Route Parameters
```php
// Required parameters
Route::get('/user/{id}', [UserController::class, 'show']);

// Multiple parameters
Route::get('/user/{name}/{id}', [UserController::class, 'profile']);

// Closure routes
Route::get('/info', function () {
    return 'Information page';
});
```

### Middleware
```php
// Single middleware
Route::get('/admin', [AdminController::class, 'index'], ['auth']);

// Multiple middleware
Route::get('/api/data', [ApiController::class, 'data'], ['cors', 'auth']);
```

## Controllers

Controllers handle the application logic and return responses:

### Base Controller
All controllers extend the base `Controller` class:

```php
<?php
namespace Bibo\App\Controllers;

use Bibo\Core\Controller\Controller;
use Bibo\Core\Response\JsonResponse;

class UserController extends Controller
{
    public function index(): string
    {
        $users = User::all();
        return $this->render('users/index', ['users' => $users]);
    }

    public function show(int $id): string
    {
        $user = User::find($id);
        return $this->render('users/show', ['user' => $user]);
    }

    public function api(): JsonResponse
    {
        $users = User::all();
        return new JsonResponse($users->toArray());
    }
}
```

### Response Types
Controllers can return various response types:

- **View Response**: `return $this->render('template', $data);`
- **JSON Response**: `return new JsonResponse($data);`
- **Redirect Response**: `return new RedirectResponse('/path');`
- **HTML Response**: `return new HtmlResponse($html);`

## Views and Templates

The framework uses Twig as the templating engine:

### Template Structure
Templates are stored in `resources/views/`:

```twig
{# resources/views/layout.twig #}
<!DOCTYPE html>
<html>
<head>
    <title>{% block title %}Default Title{% endblock %}</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <nav>
        <!-- Navigation -->
    </nav>

    <main>
        {% block content %}{% endblock %}
    </main>

    <script src="/js/main.js"></script>
</body>
</html>
```

```twig
{# resources/views/users/index.twig #}
{% extends "layout.twig" %}

{% block title %}Users{% endblock %}

{% block content %}
    <h1>Users</h1>
    <ul>
    {% for user in users %}
        <li>{{ user.name }} - {{ user.email }}</li>
    {% endfor %}
    </ul>
{% endblock %}
```

### Template Functions
The framework provides helper functions in templates:

```twig
{# URL generation #}
<a href="{{ url('/users/1') }}">User Profile</a>

{# Asset URLs #}
<img src="{{ asset('media/logo.png') }}" alt="Logo">

{# Configuration values #}
<p>App Name: {{ config('app.name') }}</p>
```

## Middleware

Middleware provides a convenient mechanism for filtering HTTP requests:

### Creating Middleware
Create middleware in `app/Middleware/`:

```php
<?php
namespace Bibo\App\Middleware;

use Bibo\Core\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(RequestInterface $request, callable $next): ResponseInterface
    {
        // Check authentication
        if (!$this->isAuthenticated($request)) {
            return new RedirectResponse('/login');
        }

        return $next($request);
    }

    private function isAuthenticated(RequestInterface $request): bool
    {
        // Authentication logic
        return true;
    }
}
```

### Built-in Middleware
The framework includes several built-in middleware:

- **CSRF**: Cross-site request forgery protection
- **CORS**: Cross-origin resource sharing
- **Auth**: Authentication middleware
- **Throttle**: Rate limiting

## Database

The framework uses Laravel's Eloquent ORM for database operations:

### Models
Create models in `app/Models/`:

```php
<?php
namespace Bibo\App\Models;

use Bibo\Core\Model\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
```

### Query Builder
Use the query builder for complex queries:

```php
// In a controller
$users = DB::table('users')
    ->where('active', 1)
    ->orderBy('name')
    ->get();

// Using models
$users = User::where('active', 1)
    ->with('posts')
    ->paginate(10);
```

## Caching

The framework provides a flexible caching system:

### File Cache
```php
use Bibo\Core\Cache\Cache;

// Store data
Cache::put('key', 'value', 3600); // 1 hour

// Retrieve data
$value = Cache::get('key', 'default');

// Check existence
if (Cache::has('key')) {
    // Key exists
}

// Remove data
Cache::forget('key');

// Clear all cache
Cache::flush();
```

### Cache Tags
```php
// Tag cache entries
Cache::tags(['users', 'posts'])->put('user.1', $user, 3600);

// Flush by tags
Cache::tags(['users'])->flush();
```

## Logging

The framework provides comprehensive logging capabilities:

### Basic Logging
```php
use Bibo\Core\Logger\Logger;

// Log levels
Logger::emergency('System is unusable');
Logger::alert('Action must be taken immediately');
Logger::critical('Critical conditions');
Logger::error('Error conditions');
Logger::warning('Warning conditions');
Logger::notice('Normal but significant condition');
Logger::info('Informational messages');
Logger::debug('Debug-level messages');
```

### Log Handlers
Configure logging in `config/log.php`:

- **File Handler**: Logs to files in `storage/logs/`
- **Database Handler**: Logs to database table
- **Syslog Handler**: System log integration

## Security

### CSRF Protection
```php
// In forms
<form method="POST" action="/submit">
    {{ csrf_field() }}
    <!-- form fields -->
</form>

// In controllers
if (!$this->verifyCsrfToken($request)) {
    throw new UnauthorizedException('Invalid CSRF token');
}
```

### CORS Configuration
Configure CORS in `config/cors.php`:

```php
return [
    'allowed_origins' => ['*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
    'allowed_headers' => ['Content-Type', 'Authorization'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

### Encryption
```php
use Bibo\Core\Security\Security;

// Encrypt data
$encrypted = Security::encrypt('sensitive data');

// Decrypt data
$decrypted = Security::decrypt($encrypted);

// Hash passwords
$hash = Security::hash('password');

// Verify passwords
$isValid = Security::verify('password', $hash);
```

## Frontend Assets

### Asset Compilation
The framework uses Webpack for asset compilation:

```bash
# Development build
npm run build

# Production build (with optimization)
npm run build --mode=production

# Watch for changes
npm run watch
```

### Asset Structure
```
resources/
├── css/
│   ├── index.css          # Main CSS file
│   └── variables.css      # CSS variables
├── js/
│   └── libs/              # JavaScript libraries
├── themes/
│   ├── dark/              # Dark theme assets
│   └── light/             # Light theme assets
└── index.js               # Main JavaScript entry point
```

### Frontend Dependencies
- **Bootstrap 5.3.5**: CSS framework
- **Axios 1.8.3**: HTTP client for AJAX requests
- **SASS**: CSS preprocessing
- **Babel**: JavaScript transpilation

## Development Tools

### Code Quality Tools
The framework includes several code quality tools:

```bash
# PHPStan - Static analysis
vendor/bin/phpstan analyse

# PHPMD - Mess detector
vendor/bin/phpmd src text phpmd.xml

# PHPCS - Code sniffer
vendor/bin/phpcs --standard=phpcs.xml src/

# Psalm - Static analysis
vendor/bin/psalm
```

### Configuration Files
- `phpstan.neon`: PHPStan configuration
- `phpmd.xml`: PHP Mess Detector rules
- `phpcs.xml`: PHP Code Sniffer standards
- `psalm.xml`: Psalm configuration

### Utility Scripts
```bash
# Clear cache
php scripts/clear_cache.php

# Clear logs
php scripts/clear_logs.php

# Set permissions
./permissions.sh
```

## Package Dependencies

### PHP Dependencies (Composer)
```json
{
    "require": {
        "psr/log": "^3.0@dev",
        "psr/http-message": "^2.0@dev",
        "psr/container": "^2.0@dev",
        "psr/http-factory": "^1.1",
        "psr/http-server-handler": "^1.0@dev",
        "psr/simple-cache": "^3.0@dev",
        "psr/clock": "dev-master",
        "psr/http-server-middleware": "^1.0@dev",
        "psr/http-client": "^1.0@dev",
        "symfony/polyfill-iconv": "1.x-dev",
        "twig/twig": "4.x-dev",
        "illuminate/database": "10.x-dev"
    },
    "require-dev": {
        "symfony/var-dumper": "7.3.x-dev",
        "phpunit/phpunit": "^11"
    }
}
```

### Node.js Dependencies (NPM)
```json
{
    "dependencies": {
        "axios": "^1.8.3",
        "bootstrap": "^5.3.5"
    },
    "devDependencies": {
        "@babel/core": "^7.26.10",
        "@babel/preset-env": "^7.26.9",
        "babel-loader": "^10.0.0",
        "css-loader": "^7.1.2",
        "css-minimizer-webpack-plugin": "^7.0.2",
        "mini-css-extract-plugin": "^2.9.2",
        "sass": "^1.86.0",
        "sass-loader": "^16.0.5",
        "style-loader": "^4.0.0",
        "webpack": "^5.98.0",
        "webpack-cli": "^6.0.1"
    }
}
```

## API Documentation

### Available Endpoints
The framework includes several example API endpoints:

```php
// GET /api/ping - Health check endpoint
Route::get('/api/ping', [TestController::class, 'ping'], ['cors']);

// Response:
{
    "status": "success",
    "message": "pong",
    "timestamp": "2024-01-01T12:00:00Z"
}
```

### Creating API Endpoints
```php
// In routes/web.php
Route::get('/api/users', [UserController::class, 'apiIndex'], ['cors']);
Route::post('/api/users', [UserController::class, 'apiStore'], ['cors', 'csrf']);
Route::get('/api/users/{id}', [UserController::class, 'apiShow'], ['cors']);
Route::put('/api/users/{id}', [UserController::class, 'apiUpdate'], ['cors', 'csrf']);
Route::delete('/api/users/{id}', [UserController::class, 'apiDestroy'], ['cors', 'csrf']);

// In controller
public function apiIndex(): JsonResponse
{
    $users = User::all();
    return new JsonResponse([
        'status' => 'success',
        'data' => $users->toArray(),
        'meta' => [
            'total' => $users->count(),
            'page' => 1,
            'per_page' => 10
        ]
    ]);
}
```

### API Response Format
Standard API response format:

```json
{
    "status": "success|error",
    "message": "Optional message",
    "data": {},
    "meta": {
        "total": 100,
        "page": 1,
        "per_page": 10
    },
    "errors": []
}
```

## Testing

### Running Tests
```bash
# Run all tests
vendor/bin/phpunit

# Run specific test file
vendor/bin/phpunit tests/unit/ExampleTest.php

# Run with coverage
vendor/bin/phpunit --coverage-html coverage/
```

### Writing Tests
Create tests in `tests/unit/`:

```php
<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Bibo\App\Controllers\IndexController;

class IndexControllerTest extends TestCase
{
    public function testIndexReturnsString(): void
    {
        $controller = new IndexController();
        $result = $controller->index();

        $this->assertIsString($result);
        $this->assertStringContainsString('home', $result);
    }

    public function testJsonReturnsJsonResponse(): void
    {
        $controller = new IndexController();
        $response = $controller->json();

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
```

### Test Configuration
PHPUnit configuration is in `phpunit.xml`:

- **Bootstrap**: `vendor/autoload.php`
- **Test Directory**: `tests/`
- **Coverage**: Includes `src/` directory
- **Cache**: Uses `.phpunit.cache/` for performance

## Contributing

### Development Workflow
1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes (`git commit -m 'Add amazing feature'`)
4. **Push** to the branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

### Coding Standards
- Follow **PSR-12** coding standards
- Use **PHPDoc** comments for all public methods
- Write **unit tests** for new features
- Run **code quality tools** before submitting

### Code Quality Checks
```bash
# Run all quality checks
composer run-script quality-check

# Individual tools
vendor/bin/phpstan analyse
vendor/bin/phpmd src text phpmd.xml
vendor/bin/phpcs --standard=phpcs.xml src/
vendor/bin/psalm
```

### Documentation
- Update **README.md** for new features
- Add **inline documentation** for complex code
- Update **API documentation** for new endpoints
- Include **examples** in documentation

## License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

### MIT License Summary
- ✅ **Commercial use**
- ✅ **Modification**
- ✅ **Distribution**
- ✅ **Private use**
- ❌ **Liability**
- ❌ **Warranty**

---

## Author

**biboletin** - [shady_fan@abv.bg](mailto:shady_fan@abv.bg)

## Acknowledgments

- **Laravel** - For the Eloquent ORM
- **Twig** - For the templating engine
- **Symfony** - For various components
- **PSR Standards** - For interface definitions
- **Bootstrap** - For the CSS framework

---

*This framework is designed for educational purposes and rapid prototyping. For production use, consider additional security hardening and performance optimizations.*