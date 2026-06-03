# Test Suite Documentation - Manajemen Kos Application

## Overview

A comprehensive test suite has been created for the **Manajemen Kos** (Room/Boarding House Management) Laravel application. The test suite covers all controllers, routes, models, and database operations.

## Test Structure

### Directory Structure

```
tests/
├── Feature/
│   ├── UserControllerTest.php      # UserController feature tests
│   ├── RouteTest.php               # Route functionality tests
│   ├── IntegrationTest.php         # Integration tests for workflows
│   ├── DatabaseTest.php            # Database operations tests
│   └── HttpRequestTest.php         # HTTP request/response tests
├── Unit/
│   ├── ExampleTest.php             # Original example test
│   ├── UserTest.php                # User model unit tests
│   └── CrTest.php                  # CR model unit tests
└── TestCase.php                    # Base test case class
```

## Test Files and Coverage

### 1. Feature Tests

#### **UserControllerTest.php** (7 tests)
Tests for the UserController methods:
- ✅ Index method returns user dashboard view
- ✅ Index method with authenticated user
- ✅ Create method exists
- ✅ Store method exists
- ✅ Show method exists
- ✅ Edit method exists
- ✅ Update and destroy methods exist

#### **RouteTest.php** (8 tests)
Tests for all application routes:
- ✅ Home route (/) returns index view
- ✅ Index route (/index) returns user dashboard
- ✅ Admin route (/admin) returns admin dashboard
- ✅ User route (/user) returns user dashboard
- ✅ Login route (/login) returns login view
- ✅ Booking route (/booking) returns booking view
- ✅ All routes are accessible
- ✅ Undefined routes return 404

#### **IntegrationTest.php** (8 tests)
Integration tests for application workflows:
- ✅ User can access login page
- ✅ User can access booking page
- ✅ Admin dashboard is accessible
- ✅ User dashboard displays for authenticated user
- ✅ Index page displays user dashboard via controller
- ✅ Home page displays index view
- ✅ Multiple test users can be created
- ✅ User creation with specific data
- ✅ CR model instantiation

#### **DatabaseTest.php** (10 tests)
Database operations and schema validation:
- ✅ Users table exists
- ✅ Users table has required columns
- ✅ Password reset tokens table exists
- ✅ Sessions table exists
- ✅ User can be inserted into database
- ✅ User can be retrieved from database
- ✅ User can be updated in database
- ✅ User can be deleted from database
- ✅ Multiple users can be created
- ✅ Email unique constraint

#### **HttpRequestTest.php** (12 tests)
HTTP request and response testing:
- ✅ GET requests to all routes
- ✅ HTTP response headers validation
- ✅ Response content verification
- ✅ Authenticated and unauthenticated requests
- ✅ HTTP method constraints
- ✅ View response validation

### 2. Unit Tests

#### **UserTest.php** (11 tests)
User model unit tests:
- ✅ User can be created
- ✅ User has required attributes
- ✅ User password is hashed
- ✅ User email is unique
- ✅ User fillable attributes
- ✅ User hidden attributes
- ✅ User can be updated
- ✅ User can be deleted
- ✅ User can be retrieved by email
- ✅ User timestamps are set
- ✅ Multiple users can be created

#### **CrTest.php** (8 tests)
CR model unit tests:
- ✅ CR model can be instantiated
- ✅ CR model inherits from Eloquent Model
- ✅ CR table name is correct
- ✅ CR model has timestamps
- ✅ CR model structure validation
- ✅ CR model fillable attributes
- ✅ CR model primary key
- ✅ CR model configuration

## Total Coverage

- **Total Tests:** 64 tests
- **Feature Tests:** 45 tests
- **Unit Tests:** 19 tests

## Running Tests

### Run All Tests

```bash
php artisan test
```

### Run Specific Test File

```bash
php artisan test tests/Feature/UserControllerTest.php
php artisan test tests/Unit/UserTest.php
```

### Run Specific Test Method

```bash
php artisan test tests/Feature/UserControllerTest.php --filter=test_index_returns_user_dashboard_view
```

### Run All Feature Tests

```bash
php artisan test tests/Feature
```

### Run All Unit Tests

```bash
php artisan test tests/Unit
```

### Run Tests with Verbose Output

```bash
php artisan test -v
```

### Run Tests with Code Coverage

```bash
php artisan test --coverage
```

## Test Database

All tests automatically:
- Use an in-memory SQLite database (by default in testing environment)
- Refresh the database before each test method
- Run migrations automatically
- Clean up after tests complete

To use a different test database, modify the `.env.testing` file or update `phpunit.xml` configuration.

## Database Migrations

Tests automatically run the following migrations:
1. `create_users_table` - Users table with id, name, email, password, etc.
2. `create_password_reset_tokens_table` - Password reset functionality
3. `create_jobs_table` - Job queue table
4. `create_cache_table` - Cache functionality

## User Factory

The `UserFactory` is used extensively in tests to create test users with realistic data:

```php
// Create a single user
$user = User::factory()->create();

// Create a user with specific attributes
$user = User::factory()->create([
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// Create multiple users
$users = User::factory()->count(10)->create();

// Create unverified user
$user = User::factory()->unverified()->create();
```

## Test Assertions Reference

### Common Assertions Used

```php
// Response assertions
$response->assertStatus(200)
$response->assertViewIs('login')
$response->assertDatabaseHas('users', ['email' => 'test@example.com'])
$response->assertDatabaseMissing('users', ['id' => 1])
$response->assertDatabaseCount('users', 10)

// Model assertions
$this->assertInstanceOf(User::class, $user)
$this->assertEquals('value', $user->attribute)
$this->assertNotNull($user->id)
$this->assertTrue(method_exists($class, 'methodName'))
```

## Next Steps for Test Expansion

As your application grows, consider adding tests for:

1. **Authentication Tests**
   - Login/logout functionality
   - Password reset flow
   - User registration process

2. **Authorization Tests**
   - Admin access control
   - User role-based permissions
   - Protected routes

3. **API Tests** (if adding API endpoints)
   - JSON response validation
   - API error handling
   - Rate limiting

4. **Validation Tests**
   - Form validation rules
   - Input sanitization
   - Business rule validation

5. **Workflow Tests**
   - Complete booking workflow
   - User profile management
   - Admin operations

6. **Performance Tests**
   - Database query optimization
   - Route response time
   - Heavy load testing

## Configuration Files

### phpunit.xml
The main test configuration file. Key settings:
- Test directory: `tests/`
- Test environment: Uses `.env.testing`
- Database: In-memory SQLite by default

### .env.testing
Test environment configuration:
- `DB_CONNECTION=sqlite`
- `DB_DATABASE=:memory:`
- Custom settings for testing environment

## Troubleshooting

### Tests Not Running
1. Ensure composer dependencies are installed: `composer install`
2. Check phpunit.xml is present in project root
3. Verify Laravel installation: `php artisan --version`

### Database Connection Errors
1. Ensure .env.testing exists or has proper database config
2. Check database permissions
3. Run: `php artisan config:clear`

### Failed Tests
1. Check test output for specific error
2. Run with verbose flag: `php artisan test -v`
3. Check for database migration issues: `php artisan migrate --env=testing`

## Best Practices

1. **Keep Tests Isolated** - Each test should be independent
2. **Use Factories** - Use factories for test data, not hardcoded values
3. **Test One Thing** - Each test should verify one behavior
4. **Use Descriptive Names** - Test names should clearly describe what they test
5. **Clean Up** - Tests automatically clean up via RefreshDatabase trait

## Further Reading

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/)
- [Pest PHP (Alternative Test Framework)](https://pestphp.com/)
