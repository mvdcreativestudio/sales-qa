<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:modules-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea permisos basados en los módulos del CRM - MVD';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modulesJson = [
            'menu' => [
                [
                    'slug' => 'dashboard',
                    'view_all' => false,
                ],
                [
                    'slug' => 'raw-materials',
                    'view_all' => true,
                ],
                [
                    'slug' => 'suppliers',
                    'view_all' => true,
                ],
                [
                    'slug' => 'supplier-orders',
                    'view_all' => true,
                ],
                [
                    'slug' => 'stock',
                    'view_all' => false,
                ],
                [
                    'slug' => 'accounting',
                    'submenus' => [
                        'invoices',
                        'receipts',
                        'entries'
                    ],
                    'view_all' => false,
                ],
                [
                    'slug' => 'clients',
                    'view_all' => false,
                ],
                [
                    'slug' => 'ecommerce',
                    'submenus' => [
                        'orders',
                        'products',
                        'product-categories',
                        'settings',
                        'product-flavors'
                    ],
                    'view_all' => false,
                ],
                [
                    'slug' => 'productions',
                    'view_all' => true,
                ],
                [
                  'slug' => 'bypass_raw_material_check',
                  'view_all' => false,
                ],
                [
                    'slug' => 'marketing',
                    'submenus' => [
                        'coupons',
                        'settings'
                    ],
                    'view_all' => false,
                ],
                [
                    'slug' => 'omnichannel',
                    'submenus' => [
                        'chats',
                        'settings'
                    ],
                    'view_all' => false,
                ],
                [
                    'slug' => 'datacenter',
                    'view_all' => false,
                ],
                [
                    'slug' => 'crm',
                    'view_all' => false,
                ],
                [
                    'slug' => 'stores',
                    'view_all' => false,
                ],
                [
                    'slug' => 'roles',
                    'view_all' => false,
                ],
                [
                    'slug' => 'company_settings',
                    'view_all' => false,
                ],
                [
                    'slug' => 'email_templates',
                    'view_all' => false,
                ],
            ]
        ];

        // Asegurar que el rol de administrador existe
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);

        foreach ($modulesJson['menu'] as $module) {
            $this->createPermission($module['slug'], $module['view_all'], $adminRole);

            if (array_key_exists('submenus', $module)) {
                foreach ($module['submenus'] as $submenuSlug) {
                    $this->createPermission($submenuSlug, false, $adminRole);
                }
            }
        }

        $this->info('Todos los permisos han sido creados y asignados al rol Administrador.');
    }

    private function createPermission($slug, $viewAll, $adminRole)
    {
        $permissionName = 'access_' . $slug;
        $permission = Permission::firstOrCreate(['name' => $permissionName]);
        $adminRole->givePermissionTo($permission);
        $this->info('Permiso creado y asignado al rol Administrador: ' . $permissionName);

        if ($viewAll) {
            $viewAllPermissionName = 'view_all_' . $slug;
            $viewAllPermission = Permission::firstOrCreate(['name' => $viewAllPermissionName]);
            $adminRole->givePermissionTo($viewAllPermission);
            $this->info('Permiso de vista total creado y asignado al rol Administrador: ' . $viewAllPermissionName);
        }
    }
}
