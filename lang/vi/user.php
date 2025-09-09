<?php
return [
    'group' => 'Cài Đặt',
    'resource' => [
        'id' => 'ID',
        'single' => 'Người Dùng',
        'email_verified_at' => 'Email Đã Xác Minh',
        'created_at' => 'Ngày Tạo',
        'updated_at' => 'Ngày Cập Nhật',
        'verified' => 'Đã Xác Minh',
        'unverified' => 'Chưa Xác Minh',
        'name' => 'Tên',
        'email' => 'Email',
        'password' => 'Mật Khẩu',
        'roles' => 'Vai Trò',
        'teams' => 'Nhóm',
        'label' => 'Người Dùng',
        'title' => [
            'show' => 'Xem Người Dùng',
            'delete' => 'Xóa Người Dùng',
            'impersonate' => 'Mạo Danh Người Dùng',
            'create' => 'Tạo Người Dùng',
            'edit' => 'Chỉnh Sửa Người Dùng',
            'list' => 'Danh Sách Người Dùng',
            'home' => 'Người Dùng',
        ],
        'notificaitons' => [
            'last' => [
                'title' => 'Lỗi',
                'body' => 'Bạn không thể xóa người dùng cuối cùng',
            ],
            'self' => [
                'title' => 'Lỗi',
                'body' => 'Bạn không thể xóa chính mình',
            ],
        ],
    ],
    'bulk' => [
        'teams' => 'Cập Nhật Nhóm',
        'roles' => 'Cập Nhật Vai Trò',
    ],
    'team' => [
        'title' => 'Nhóm',
        'single' => 'Nhóm',
        'columns' => [
            'avatar' => 'Hình Đại Diện',
            'name' => 'Tên',
            'owner' => 'Chủ Sở Hữu',
            'personal_team' => 'Nhóm Cá Nhân',
        ],
    ],
];
