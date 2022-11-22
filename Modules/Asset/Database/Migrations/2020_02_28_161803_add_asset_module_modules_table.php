<?php

use App\Module;
use App\ModuleSetting;
use App\Permission;
use Illuminate\Database\Migrations\Migration;

class AddAssetModuleModulesTable extends Migration
{


    public function up()
    {
        $module = Module::where('module_name', 'assets')->first();
        if (!$module) {
            $module = new Module();
            $module->module_name = 'assets';
            $module->save();
        }

        Permission::insert(
            [
                ['name' => 'add_asset', 'display_name' => 'Add Contract', 'module_id' => $module->id],
                ['name' => 'view_asset', 'display_name' => 'View Contract', 'module_id' => $module->id],
                ['name' => 'edit_asset', 'display_name' => 'Edit Contract', 'module_id' => $module->id],
                ['name' => 'delete_asset', 'display_name' => 'Delete Contract', 'module_id' => $module->id],
            ]
        );

        $module = new ModuleSetting();
        $module->type = 'admin';
        $module->module_name = 'assets';
        $module->status = 'active';
        $module->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }

}
