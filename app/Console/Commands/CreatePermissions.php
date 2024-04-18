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
                        'marketing',
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
                ]
            ]
        ];

         // Asegurar que el rol de administrador existe
         $adminRole = Role::firstOrCreate(['name' => 'Administrador']);

        foreach ($modulesJson['menu'] as $module) {
            $this->createPermission($module['slug'], $module['view_all']);

            if (array_key_exists('submenus', $module)) {
                foreach ($module['submenus'] as $submenuSlug) {
                    $this->createPermission($submenuSlug, false);
                }
            }
        }

        $this->info('Todos los permisos han sido creados.');
    }

    private function createPermission($slug, $viewAll)
    {
        $permissionName = 'access_' . $slug;
        if (!Permission::where('name', $permissionName)->exists()) {
            Permission::create(['name' => $permissionName]);
            $this->info('Permiso creado: ' . $permissionName);
        } else {
            $this->info('El permiso ya existe: ' . $permissionName);
        }

        if ($viewAll) {
            $viewAllPermissionName = 'view_all_' . $slug;
            if (!Permission::where('name', $viewAllPermissionName)->exists()) {
                Permission::create(['name' => $viewAllPermissionName]);
                $this->info('Permiso de vista total creado: ' . $viewAllPermissionName);
            } else {
                $this->info('El permiso de vista total ya existe: ' . $viewAllPermissionName);
            }
        }
    }
}
