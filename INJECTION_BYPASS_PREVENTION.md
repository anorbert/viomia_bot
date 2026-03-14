# Injection & Bypass Prevention - Technical Reference

**Date:** March 14, 2026  
**Document**: Injection Prevention & Attack Bypass Mitigation

---

## 🛡️ SQL Injection Prevention

### How We Prevent SQL Injection

#### 1. Laravel Eloquent ORM (Automatic Protection)
```php
// ✅ SAFE: Eloquent automatically parameterizes
$user = User::where('phone_number', $request->phone)->first();
// SQL: SELECT * FROM users WHERE phone_number = ? (with bound parameter)

// ❌ DANGEROUS: Raw SQL concatenation
$user = User::whereRaw("phone_number = '{$phone}'")->first();
// Vulnerable to: ' OR '1'='1
```

#### 2. Query Builder with Bindings
```php
// ✅ SAFE: Bindings are escaped
DB::table('users')
    ->where('email', $email)
    ->first();

// ✅ SAFE: Raw queries with bindings
DB::select('SELECT * FROM users WHERE email = ?', [$email]);

// ❌ DANGEROUS: String interpolation
DB::select("SELECT * FROM users WHERE email = '$email'");
```

#### 3. Parameterized Insert/Update
```php
// ✅ SAFE: Mass assignment safe
User::create([
    'name' => $request->name,
    'email' => $request->email,
]);

// ✅ SAFE: Update with input
$user->update([
    'bio' => $request->bio,
]);

// ❌ DANGEROUS: Direct SQL injection
User::create(DB::raw("VALUES (" . $request->name . ")"));
```

### SQL Injection Attack Examples (All Prevented)

| Attack Type | Example | Prevention |
|-------------|---------|-----------|
| **OR-based bypass** | `' OR '1'='1` | Parameterized queries |
| **Union-based** | `' UNION SELECT password FROM users` | Query builder constraints |
| **Stacked queries** | `'; DROP TABLE users;--` | No raw SQL execution |
| **Time-based blind** | `'; WAITFOR DELAY '00:00:05'--` | Parameterized queries |
| **Boolean-based blind** | `' AND 1=1` | Parameterized queries |

---

## 🛡️ Authorization Bypass Prevention

### Ownership Verification (Critical)

#### Pattern 1: Route Model Binding (Recommended)
```php
// ✅ SAFE: Laravel route model binding with ownership check
Route::get('/profile/{user}', [ProfileController::class, 'show'])
    ->middleware('auth')
    ->middleware(function ($request, $next) {
        if ($request->user()->id !== $request->route('user')->id) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    });
```

#### Pattern 2: Manual Verification (Current Implementation)
```php
// ✅ SAFE: Manual check in controller
public function edit(string $id)
{
    $user = Auth::user();
    
    // Convert to int to prevent type confusion
    if ($user->id !== (int)$id) {
        abort(403, 'Unauthorized access');
    }
    
    return view('users.profile.edit', compact('user'));
}

// ❌ DANGEROUS: No verification
public function edit(string $id)
{
    return view('users.profile.edit', ['user' => User::find($id)]);
}
```

#### Pattern 3: Query Scope (Most Secure)
```php
// ✅ BEST: Scope queries to authorized user
public function edit()
{
    $user = Auth::user();  // Only current user's profile
    return view('users.profile.edit', compact('user'));
}

// Uses named route parameters
Route::get('/profile/{user}/edit', [ProfileController::class, 'edit'])
    ->middleware('auth')
    ->where('user', auth()->id());  // Only allows authenticated user's ID
```

### Authorization Bypass Attack Examples (All Prevented)

| Attack Type | Example | Prevention |
|-------------|---------|-----------|
| **Direct object reference** | `/profile/2` (accessing user 2 as user 1) | Ownership verification |
| **ID parameter tampering** | `/profile?user_id=999` | Route binding + auth check |
| **Session hijacking** | Stealing session cookie | HttpOnly + Secure flags |
| **Token reuse** | Old CSRF token | Token rotation |
| **Role escalation** | Changing `role=user` to `role=admin` | Server-side role storage |

---

## 🛡️ CSRF Prevention

### Cross-Site Request Forgery Protection

#### Token Generation & Validation
```php
// ✅ SAFE: Use @csrf in all forms
<form method="POST" action="/user/profile/update">
    @csrf  <!-- Blade generates <input name="_token"> -->
    <!-- form fields -->
</form>

// Tokens automatically:
// - Generated unique per session
// - Validated on POST/PUT/PATCH/DELETE
// - Regenerated after each request
// - HttpOnly (not accessible to JavaScript)

// ❌ DANGEROUS: No CSRF token
<form method="POST" action="/user/profile/update">
    <!-- Missing @csrf = VULNERABLE TO CSRF -->
</form>
```

#### API CSRF Protection
```php
// ✅ SAFE: For API requests (no cookies)
// Use Bearer token + SameSite=Strict cookies

// Headers header sent by JavaScript:
Authorization: Bearer eyJhbGciOiJIUzI1...

// ❌ DANGEROUS: API without token verification
// If API relies only on cookies, vulnerable to CSRF
```

---

## 🛡️ XSS (Cross-Site Scripting) Prevention

### Automatic Output Escaping

#### Blade Template Auto-Escaping
```php
// ✅ SAFE: Auto-escaped (default)
{{ $user->name }}
<!-- Input: <script>alert('XSS')</script> -->
<!-- Output: &lt;script&gt;alert('XSS')&lt;/script&gt; -->

// ✅ SAFE: Explicitly escaped
{!! htmlspecialchars($user->name) !!}

// ❌ DANGEROUS: Unescaped (rare, explicit)
{!! $user->bio !!}  <!-- Only if content is truly trusted -->
```

#### Mass Assignment Protection
```php
// ✅ SAFE: Whitelist allowed fields
protected $fillable = [
    'name',
    'email',
    'password',
    'bio',
];

// Input: name=John&role=admin
// Result: Only 'name' is mass-assigned, 'role' ignored

// ❌ DANGEROUS: Allow all fields
protected $guarded = [];  // Never use this!
```

#### XSS Attack Examples (All Prevented)

| Attack Type | Payload | Prevention |
|-------------|---------|-----------|
| **Stored XSS** | `<img src=x onerror=alert('XSS')>` | Blade escaping |
| **Reflected XSS** | `?search=<script>...` | Auto-escaping output |
| **DOM XSS** | `document.innerHTML = user_input` | Vue/React escaping |
| **Event handler** | `<div onclick=alert('XSS')>` | Blade escaping |
| **Data URI** | `<img src=data:text/html,...>` | Escaping |

---

## 🛡️ Password Security

### Secure Password Handling

#### Hashing & Verification
```php
// ✅ SECURE: Bcrypt hashing
Hash::make($password)
// Output: $2y$12$...salt...hash... (never reversible)

// ✅ SECURE: Verification
Hash::check($input_password, $hashed_password)
// Applies same algorithm and compares results

// ❌ DANGEROUS: Plain text storage
$user->password = $request->password;

// ❌ DANGEROUS: Weak hashing
md5($password)  // Or sha1()
// These are too fast and easily brute-forced
```

#### Password Change Validation
```php
// ✅ SECURE: Verify current password before change
$request->validate([
    'current_password' => 'required|current_password',  // ← Built-in
    'password' => 'required|min:8|confirmed',
]);

// current_password rule checks:
// Hash::check($input, Auth::user()->password)

// ❌ DANGEROUS: Skip current password check
// Allows attackers to change password after session compromise
```

---

## 🛡️ Input Validation

### Type & Format Validation

#### Laravel Validation Rules
```php
// ✅ SAFE: Validate before using input
$validated = $request->validate([
    'email' => 'required|email|unique:users,email,' . $user->id,
    'phone' => 'required|string|max:20',
    'name' => 'required|string|max:255',
    'bio' => 'nullable|string|max:500',
    'profile_photo' => 'nullable|image|mimes:jpeg,png|max:2048',
]);

// Validation rules prevent:
// - Invalid email format
// - Type mismatches
// - Buffer overflows (max length)
// - Malicious file uploads

// ❌ DANGEROUS: No validation
$user->update($request->all());  // Never!
```

#### Type Casting
```php
// ✅ SAFE: Explicit type conversion
$id = (int)$request->id;  // Convert to integer
if ($user->id !== $id) {
    abort(403);
}

// Prevents type confusion
// Comparison protects against: '1' === 1 (true in PHP)

// ❌ DANGEROUS: Loose comparison
if ($user->id == $request->id) {  // Could be true for wrong types
    // Vulnerable to bypass
}
```

---

## 🛡️ Session & Cookie Security

### Configuration
```php
// config/session.php

'lifetime' => 120,           // 2 hours
'http_only' => true,         // Prevent JS access (XSS protection)
'secure' => env('SECURE_COOKIES', true),  // HTTPS only
'same_site' => 'lax',         // CSRF protection

// ✅ SECURE: HttpOnly prevents document.cookie access
// Attacker can't steal session via XSS

// ✅ SECURE: Secure flag requires HTTPS
// Cookie only sent over encrypted connection

// ✅ SECURE: SameSite prevents cross-site requests
// Must be same site to include cookies
```

---

## 🛡️ File Upload Security

### Validation
```php
// ✅ SAFE: Comprehensive file validation
$file = $request->validate([
    'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
]);

// Checks:
// - File exists
// - MIME type matches whitelist
// - Size under limit
// - Image dimensions valid (for images)

// ❌ DANGEROUS: No validation
$file = $request->file('photo');  // Could be executable!

// ❌ DANGEROUS: Only check extension
if (pathinfo($file, PATHINFO_EXTENSION) === 'jpg') {
    // Could be .jpg.php or fake extension
}
```

### Storage
```php
// ✅ SAFE: Store outside webroot
$path = $request->file('photo')->store('profile-photos', 'public');
// Stored in: storage/app/public/profile-photos/

// ✅ SAFE: Don't use user input as filename
$filename = $file->hashName();  // Random hash name

// ❌ DANGEROUS: Store in webroot
// storage/uploads/user_input.php → Executable!

// ❌ DANGEROUS: Use user-supplied filename
// storage/avatars/../../config/app.php  (Path traversal)
```

---

## 🛡️ API Security

### API Key Validation
```php
// ✅ SAFE: Validate and rate limit
public function handle(Request $request, $next)
{
    $key = $request->header('X-API-Key');
    
    if (!$key || !ApiKey::where('key', $key)->active()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    // Rate limiting
    if (Cache::increment("api-{$key}", 1, 60) > 100) {
        return response()->json(['error' => 'Rate limited'], 429);
    }
    
    return $next($request);
}

// ❌ DANGEROUS: No API key verification
// Anyone can call the API endpoint
```

---

## 📋 Attack Prevention Summary Table

| Attack Vector | Prevention Method | Implementation |
|---------------|-------------------|-----------------|
| SQL Injection | Parameterized queries | Laravel ORM |
| Authorization Bypass | Ownership verification | Controller check |
| CSRF | CSRF tokens | @csrf in forms |
| XSS | Auto-escaping | Blade templates |
| Password attacks | Bcrypt + verification | Hash::make() |
| Brute force | Rate limiting | ⚠️ TODO |
| Session hijacking | HttpOnly + Secure | config/session.php |
| File upload RCE | MIME validation | File validation rules |
| Path traversal | Safe storage | storage/app/ outside webroot |
| Type confusion | Type casting | (int) conversion |

---

## ✅ Verification Commands

```bash
# Check for hardcoded credentials
grep -r "password\|secret\|key" --include="*.php" app/

# Find potential SQL injection points
grep -r "whereRaw\|DB::raw" --include="*.php" app/

# Verify CSRF tokens in forms
grep -r "@csrf" resources/views/

# Check for unescaped output
grep -r "{!!" resources/views/ | grep -v "trusted"

# List all routes
php artisan route:list

# Check middleware on protected routes
php artisan route:list --path=user
```

---

## 🚨 Report Security Issues

**Email:** security@viomia.com  
**WhatsApp:** 0787373722  
**Severity Levels:**
- 🔴 Critical: Immediate exploit possible
- 🟠 High: Complex exploitation required
- 🟡 Medium: Requires user interaction
- 🟢 Low: Limited impact

---

**Last Updated:** March 14, 2026  
**Next Review:** June 14, 2026
