<?php  

namespace App\Services;

use App\Enums\TypeNotification;
use App\Models\Host;
use App\Models\User;
use App\Notifications\HostCreatedNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class HostService
{	
   public function create($data){
   
    return DB::transaction(function () use ($data){

        try {
            
            $newHost =  Host::create([
            'name' => $data['name'],
            'user' => $data['user'] ?? $data['name'],
            'department_id' => $data['department_id'],
            'group_id' => $data['group_id'] ?? null,
            'mac' => $data['mac'],
            'ip' => $data['ip'],
        ]);

        $this->notifyUsers($newHost,TypeNotification::HOST_CREATED);

        return $newHost;

        } catch (Exception $e) {
            
            Log::error('HostService - Error al crear host: '.$e->getMessage(), [
                    'data' => $data,
                    'trace' => $e->getTraceAsString()
                ]);

            throw $e;
        }
    });
    
   }

   public function update($data, $host){
   
    return DB::transaction(function () use ($data, $host){

        try {
            
            $host->update([
            'name' => $data['name'],
            'user' => $data['user'] ?? $data['name'],
            'department_id' => $data['department_id'],
            'group_id' => $data['group_id'] ?? null,
            'mac' => $data['mac'],
            'ip' => $data['ip'],
        ]);

        $this->notifyUsers($host, TypeNotification::HOST_UPDATED);

        return $host;

        } catch (Exception $e) {
            
            Log::error('HostService - Error al actualizar host: '.$e->getMessage(), [
                    'data' => $data,
                    'trace' => $e->getTraceAsString()
                ]);

            throw $e;
        }
    });
    
   }

   public function delete($host){
    
        return DB::transaction(function () use ($host){
            try{
                
                $clone = $host->replicate();
                $host->delete();
                $this->notifyUsers($clone, TypeNotification::HOST_DELETED);
                
                return 0;
                

            }catch(Exception $e){

                    Log::error('HostService - Error al eliminar host: '.$e->getMessage(), [
                        'data' => $host->toArray(),
                        'trace' => $e->getTraceAsString()
                    ]);

                throw $e;
            }
        });

   }

   protected function notifyUsers(Host $host, TypeNotification $type): void
    {
        try {
            $users = User::get();
            
            Notification::send($users, $type->createNotification($host));

        } catch (Exception $e) {
            
            Log::error('Error enviando host notifications: '.$e->getMessage(), [
                'host_id' => $host->id,
                'error' => $e->getTraceAsString()
            ]);
        }
    }

}