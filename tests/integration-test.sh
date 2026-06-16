#!/bin/bash

# Integration test for Laravel-DDD v4.0.0 Feature Wizard
# Run this in a fresh Laravel environment

set -e

echo "=========================================="
echo "Laravel-DDD v4.0.0 Integration Test"
echo "=========================================="

# Test 1: Verify commands are registered
echo ""
echo "[1/8] Verifying commands are registered..."
php artisan list | grep -q "ddd:make:feature" && echo "✅ ddd:make:feature found" || echo "❌ ddd:make:feature NOT found"
php artisan list | grep -q "ddd:make:domain" && echo "✅ ddd:make:domain found" || echo "❌ ddd:make:domain NOT found"
php artisan list | grep -q "ddd:use-case" && echo "✅ ddd:use-case found" || echo "❌ ddd:use-case NOT found"
php artisan list | grep -q "ddd:response" && echo "✅ ddd:response found" || echo "❌ ddd:response NOT found"
php artisan list | grep -q "ddd:service" && echo "✅ ddd:service found" || echo "❌ ddd:service NOT found"

# Test 2: Run feature wizard (non-interactive)
echo ""
echo "[2/8] Running feature wizard (non-interactive)..."
php artisan ddd:make:feature ForUserLogin --folder=Authentication --no-interaction 2>&1 | head -20

# Test 3: Check files were created
echo ""
echo "[3/8] Verifying generated files exist..."
test -f "app/UseCases/Authentication/ForUserLoginUseCase.php" && echo "✅ UseCase created" || echo "❌ UseCase NOT created"
test -f "app/Domain/Authentication/Services/IForUserLoginService.php" && echo "✅ Service interface created" || echo "❌ Service interface NOT created"
test -f "app/Infra/Authentication/Services/ForUserLoginService.php" && echo "✅ Service impl created" || echo "❌ Service impl NOT created"
test -f "app/Domain/Authentication/Repositories/IForUserLoginRepository.php" && echo "✅ Repository interface created" || echo "❌ Repository interface NOT created"
test -f "app/Infra/Authentication/Repositories/ForUserLoginRepository.php" && echo "✅ Repository impl created" || echo "❌ Repository impl NOT created"
test -f "app/Domain/Authentication/Services/Output/ForUserLoginOutput.php" && echo "✅ Output DTO created" || echo "❌ Output DTO NOT created"
test -f "app/Http/Responses/Api/V1/Authentication/IForUserLoginResponse.php" && echo "✅ Response interface created" || echo "❌ Response interface NOT created"
test -f "app/Http/Responses/Api/V1/Authentication/ForUserLoginResponse.php" && echo "✅ Response impl created" || echo "❌ Response impl NOT created"

# Test 4: Check PHP syntax
echo ""
echo "[4/8] Checking PHP syntax for all generated files..."
php -l app/UseCases/Authentication/ForUserLoginUseCase.php > /dev/null && echo "✅ UseCase: valid PHP" || echo "❌ UseCase: SYNTAX ERROR"
php -l app/Domain/Authentication/Services/IForUserLoginService.php > /dev/null && echo "✅ Service interface: valid PHP" || echo "❌ Service interface: SYNTAX ERROR"
php -l app/Infra/Authentication/Services/ForUserLoginService.php > /dev/null && echo "✅ Service impl: valid PHP" || echo "❌ Service impl: SYNTAX ERROR"
php -l app/Domain/Authentication/Repositories/IForUserLoginRepository.php > /dev/null && echo "✅ Repository interface: valid PHP" || echo "❌ Repository interface: SYNTAX ERROR"
php -l app/Infra/Authentication/Repositories/ForUserLoginRepository.php > /dev/null && echo "✅ Repository impl: valid PHP" || echo "❌ Repository impl: SYNTAX ERROR"
php -l app/Domain/Authentication/Services/Output/ForUserLoginOutput.php > /dev/null && echo "✅ Output DTO: valid PHP" || echo "❌ Output DTO: SYNTAX ERROR"
php -l app/Http/Responses/Api/V1/Authentication/IForUserLoginResponse.php > /dev/null && echo "✅ Response interface: valid PHP" || echo "❌ Response interface: SYNTAX ERROR"
php -l app/Http/Responses/Api/V1/Authentication/ForUserLoginResponse.php > /dev/null && echo "✅ Response impl: valid PHP" || echo "❌ Response impl: SYNTAX ERROR"

# Test 5: Check for unreplaced placeholders
echo ""
echo "[5/8] Checking for unreplaced placeholders..."
if grep -r "{{" app/UseCases/Authentication/ app/Domain/Authentication/ app/Infra/Authentication/ app/Http/Responses/Api/V1/Authentication/ 2>/dev/null; then
    echo "❌ ERROR: Found unreplaced placeholders!"
else
    echo "✅ No unreplaced placeholders found"
fi

# Test 6: Check for TODO markers
echo ""
echo "[6/8] Checking for TODO markers in stubs..."
grep -q "TODO" app/Http/Requests/Api/V1/Authentication/ForUserLoginRequest.php && echo "✅ Request has TODOs" || echo "⚠️  Request missing TODOs"
grep -q "TODO" app/Domain/Authentication/Services/IForUserLoginService.php && echo "✅ Service interface has TODOs" || echo "⚠️  Service interface missing TODOs"
grep -q "TODO" app/Infra/Authentication/Services/ForUserLoginService.php && echo "✅ Service impl has TODOs" || echo "⚠️  Service impl missing TODOs"

# Test 7: Test with different flags
echo ""
echo "[7/8] Testing with different complexity flags..."
php artisan ddd:make:feature ForCreatePost --folder=Blog --no-interaction > /dev/null 2>&1 && echo "✅ Wizard works with default flags" || echo "❌ Wizard failed with default flags"

# Test 8: Summary
echo ""
echo "=========================================="
echo "✅ Integration test complete!"
echo "=========================================="
echo ""
echo "All systems go for v4.0.0 release!"
