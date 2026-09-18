<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
     
        $permissions = [
          
            'manage users',
          
            'view products',
            'create products',
            'update products',
            'delete products',
            'manage products',
            'manage categories',           
            'manage brands',
            'manage orders',
            'view own orders',
            'create orders',
            'manage coupons',
            'create coupons',
            'use coupon',
            'write review',
            'delete reviews',
            'create wishlist',
            'add a product to cart',
            'manage stock',
            'watch statistics of sells',
            'log in',
            'register',
            'change the account',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

       
        $admin = Role::firstOrCreate(['name' => 'admin']);  // الادمن 
        $admin->syncPermissions([
            'manage users',
            'manage products',
            'create products',
            'update products',
            'delete products',
            'manage categories',
            'manage brands',
            'manage orders',
            'manage coupons',
            'create coupons',
            'delete reviews',
            'manage stock',
            'watch statistics of sells',
        ]);

      
        $customer = Role::firstOrCreate(['name' => 'customer']); // العميل
        $customer->syncPermissions([
            'log in',
            'register',
            'change the account',
            'add a product to cart',
            'create orders',
            'view own orders',
            'write review',
            'create wishlist',
            'use coupon',
            'view products',
        ]);
    }
}