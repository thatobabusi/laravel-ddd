# Scaffolding & Domain Structure

Advanced domain scaffolding with bounded-contexts and layered architecture.

## Bounded-Context Structure

A **bounded-context** is an organizational unit that groups domains by shared concepts and ubiquitous language. This guide shows how to scaffold and organize them using Laravel-DDD.

### Context Layers

Each bounded-context has four standard layers:

```
src/UserManagement/
├─ Domain/               # Core business rules, entities, value objects
├─ Application/         # Use cases, commands, queries, repositories
├─ Presentation/        # Controllers, requests, responses
└─ Infrastructure/      # Eloquent models, service providers, external APIs
```

## Creating a New Bounded-Context

### Quick Scaffold

Generate all four layers at once:

```bash
php artisan ddd:make:domain UserManagement
```

Output:
```
src/UserManagement/
├─ Domain/
├─ Application/
├─ Presentation/
└─ Infrastructure/
```

---

## Scaffolding Commands

### 1. Eloquent Models (Infrastructure)

Eloquent models belong in the **Infrastructure** layer, not the Domain.

```bash
php artisan ddd:eloquent-model UserManagement:User
```

Creates: `src/UserManagement/Infrastructure/Models/User.php`

**Stub:**
```php
<?php
namespace Domain\UserManagement\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        //
    ];
}
```

---

### 2. Repositories

Create both interface and implementation:

```bash
php artisan ddd:repository UserManagement:UserRepository
```

Creates:
- `src/UserManagement/Application/Contracts/UserRepositoryInterface.php`
- `src/UserManagement/Application/Repositories/UserRepository.php`

**Implementation stub:**
```php
class UserRepository implements UserRepositoryInterface
{
    public function __construct(private User $model) {}

    public function findById(int $id) {
        return $this->model->findOrFail($id);
    }
}
```

---

### 3. Mappers

Map between Eloquent models and Domain entities:

```bash
php artisan ddd:mapper UserManagement:UserMapper
```

Creates: `src/UserManagement/Application/Mappers/UserMapper.php`

**Stub:**
```php
class UserMapper
{
    public function toDomain($eloquentModel)
    {
        return new DomainUser(
            id: $eloquentModel->id,
            name: $eloquentModel->name,
            email: $eloquentModel->email,
        );
    }

    public function toEloquent($domainUser): array
    {
        return [
            'name' => $domainUser->name,
            'email' => $domainUser->email,
        ];
    }
}
```

---

### 4. Policies

Domain-level authorization:

```bash
php artisan ddd:policy UserManagement:UserPolicy
```

Creates: `src/UserManagement/Domain/Policies/UserPolicy.php`

**Stub:**
```php
class UserPolicy
{
    public function view(User $user, User $target): bool
    {
        return $user->id === $target->id || $user->isAdmin();
    }

    public function update(User $user, User $target): bool
    {
        return $user->id === $target->id;
    }

    public function delete(User $user, User $target): bool
    {
        return $user->isAdmin();
    }
}
```

---

### 5. Service Provider

Bind interfaces to implementations:

```bash
php artisan ddd:provider UserManagement:UserManagementServiceProvider
```

Creates: `src/UserManagement/Infrastructure/Providers/UserManagementServiceProvider.php`

**Stub:**
```php
class UserManagementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    public function boot(): void
    {
        // Load routes, migrations, etc.
    }
}
```

Register in `config/app.php`:
```php
'providers' => [
    // ...
    Domain\UserManagement\Infrastructure\Providers\UserManagementServiceProvider::class,
],
```

---

### 6. Commands & Queries (CQRS)

**Create a Command (modifies state):**
```bash
php artisan ddd:command-query UserManagement:CreateUserCommand
```

Creates: `src/UserManagement/Application/Commands/CreateUserCommand.php`

```php
class CreateUserCommand
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}

    public function handle(CreateUserHandler $handler)
    {
        return $handler->execute($this);
    }
}
```

**Create a Query (retrieves data, no side effects):**
```bash
php artisan ddd:command-query UserManagement:GetUserQuery --query
```

Creates: `src/UserManagement/Application/Queries/GetUserQuery.php`

```php
class GetUserQuery
{
    public function __construct(public int $userId) {}

    public function handle(GetUserQueryHandler $handler)
    {
        return $handler->execute($this);
    }
}
```

---

## Full Workflow Example

### 1. Create Bounded-Context
```bash
php artisan ddd:make:domain UserManagement
```

### 2. Create Infrastructure Layer
```bash
php artisan ddd:eloquent-model UserManagement:User
php artisan ddd:repository UserManagement:UserRepository
php artisan ddd:mapper UserManagement:UserMapper
```

### 3. Create Application Layer
```bash
php artisan ddd:command-query UserManagement:CreateUserCommand
php artisan ddd:command-query UserManagement:GetUserQuery --query
```

### 4. Create Domain Layer
```bash
php artisan ddd:policy UserManagement:UserPolicy
php artisan ddd:exception UserManagement:UserNotFoundException
```

### 5. Bind Everything
```bash
php artisan ddd:provider UserManagement:UserManagementServiceProvider
```

### 6. Register Provider
Add to `config/app.php`:
```php
Domain\UserManagement\Infrastructure\Providers\UserManagementServiceProvider::class,
```

---

## Layer Responsibilities

### Domain Layer
- **Entities & Value Objects** → Core business rules
- **Exceptions** → Domain-specific errors
- **Policies** → Authorization rules
- **Services** → Complex business logic
- **Factories** → Object creation

### Application Layer
- **Commands** → Write operations (create, update, delete)
- **Queries** → Read operations (fetch, list)
- **Repositories** → Data persistence (implements domain interfaces)
- **Mappers** → Transform between layers (DTO ↔ Entity)
- **Handlers** → Execute commands/queries

### Presentation Layer
- **Controllers** → HTTP request handling
- **Requests** → Input validation
- **Resources** → API responses

### Infrastructure Layer
- **Models** → Eloquent ORM objects
- **Service Providers** → Dependency injection
- **External APIs** → Third-party integrations

---

## Best Practices

### 1. One Responsibility Per Layer
- Domain: Business logic
- Application: Orchestration
- Presentation: HTTP concerns
- Infrastructure: Persistence

### 2. Depend on Abstractions
```php
// Register in provider
$this->app->bind(UserRepositoryInterface::class, UserRepository::class);

// Inject interface, not concrete class
public function __construct(UserRepositoryInterface $repository) {}
```

### 3. Use Mappers for Translation
Never pass Eloquent models to the Domain layer:
```php
// ❌ Bad
$user = User::find($id);
$result = $action->execute($user);

// ✅ Good
$eloquentUser = User::find($id);
$domainUser = $mapper->toDomain($eloquentUser);
$result = $action->execute($domainUser);
```

### 4. Commands for Write, Queries for Read
```php
// Create user (Command)
$command = new CreateUserCommand('John', 'john@example.com', 'password');
$userId = $handler->execute($command);

// Fetch user (Query)
$query = new GetUserQuery($userId);
$user = $handler->execute($query);
```

### 5. Bind in Service Providers
Never import concrete classes directly; always use dependency injection.

---

## Testing Scaffolded Components

### Test a Repository
```php
public function test_user_repository_finds_by_id()
{
    $user = User::factory()->create();
    $repository = app(UserRepositoryInterface::class);
    
    $found = $repository->findById($user->id);
    
    $this->assertEquals($user->id, $found->id);
}
```

### Test a Command
```php
public function test_create_user_command()
{
    $command = new CreateUserCommand('John', 'john@example.com', 'password');
    $handler = app(CreateUserHandler::class);
    
    $userId = $handler->execute($command);
    
    $this->assertNotNull($userId);
}
```

### Test a Policy
```php
public function test_user_can_update_own_profile()
{
    $user = User::factory()->create();
    $policy = new UserPolicy();
    
    $this->assertTrue($policy->update($user, $user));
}
```

---

## Key Takeaways

1. **Bounded-contexts** organize large domains into manageable units
2. **Four layers** separate concerns: Domain, Application, Presentation, Infrastructure
3. **Scaffolding commands** generate boilerplate quickly
4. **Mappers** translate between Eloquent and Domain entities
5. **Service Providers** bind abstractions to implementations
6. **Commands & Queries** follow CQRS pattern for clarity
7. **Policies** enforce domain authorization rules
