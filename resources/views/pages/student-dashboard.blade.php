@php
// Minimal Blade to consume API endpoints from web (to be wired with a controller or Livewire later)
@endphp
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Bảng điều khiển Sinh viên</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="font-semibold mb-2">Sự kiện sắp tới</h2>
            <p>Hiển thị danh sách sự kiện từ API /api/v1/events (filter từ hôm nay).</p>
        </div>
        <div>
            <h2 class="font-semibold mb-2">Sự kiện của tôi</h2>
            <p>Hiển thị đăng ký của tôi từ API /api/v1/me/registrations.</p>
        </div>
    </div>
</div>
