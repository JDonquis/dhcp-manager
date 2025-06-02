<?php

namespace Database\Seeders;

use App\Models\Network;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Network::create([
            'subnet' => '10.41.0.0',
            'netmask' => '255.255.252.0',
            'options' => [
                'option routers' => '10.41.1.2',
                'option subnet-mask' => '255.255.252.0',
                'option domain-name-servers' =>  '192.168.3.23, 8.8.8.8',
            ],
            'params' => [
                'ddns-update-style' => 'none',
                'authoritative' => '',
                'default-lease-time' => '21600',
                'max-lease-time' => '43200',
            ]
        ]);
    }
}
