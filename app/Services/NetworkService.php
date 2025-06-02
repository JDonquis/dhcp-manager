<?php  

namespace App\Services;

use App\Enums\TypeNotification;
use App\Models\Department;
use App\Models\Network;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NetworkService
{	
   

   public function update($data){
   
    return DB::transaction(function () use ($data){

        try {
            
        $data['options'] = json_decode($data['options'], true);
        $data['params'] = json_decode($data['params'], true);

        Network::updateOrCreate(['id' => 1], $data);

        $this->notifyUsers(TypeNotification::NETWORK_UPDATED);

        return 0;

        } catch (Exception $e) {
            
            Log::error('NetworkService - Error al actualizar network: '.$e->getMessage(), [
                    'data' => $data,
                    'trace' => $e->getTraceAsString()
                ]);

            throw $e;
        }
    });
    
   }

   public function generateDHCP($action){

    $network = Network::firstOrFail();

    $departments = Department::with(['hosts' => function($query) {
        $query->orderBy('ip');
    }])->get();

    $content = $this->generateHeaderSection();
    $content.= $this->generateNetworkConfigSection($network);
    $content.= $this->generateHostSection($departments);
    $content.= $this->generateFooterSection();
    
    $fileName = 'dhcpd-' . now()->format('Ymd-His') . '.conf';
    $filePath = storage_path('app/dhcp/' . $fileName);

    if (!file_exists(dirname($filePath))) {
        mkdir(dirname($filePath), 0755, true);
    }

    file_put_contents($filePath, $content);

    if ($action === 'generate_and_execute') {
        $output = $this->applyDhcpConfiguration($filePath);
        
        return [
            'success' => true,
            'message' => 'Archivo DHCP generado y configuración aplicada correctamente. ' . $output,
            'file_path' => $filePath
        ];
    }

     return [
        'success' => true,
        'message' => 'Archivo DHCP generado correctamente',
        'file_path' => $filePath,
        'download_url' => route('download-dhcp-file', ['file' => basename($filePath)])
    ];

   }

   public function reloadDHCP(){

        $command = 'systemctl restart isc-dhcp-server';
        // $command = 'touch /home/donquis/testingdhcp.txt';

        
        // Ejecutar el comando (requiere permisos adecuados)
        exec($command, $output, $returnVar);
        
        if ($returnVar !== 0) {
            throw new \RuntimeException('Error al reiniciar la configuración DHCP: ' . implode("\n", $output));
        }
        
        $output = implode("\n", $output);

        return [
            'success' => true,
            'message' => 'Servicio DHCP reiniciado correctamente. ' . $output,
        ];
        

   }

   public function generateHeaderSection(){

    $header = "##################################################\n";
    $header .= "## ARCHIVO DHCP - SECRETARÍA DE SALUD\n";
    $header .= "## Última actualización: " . now()->format('d/m/Y') . "\n";
    $header .= "## Responsable: " . auth()->user()->name . "\n";
    $header .= "##################################################\n\n";

    return $header;

   }

   public function generateNetworkConfigSection($network){

    $networkConfig = "#################################\n";
    $networkConfig .= "## CONFIGURACIÓN GLOBAL\n";
    $networkConfig .= "#################################\n\n";

    $params = $network->params ?? [];

    foreach ($params as $key => $value) {

        if ($value !== null && $value !== '') {

            $networkConfig .= "$key $value;\n";
        
        } else {

            $networkConfig .= "$key;\n";
        }
    }

    $networkConfig .= "\n";

    $networkConfig .= "#################################\n";
    $networkConfig .= "## CONFIGURACIÓN DE RED PRINCIPAL\n";
    $networkConfig .= "#################################\n\n";

    $options = $network->options ?? [];
    $networkConfig .= "subnet " . $network->subnet . " netmask " . $network->netmask . " {\n";

    foreach ($options as $key => $value) {

        $networkConfig .= "    $key $value;\n";
    }

    $networkConfig .= "}\n\n";

    return $networkConfig;

   }

   public function generateHostSection($departments){

    $hostConfig = "####################################\n";
    $hostConfig .= "## ASIGNACIONES ESTÁTICAS POR DEPARTAMENTO\n";
    $hostConfig .= "####################################\n\n";

    foreach ($departments as $department) {

        if ($department->hosts->isEmpty()) {
            continue;
        }

        $hostConfig .= "####### DEPARTAMENTO: " . $department->name . " #######\n";
        $hostConfig .= "####### RANGO: " . $department->ip_range_start . " - " . $department->ip_range_end . " #######\n";
        
        foreach ($department->hosts as $host) {
            $hostConfig .= "host " . str_replace(' ', '-', strtolower($host->name)) . " {\n";
            $hostConfig .= "    hardware ethernet     " . $host->mac . ";\n";
            $hostConfig .= "    fixed-address         " . $host->ip . ";\n";
            $hostConfig .= "}\n\n";
        }

        $hostConfig .= "####### FIN DEPARTAMENTO: " . $department->name . " #######\n\n";
    }

    return $hostConfig;

   }

   public function generateFooterSection(){
    
        $footer = "####################################\n";
        $footer .= "## FIN DEL ARCHIVO\n";
        $footer .= "####################################";

        return $footer;

}
    
   protected function applyDhcpConfiguration($filePath)
    {
        // $command = 'cp ' . escapeshellarg($filePath) . ' /home/donquis';

        $dhcpConfPath = '/etc/dhcp/dhcpd.conf';
        $backupPath = '/etc/dhcp/dhcpd.conf.backup_' . date('Y-m-d_His');

        $command = sprintf(
            'sudo cp %s %s && sudo cp %s %s && sudo systemctl restart isc-dhcp-server',
            escapeshellarg($dhcpConfPath),
            escapeshellarg($backupPath),
            escapeshellarg($filePath),
            escapeshellarg($dhcpConfPath)
        );

        
        // Ejecutar el comando (requiere permisos adecuados)
        exec($command, $output, $returnVar);
        
        if ($returnVar !== 0) {
            throw new \RuntimeException('Error al aplicar la configuración DHCP: ' . implode("\n", $output));
        }
        
        return implode("\n", $output);
    } 

   protected function notifyUsers(TypeNotification $type): void
    {
        try {

            $users = User::get();
            
            Notification::send($users, $type->createNotification());

        } catch (Exception $e) {
            
            Log::error('Error enviando network notifications: '.$e->getMessage(), [
                'error' => $e->getTraceAsString()
            ]);
        }
    }

}