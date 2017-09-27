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
            'name' => 'Diógenes Ribeiro Duarte',
            'email' => 'diogenes.duarte@prodeb.ba.gov.br',
            'password' =>  '$2y$10$2P.o3KTKpfUZ27UON852ferf2B.p09VXbV4l0IqhfdA98w2TxugrC',
            'remember_token' => 'BFlnVcX9G8MeLTxgK3Hgnzam3dFcBkseMQKblhz6tOaLN4q8qfINi9vR7aSR',
            'coordination_id' => 2
        ]);
    }
}
