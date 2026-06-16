# Quick Start: All-in-One Feature Wizard

Generate a complete DDD feature in seconds with intelligent prompting.

## The Problem (Solved)

Creating a feature with traditional DDD requires:
- Request class
- Action/Controller
- UseCase interface + implementation
- Service interface + implementation
- Repository interface + implementation
- Output DTO
- Response interface + implementation
- Manual bindings in AppServiceProvider
- Manual route registration

That's **10-15 files** created in **10-15 minutes** of repetitive work.

## The Solution

**One command. One wizard. All files generated.**

```bash
php artisan ddd:make:feature ForHomePageTeacherGet --folder=HomePage
```

---

## Interactive Wizard

The command walks you through intelligent questions about your feature:

```
╔═══════════════════════════════════════════════════╗
║  Laravel DDD Maker    ║
║  Clean Architecture + Domain-Driven Design        ║
╚═══════════════════════════════════════════════════╝

Enter the feature prefix (e.g. ForHomePageTeacherGet):
> ForHomePageTeacherGet

── Complexity & Design Questions ─────────────────────────────

Will this feature require Value Objects (VO)? (yes/no) [no]:
> no

Will the Repository need an Input DTO? (yes/no) [no]:
> no

Will the Service need an Input DTO? (yes/no) [no]:
> no

Will the Response have multiple names? (yes/no) [no]:
> no

Will the Output (DTO) have multiple names? (yes/no) [no]:
> no

Will there be multiple Repositories? (yes/no) [no]:
> no

Is there a need for a domain Entity class? (yes/no) [no]:
> no

Should an Eloquent Model be generated? (yes/no) [no]:
> no

Will this feature need a Request (Form Request) class? (yes/no) [yes]:
> yes

Enter the feature folder name (e.g. HomePage or Users/Profile):
> HomePage
```

---

## Generated Files

After answering questions, the wizard shows:

```
── Files to be generated ─────────────────────────────────────

  Request:
    • app/Http/Requests/Api/V1/HomePage/ForHomePageTeacherGetRequest.php
  Action (Invokable Controller):
    • app/Http/Controllers/Api/V1/HomePage/ForHomePageTeacherGetAction.php
  UseCase:
    • app/UseCases/HomePage/IForHomePageTeacherGetUseCase.php
    • app/UseCases/HomePage/ForHomePageTeacherGetUseCase.php
  Domain Service:
    • app/Domain/HomePage/Services/IForHomePageTeacherGetService.php
    • app/Infra/HomePage/Services/ForHomePageTeacherGetService.php
  Repository:
    • app/Domain/HomePage/Repositories/IForHomePageTeacherGetRepository.php
    • app/Infra/HomePage/Repositories/ForHomePageTeacherGetRepository.php
  Output DTO:
    • app/Domain/HomePage/Services/Output/ForHomePageTeacherGetOutput.php
  Response:
    • app/Http/Responses/Api/V1/HomePage/IForHomePageTeacherGetResponse.php
    • app/Http/Responses/Api/V1/HomePage/ForHomePageTeacherGetResponse.php
```

---

## Auto-Generated Binding Code

After generation, the wizard prints exact code to add to `AppServiceProvider::register()`:

```php
// HomePage - ForHomePageTeacherGet
$this->app->bind(
    App\UseCases\HomePage\IForHomePageTeacherGetUseCase::class,
    App\UseCases\HomePage\ForHomePageTeacherGetUseCase::class
);
$this->app->bind(
    App\Domain\HomePage\Services\IForHomePageTeacherGetService::class,
    App\Infra\HomePage\Services\ForHomePageTeacherGetService::class
);
$this->app->bind(
    App\Domain\HomePage\Repositories\IForHomePageTeacherGetRepository::class,
    App\Infra\HomePage\Repositories\ForHomePageTeacherGetRepository::class
);
$this->app->bind(
    App\Http\Responses\Api\V1\HomePage\IForHomePageTeacherGetResponse::class,
    App\Http\Responses\Api\V1\HomePage\ForHomePageTeacherGetResponse::class
);
```

**Just copy-paste!**

---

## Auto-Generated Route

The wizard also prints the route to add to `routes/api.php`:

```php
Route::get('/for-home-page-teacher-get', \App\Http\Controllers\Api\V1\HomePage\ForHomePageTeacherGetAction::class);
```

---

## TODO-Driven Development

Each generated file has marked **TODO comments** showing exactly what to fill in:

### Request
```php
class ForHomePageTeacherGetRequest extends FormRequest
{
    public function rules(): array
    {
        // TODO: Add your validation rules here
        return [];
    }
}
```

### Service
```php
class ForHomePageTeacherGetService implements IForHomePageTeacherGetService
{
    // TODO: Implement your business logic here
}
```

### Repository
```php
class ForHomePageTeacherGetRepository implements IForHomePageTeacherGetRepository
{
    // TODO: Implement Eloquent queries here
    // Example: return $this->model->where(...)->get();
}
```

### Output DTO
```php
class ForHomePageTeacherGetOutput
{
    // TODO: Define your properties and getters here
    // Example: public function __construct(public int $id, public string $name) {}
}
```

### Response
```php
class ForHomePageTeacherGetResponse implements IForHomePageTeacherGetResponse
{
    public function toArrayResponse(): array
    {
        // TODO: Map output DTO fields to API response format
        return [];
    }
}
```

---

## Usage Patterns

### Simple Feature (Most Cases)
```bash
php artisan ddd:make:feature ForCreateUser --folder=Users
```

**What it generates:**
- Request (with validation)
- Action
- UseCase
- Service
- Repository
- Output DTO
- Response

**Perfect for:** CRUD operations, simple business logic.

### Complex Feature (Multiple Repositories)
```bash
php artisan ddd:make:feature ForSubscriptionCheckout --folder=Billing
```

When prompted:
```
Will there be multiple Repositories? (yes/no)
> yes
  Repository name:
  > SubscriptionRepository
  Repository name:
  > PaymentRepository
  Repository name:
  [ENTER to finish]
```

**Result:** Both repositories generated with proper bindings.

### Feature with Entity & Model
```bash
php artisan ddd:make:feature ForCreateInvoice --folder=Invoicing
```

When prompted:
```
Is there a need for a domain Entity class? (yes/no)
> yes

Should an Eloquent Model be generated? (yes/no)
> yes
```

**Result:** Full infrastructure ready (Entity + Eloquent Model + Repository).

### Feature with Value Objects
```bash
php artisan ddd:make:feature ForTransferMoney --folder=Payments
```

When prompted:
```
Will this feature require Value Objects (VO)? (yes/no)
> yes
```

**Result:** Vo folder created with comment guidance.

---

## Non-Interactive Mode

For CI/CD or scripting, skip prompts:

```bash
php artisan ddd:make:feature ForCreateUser \
  --folder=Users \
  --no-interaction
```

Uses sensible defaults (no VOs, no multiple repos, with Request).

---

## Inline Options

Shorthand for all questions:

```bash
php artisan ddd:make:feature ForCreateUser \
  --folder=Users \
  --with-entity \
  --with-eloquent-model \
  --with-request \
  --value-objects
```

---

## After Generation: Next Steps

1. **Add bindings** (copy-paste from wizard output)
   ```php
   // In AppServiceProvider::register()
   $this->app->bind(...);
   ```

2. **Add route** (copy-paste from wizard output)
   ```php
   // In routes/api.php
   Route::get('/...' ...);
   ```

3. **Fill in TODOs**
   - Request: validation rules
   - Service: business logic
   - Repository: Eloquent queries
   - Output DTO: properties and getters
   - Response: response mapping

4. **Test**
   ```bash
   php artisan test
   ```

---

## Comparison: Wizard vs Granular

| Task | Wizard (`ddd:make:feature`) | Granular (`ddd:*` commands) |
|------|--------|----------|
| **Create a feature** | 2 minutes | 15 minutes |
| **Answer questions** | 10 questions upfront | N/A |
| **Customization** | Limited (preset combinations) | Full control |
| **Learning curve** | Beginner-friendly | Intermediate |
| **Incremental use** | Not ideal | Perfect |

**Use the wizard when:** Starting a new feature from scratch.  
**Use granular commands when:** Creating individual components or customizing heavily.

---

## Real-World Example

### Feature: Get User Profile with Address History

```bash
php artisan ddd:make:feature ForGetUserProfileWithAddressHistory --folder=UserManagement
```

Prompts:
```
Will the Service need an Input DTO? (yes/no)
> yes

Will there be multiple Repositories? (yes/no)
> yes
  Repository name:
  > UserRepository
  Repository name:
  > AddressRepository
  Repository name:
  [ENTER]

Is there a need for a domain Entity class? (yes/no)
> yes

Should an Eloquent Model be generated? (yes/no)
> yes
```

**Generated:**
- Request (input validation)
- Action (HTTP handler)
- UseCase (orchestration)
- Service (business logic: user + address history)
- 2 Repositories (User, Address)
- 2 Models (User, Address)
- 1 Entity (User domain entity)
- Input DTO (for service parameters)
- Output DTO (user + address array)
- Response (API format)
- Binding code (4 interfaces + implementations)
- Route suggestion

**Total time:** ~3 minutes  
**Manual steps:** Fill TODOs + add bindings + add route

---

## Key Takeaways

1. **Speed:** Generate 10+ files in 2-3 minutes
2. **Guidance:** TODO comments show exactly what to implement
3. **Bindings:** Auto-printed code for copy-paste
4. **Flexibility:** Answer questions to customize generation
5. **Routes:** Route code provided automatically
6. **Learning:** Perfect for teams new to DDD

This combines the **best of both worlds:**
- ✅ Speed of all-in-one generation (like Imran's maker)
- ✅ Flexibility of granular commands (like our existing generators)
- ✅ Comprehensive documentation (like our docs)
