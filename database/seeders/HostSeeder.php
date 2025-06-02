<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hosts')->insert([
            
            [
                'name' => 'servidorhpprolaint',
                'mac' => '2C:59:E5:3B:42:3D',
                'ip' => '10.41.1.6',
                'department_id' => 1,
                'group_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'name' => 'informatica01',
                'mac' => '00:15:58:9F:78:E0',
                'ip' => '10.41.1.13',
                'department_id' => 2,
                'group_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'informatica02',
                'mac' => 'D8:EC:5E:78:2A:79',
                'ip' => '10.41.1.14',
                'department_id' => 2,
                'group_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'informatica03',
                'mac' => '00:25:22:99:7C:4F',
                'ip' => '10.41.1.15',
                'department_id' => 2,
                'group_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'informatica04',
                'mac' => '00:19:D1:75:01:48',
                'ip' => '10.41.1.16',
                'department_id' => 2,
                'group_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'informatica05',
                'mac' => '00:25:22:99:7C:6A',
                'ip' => '10.41.1.17',
                'department_id' => 2,
                'group_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'informatica06',
                'mac' => '00:25:22:99:7C:5F',
                'ip' => '10.41.1.18',
                'department_id' => 2,
                'group_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'informatica-laptop01',
                'mac' => 'C0:18:50:5A:13:0D',
                'ip' => '10.41.1.19',
                'department_id' => 2,
                'group_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'informatica-laptop2',
                'mac' => '18:03:73:BF:95:E7',
                'ip' => '10.41.1.20',
                'department_id' => 2,
                'group_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

    }
}
