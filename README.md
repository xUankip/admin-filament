### Cài đặt nhanh

1) Cài JS deps:

```
bun i
```

2) Cài PHP deps:

```
composer i
```

3) Migrate DB:

```
php artisan migrate --seed
```

4) Khởi tạo:

```
sh init.sh
```

### Kiến trúc tổng thể

- BE: Laravel 11 + Breeze + Sanctum, Spatie Permission, Livewire/Filament 3 (Admin/Organizer)
- FE Web: Blade + Livewire (Public + Student mini-dashboard)
- Mobile: Flutter (Android 9+), gọi API Sanctum
- Tìm kiếm: Scout + Meilisearch (tùy chọn)
- Storage: S3-compatible; Queue: Redis + Horizon

### CSDL chính (đã tạo migrations)

`departments`, `categories`, `events`, `registrations`, `attendance`, `feedback`, `certificates`, `media_gallery`, `favorites`, `user_notifications`, `user_details`, bổ sung user: `status`, `role_hint`, `org_domain`.

Indexes: `events(slug unique)`, `events(start_at,end_at,status)`, `registrations(event_id,user_id unique)`, `certificates(certificate_id unique)`.

### API (v1, Sanctum)

- Auth: POST /api/v1/auth/register, POST /api/v1/auth/login, GET /api/v1/auth/me, POST /api/v1/auth/logout
- Events: GET /api/v1/events, GET /api/v1/events/{id}
- Registrations: GET /api/v1/me/registrations, POST /api/v1/events/{id}/register, DELETE /api/v1/events/{id}/register
- Certificates: GET /api/v1/me/certificates
- Feedback: POST /api/v1/events/{id}/feedback
- Media: GET /api/v1/media, POST /api/v1/media/{id}/favorite
- Notifications: GET /api/v1/notifications, POST /api/v1/notifications/{id}/read

### Roles & xác thực

- Roles: student_viewer, student_participant, staff_organizer, staff_admin (+ super_admin)
- User phải verify email trước khi đăng ký sự kiện

### Cấu hình email domain Staff

- Vào Filament > Settings > Social, nhập regex tại trường “Staff Email Regex” (ví dụ: \@college\.edu\.vn$)
