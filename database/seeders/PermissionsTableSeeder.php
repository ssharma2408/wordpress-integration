<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'customer_create',
            ],
            [
                'id'    => 18,
                'title' => 'customer_edit',
            ],
            [
                'id'    => 19,
                'title' => 'customer_show',
            ],
            [
                'id'    => 20,
                'title' => 'customer_delete',
            ],
            [
                'id'    => 21,
                'title' => 'customer_access',
            ],
            [
                'id'    => 22,
                'title' => 'customer_role_create',
            ],
            [
                'id'    => 23,
                'title' => 'customer_role_edit',
            ],
            [
                'id'    => 24,
                'title' => 'customer_role_show',
            ],
            [
                'id'    => 25,
                'title' => 'customer_role_delete',
            ],
            [
                'id'    => 26,
                'title' => 'customer_role_access',
            ],
            [
                'id'    => 27,
                'title' => 'order_create',
            ],
            [
                'id'    => 28,
                'title' => 'order_edit',
            ],
            [
                'id'    => 29,
                'title' => 'order_show',
            ],
            [
                'id'    => 30,
                'title' => 'order_delete',
            ],
            [
                'id'    => 31,
                'title' => 'order_access',
            ],
            [
                'id'    => 32,
                'title' => 'customer_management_access',
            ],
            [
                'id'    => 33,
                'title' => 'order_management_access',
            ],
            [
                'id'    => 34,
                'title' => 'document_create',
            ],
            [
                'id'    => 35,
                'title' => 'document_edit',
            ],
            [
                'id'    => 36,
                'title' => 'document_show',
            ],
            [
                'id'    => 37,
                'title' => 'document_delete',
            ],
            [
                'id'    => 38,
                'title' => 'document_access',
            ],
            [
                'id'    => 39,
                'title' => 'document_version_create',
            ],
            [
                'id'    => 40,
                'title' => 'document_version_edit',
            ],
            [
                'id'    => 41,
                'title' => 'document_version_show',
            ],
            [
                'id'    => 42,
                'title' => 'document_version_delete',
            ],
            [
                'id'    => 43,
                'title' => 'document_version_access',
            ],
            [
                'id'    => 44,
                'title' => 'client_create',
            ],
            [
                'id'    => 45,
                'title' => 'client_edit',
            ],
            [
                'id'    => 46,
                'title' => 'client_show',
            ],
            [
                'id'    => 47,
                'title' => 'client_delete',
            ],
            [
                'id'    => 48,
                'title' => 'client_access',
            ],
            [
                'id'    => 49,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
