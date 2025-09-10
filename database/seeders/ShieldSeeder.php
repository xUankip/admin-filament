<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[
          {
            "name": "super_admin",
            "guard_name": "web",
            "permissions": [
              "panel_user",
              "view_role","view_any_role","create_role","update_role","delete_role","delete_any_role",
              "view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user",
              "page_CDNSetting","page_EmailSetting","page_EmbedCodeWebsite","page_NotificationSetting","page_SocialSetting","page_UserAndRole"
            ]
          },
          {
            "name": "staff_admin",
            "guard_name": "web",
            "permissions": [
              "panel_user",
              "view_role","view_any_role","create_role","update_role","delete_role","delete_any_role",
              "view_user","view_any_user","create_user","update_user","delete_user","delete_any_user",
              "view_event","view_any_event","create_event","update_event","delete_event","delete_any_event","restore_event","restore_any_event","replicate_event","reorder_event","force_delete_event","force_delete_any_event",
              "view_category","view_any_category","create_category","update_category","delete_category","delete_any_category",
              "view_department","view_any_department","create_department","update_department","delete_department","delete_any_department",
              "view_registration","view_any_registration","update_registration","delete_registration","delete_any_registration",
              "view_attendance","view_any_attendance","delete_attendance","delete_any_attendance",
              "view_feedback","view_any_feedback","update_feedback","delete_feedback","delete_any_feedback",
              "view_certificate","view_any_certificate","create_certificate","update_certificate","delete_certificate","delete_any_certificate",
              "view_media","view_any_media","create_media","update_media","delete_media","delete_any_media",
              "page_CDNSetting","page_EmailSetting","page_EmbedCodeWebsite","page_NotificationSetting","page_SocialSetting","page_UserAndRole"
            ]
          },
          {
            "name": "staff_organizer",
            "guard_name": "web",
            "permissions": [
              "panel_user",
              "view_event","view_any_event","create_event","update_event",
              "view_registration","view_any_registration","update_registration",
              "view_attendance","view_any_attendance","delete_attendance","delete_any_attendance",
              "view_feedback","view_any_feedback","update_feedback",
              "view_certificate","view_any_certificate","create_certificate","update_certificate",
              "view_media","view_any_media","create_media","update_media",
              "view_category","view_any_category",
              "view_department","view_any_department"
            ]
          }
        ]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        // Ensure super_admin has ALL permissions
        /** @var Role $super */
        $super = Role::where('name', config('filament-shield.super_admin.name', 'super_admin'))->first();
        if ($super) {
            $super->syncPermissions(Permission::all());
        }

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
