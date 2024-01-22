<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Dashboard',
            'Machine Dashboard',
            'Production Dashboard',
            'Role List',
            'Role Create',
            'Role Edit',
            'Role Delete',
            'Batch List',
            'Batch Create',
            'Batch Edit',
            'Batch Delete',
            'Category List',
            'Category Create',
            'Category Edit',
            'Category Delete',
            'Drilling List',
            'Drilling Create',
            'Drilling Edit',
            'Drilling Delete',
            'Final Checking List',
            'Final Checking Create',
            'Final Checking Edit',
            'Final Checking Delete',
            'Grinding List',
            'Grinding Create',
            'Grinding Edit',
            'Grinding Delete',
            'Material Stock',
            'User List',
            'User Create',
            'User Edit',
            'User Delete',
            'Machine List',
            'Machine Create',
            'Machine Edit',
            'Machine Delete',
            'Material List',
            'Material Create',
            'Material Edit',
            'Material Delete',
            'Material In List',
            'Material In Create',
            'Material In Edit',
            'Material In Delete',
            'Material Out List',
            'Material Out Create',
            'Material Out Edit',
            'Material Out Delete',
            'Report',
            'Pellete List',
            'Pellete Create',
            'Pellete Edit',
            'Pellete Delete',
            'Parameter List',
            'Parameter Create',
            'Parameter Edit',
            'Parameter Delete',
            'Press List',
            'Press Create',
            'Press Edit',
            'Press Delete',
            'Product List',
            'Product Create',
            'Product Edit',
            'Product Delete',
            'Production Order List',
            'Production Order Create',
            'Production Order Edit',
            'Production Order Delete',
            'production-dashboard',
            'Purchase Order List',
            'Purchase Order Create',
            'Purchase Order Edit',
            'Purchase Order Delete',
            'Shotblast List',
            'Shotblast Create',
            'Shotblast Edit',
            'Shotblast Delete',
            'Supplier List',
            'Supplier Create',
            'Supplier Edit',
            'Supplier Delete',
            'UOM List',
            'UOM Create',
            'UOM Edit',
            'UOM Delete',
            'WareHouse In List',
            'WareHouse In Create',
            'WareHouse In Edit',
            'WareHouse In Delete',
            'WareHouse Out List',
            'WareHouse Out Create',
            'WareHouse Out Edit',
            'WareHouse Out Delete',
            'Stock',
            'Check Pellete',
            'Profile View',
            'Profile Update'
         ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }
    }
}
