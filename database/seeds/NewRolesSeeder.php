<?php

use Database\traits\TruncateTable;
use Database\traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewRolesSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $this->disableForeignKeys();
        $this->truncate('roles');

        $roles = [
            ['name' => 'system_admin'],
            ['name' => 'community_manager'],
            ['name' => 'company_admin'],
            ['name' => 'company_user']
        ];

        DB::table('roles')->insert($roles);

        $this->enableForeignKeys();
    }
}
