# User Management Backend Documentation

## Overview
Complete backend implementation for user management admin panel with comprehensive validation, authorization, logging, and error handling.

## Architecture

### Controllers
- **UserController** (`app/Http/Controllers/Admin/UserController.php`)
  - `index()` - List all users with filtering, searching, and pagination
  - `show()` - Display detailed information about a specific user
  - `updateStatus()` - Change user status (active/blocked)
  - `destroy()` - Delete a user with cascade delete

### Form Requests (Validation)
1. **FilterUsersRequest** (`app/Http/Requests/FilterUsersRequest.php`)
   - Validates filter parameters (role, status, search)
   - Rate limiting and input sanitization
   - Custom error messages in Vietnamese

2. **UpdateUserStatusRequest** (`app/Http/Requests/UpdateUserStatusRequest.php`)
   - Validates status change input
   - Ensures only valid statuses (active/blocked)
   - Authorization checks

### Services
- **UserManagementService** (`app/Services/UserManagementService.php`)
  - `canModifyUser()` - Check if admin can modify a user
  - `canDeleteUser()` - Check if admin can delete a user
  - `canChangeUserStatus()` - Check if admin can change user status
  - `getUserStatistics()` - Get user system statistics
  - `formatUserInfo()` - Format user data for logging

### Resources
- **UserResource** (`app/Http/Resources/UserResource.php`)
  - JSON serialization for users
  - Useful for API endpoints (future implementation)

### Models
- **User** - Main user model with relationships
  - Relations: enrollments, courses, provider
  - Helper methods: isAdmin(), isProvider(), isUser()

- **Provider** - Provider profile with methods
  - Relations: user, courses
  - Helper methods: isApproved(), isPending()

### Exceptions
- **UnauthorizedActionException** (`app/Exceptions/UnauthorizedActionException.php`)
  - Custom exception for authorization failures
  - Returns 403 HTTP status

### Traits
- **LogsAdminActions** (`app/Traits/LogsAdminActions.php`)
  - Centralized logging for admin actions
  - Logs admin ID, email, IP address, user agent, timestamp
  - Three levels: info, warning, error

## Features

### 1. User Listing & Filtering
```php
// Filter by role
GET /admin/users?role=provider

// Filter by status  
GET /admin/users?status=blocked

// Search by username, email, or name
GET /admin/users?search=john

// Pagination
GET /admin/users?page=2

// Combine filters
GET /admin/users?role=user&status=active&search=student
```

**Logging:**
- Logs: Admin ID, email, number of filters applied, total results

### 2. View User Details
```php
GET /admin/users/{id}
```

**Features:**
- User avatar display
- Full profile information
- Email verification status
- Provider profile (if applicable)
- List of enrolled courses
- Related data eager loading

**Logging:**
- Logs: Admin ID, target user ID, username, and role

### 3. Update User Status
```php
POST /admin/users/{id}/status
Body: {status: "active" | "blocked"}
```

**Validations:**
- Admin cannot block their own account
- Admin cannot block other admin accounts
- Only valid statuses: active, blocked

**Error Handling:**
- Prevents self-blocking
- Prevents admin-blocking
- Comprehensive error messages

**Logging:**
- Logs: Old status, new status, target user, admin details
- Level: INFO for successful changes

### 4. Delete User
```php
DELETE /admin/users/{id}
```

**Protections:**
- Cannot delete self
- Cannot delete other admins
- Uses database transaction for data integrity
- Cascades delete: enrollments and provider profiles

**Features:**
- Related data cleanup
- Transaction safety
- Detailed logging with email and role

**Logging:**
- Level: WARNING (significant action)
- Logs: Deleted user details for audit trail

## Security Features

### 1. Authorization
- AdminMiddleware verification on all routes
- Per-action authorization checks
- Cannot modify/delete admin accounts
- Cannot self-modify/delete

### 2. Input Validation
- FormRequest validation
- Type checking
- Max length constraints
- Enumeration validation

### 3. Data Protection
- Database transactions for delete operations
- Cascading deletes to prevent orphaned records
- Password hashing (inherited from User model)

### 4. Audit Logging
- All admin actions logged with:
  - Admin ID and email
  - Target user information
  - Action details (old/new values)
  - IP address
  - User agent
  - Timestamp

### 5. Error Handling
- Try-catch blocks with detailed error logging
- User-friendly error messages
- Stack traces in logs for debugging

## Database Operations

### Transactions
Delete operations use database transactions:
```php
DB::transaction(function () use ($user) {
    // Delete enrollments
    $user->enrollments()->delete();
    
    // Delete provider profile
    if ($user->provider) {
        $user->provider->delete();
    }
    
    // Delete user
    $user->delete();
});
```

### Eager Loading
Relations are eagerly loaded to prevent N+1 queries:
```php
$user->load('courses', 'provider');
```

## Response & Messaging

### Success Messages
- Status update: "Đã [Khóa/Mở khóa] tài khoản người dùng {username} thành công!"
- User delete: "Đã xóa người dùng {username} thành công!"

### Error Messages
- Self-modification: "Bạn không thể... chính tài khoản của mình!"
- Admin-modification: "Không thể... tài khoản admin!"
- System errors: "Có lỗi xảy ra khi..."

## Logging Examples

### User List Access
```
{
  "admin_id": 1,
  "admin_email": "admin@example.com",
  "filters_applied": 2,
  "results_count": 45
}
```

### Status Change
```
{
  "admin_id": 1,
  "admin_email": "admin@example.com",
  "target_user_id": 5,
  "target_username": "john_doe",
  "target_role": "user",
  "old_status": "active",
  "new_status": "blocked"
}
```

### User Deletion
```
{
  "admin_id": 1,
  "admin_email": "admin@example.com",
  "deleted_user_id": 8,
  "deleted_username": "spam_user",
  "deleted_user_email": "spam@example.com",
  "deleted_user_role": "provider"
}
```

## Usage Example

### In Controller
```php
public function example(FilterUsersRequest $request)
{
    $validated = $request->validated();
    
    // Use validated data...
    
    // Log action
    Log::info('Action description', [
        'admin_id' => auth()->id(),
        'additional_data' => 'value'
    ]);
}
```

### Using Service
```php
$canDelete = UserManagementService::canDeleteUser($admin, $targetUser);

if (!$canDelete['allowed']) {
    return redirect()->back()->with('error', $canDelete['message']);
}
```

## Future Enhancements
- Rate limiting middleware
- Soft deletes for user recovery
- Bulk operations (bulk status change, bulk delete)
- Export user data (CSV/Excel)
- User role change functionality
- Email notifications for status changes
- API endpoints with pagination
- Advanced search/filtering UI
- User activity tracking

## Testing
Run the following to verify functionality:
```bash
# Test authorization
curl -H "Authorization: Bearer $TOKEN" http://localhost/admin/users

# Test filtering
curl http://localhost/admin/users?role=provider&status=active

# Test user creation
curl -X POST http://localhost/admin/users/{id}/status \
  -d "status=blocked"
```

## Related Files
- Frontend: `resources/views/admin/users/index.blade.php`
- Frontend: `resources/views/admin/users/show.blade.php`
- Routes: `routes/web.php` (admin.users.*)
- Middleware: `app/Http/Middleware/AdminMiddleware.php`
