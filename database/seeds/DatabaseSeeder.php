<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('coordinations')->insert([
            'id'    => 1,
            'name' => 'CSOL 1',
        ]);
        DB::table('coordinations')->insert([
            'id'    => 2,
            'name' => 'CSOL 2',
        ]);
        DB::table('users')->insert([
            'id'    => 1,
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' =>  '$2y$10$UWUH4A3MAnquAczafBcb..X9JYm6pzsuFpwdDsclV4NMO.uspgK9u',
            'coordination_id' => 2,
            'admin' => true
        ]);
        DB::table('coordinations')->where('id',2)
            ->update([
                'coordinator_id' => 1
            ]);
    }
}
