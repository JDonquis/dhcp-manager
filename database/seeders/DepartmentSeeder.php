<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sección 1: Servidores
        $servidores = Department::create([
            'name' => 'Servidores',
            'ip_range_start' => '10.41.1.3',
            'ip_range_end' => '10.41.1.12',
        ]);

        Group::create([
            'name' => 'ssf server',
            'department_id' => $servidores->id,
        ]);

        // Sección 2: Oficina de sistemas y tecnología de la información
        $sistemas = Department::create([
            'name' => 'Oficina de sistemas y tecnología de la información',
            'ip_range_start' => '10.41.1.13',
            'ip_range_end' => '10.41.1.25',
        ]);
        Group::create([
            'name' => 'ssf info',
            'department_id' => $sistemas->id,
        ]);

        // Sección 3: Despacho
        $despacho = Department::create([
            'name' => 'Despacho',
            'ip_range_start' => '10.41.1.26',
            'ip_range_end' => '10.41.1.32',
        ]);
        Group::create([
            'name' => 'ssf desp',
            'department_id' => $despacho->id,
        ]);

        // Sección 4: Dirección General
        $direccion = Department::create([
            'name' => 'Dirección General',
            'ip_range_start' => '10.41.1.33',
            'ip_range_end' => '10.41.1.39',
        ]);
        Group::create([
            'name' => 'ssf dir',
            'department_id' => $direccion->id,
        ]);

        // Sección 5: Dirección General (duplicada, misma info que Sección 4)
        // (Omitida porque es redundante)

        // Sección 6: Oficina de gestión administrativa y finanzas
        $admin = Department::create([
            'name' => 'Oficina de gestión administrativa y finanzas',
            'ip_range_start' => '10.41.1.40',
            'ip_range_end' => '10.41.1.65',
        ]);
        Group::create([
            'name' => 'ssf admin',
            'department_id' => $admin->id,
        ]);

        // Sección 7: Oficina de asesoría jurídica
        $juridica = Department::create([
            'name' => 'Oficina de asesoría jurídica',
            'ip_range_start' => '10.41.1.66',
            'ip_range_end' => '10.41.1.71',
        ]);
        Group::create([
            'name' => 'ssf consul',
            'department_id' => $juridica->id,
        ]);

        // Sección 8: Oficina de comunicación y relaciones institucionales
        $comunicacion = Department::create([
            'name' => 'Oficina de comunicación y relaciones institucionales',
            'ip_range_start' => '10.41.1.72',
            'ip_range_end' => '10.41.1.77',
        ]);
        Group::create([
            'name' => 'ssf comuni',
            'department_id' => $comunicacion->id,
        ]);

        // Sección 9: Oficina de recursos humanos
        $rrhh = Department::create([
            'name' => 'Oficina de recursos humanos',
            'ip_range_start' => '10.41.1.78',
            'ip_range_end' => '10.41.1.143',
        ]);
        Group::create([
            'name' => 'ssf rrhh',
            'department_id' => $rrhh->id,
        ]);

        // Sección 10: Oficina de planificación, control de gestión y organización
        $planificacion = Department::create([
            'name' => 'Oficina de planificación, control de gestión y organización',
            'ip_range_start' => '10.41.1.144',
            'ip_range_end' => '10.41.1.154',
        ]);
        Group::create([
            'name' => 'ssf planif',
            'department_id' => $planificacion->id,
        ]);

        // Sección 11: Oficina de investigación y educación
        $investigacion = Department::create([
            'name' => 'Oficina de investigación y educación',
            'ip_range_start' => '10.41.1.155',
            'ip_range_end' => '10.41.1.165',
        ]);
        Group::create([
            'name' => 'ssf drieo',
            'department_id' => $investigacion->id,
        ]);

        $inspec = Department::create([
            'name' => 'Oficina de inspección de salud',
            'ip_range_start' => '10.41.1.166',
            'ip_range_end' => '10.41.1.171',
        ]);
        Group::create([
            'name' => 'ssf inspec',
            'department_id' => $inspec->id,
        ]);

        $promo = Department::create([
            'name' => 'Dirección de promoción y participación popular en salud',
            'ip_range_start' => '10.41.1.172',
            'ip_range_end' => '10.41.1.178',
        ]);
        Group::create([
            'name' => 'ssf promo',
            'department_id' => $promo->id,
        ]);

        $colect = Department::create([
            'name' => 'Dirección de salud colectiva',
            'ip_range_start' => '10.41.1.179',
            'ip_range_end' => '10.41.1.209',
        ]);
        Group::create([
            'name' => 'ssf colect',
            'department_id' => $colect->id,
        ]);

        $servs = Department::create([
            'name' => 'Dirección de servicios de salud',
            'ip_range_start' => '10.41.1.210',
            'ip_range_end' => '10.41.1.225',
        ]);
        Group::create([
            'name' => 'ssf servs',
            'department_id' => $servs->id,
        ]);

        $mant = Department::create([
            'name' => 'Dirección de recursos para la salud',
            'ip_range_start' => '10.41.1.226',
            'ip_range_end' => '10.41.1.236',
        ]);
        Group::create([
            'name' => 'ssf mant',
            'department_id' => $mant->id,
        ]);

        $guest = Department::create([
            'name' => 'Invitados',
            'ip_range_start' => '10.41.1.237',
            'ip_range_end' => '10.41.2.255',
        ]);
        Group::create([
            'name' => 'ssf guest',
            'department_id' => $guest->id,
        ]);
        
    }

}
