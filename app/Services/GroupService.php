<?php  

namespace App\Services;

use App\Enums\TypeNotification;
use App\Models\Group;
use App\Models\Host;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class GroupService
{	
   public function create($data){
   
    return DB::transaction(function () use ($data){

        try {
            
            $newDepartment =  Group::create([
            'name' => $data['name'],
            'department_id' => $data['department_id'],
        ]);

        $this->notifyUsers($newDepartment,TypeNotification::GROUP_CREATED);

        return $newDepartment;

        } catch (Exception $e) {
            
            Log::error('DepartmentService - Error al crear departamento: '.$e->getMessage(), [
                    'data' => $data,
                    'trace' => $e->getTraceAsString()
                ]);

            throw $e;
        }
    });
    
   }

   public function update($data, $group){
   
    return DB::transaction(function () use ($data, $group){

        try {
            
            if($data['department_id'] != $group->department_id)
                  $group->hosts()->update(['group_id' => null]);

            $group->update([
                'name' => $data['name'],
                'department_id' => $data['department_id'],
            ]);

        $this->notifyUsers($group, TypeNotification::GROUP_UPDATED);

        return $group;

        } catch (Exception $e) {
            
            Log::error('GroupService - Error al actualizar grupo: '.$e->getMessage(), [
                    'data' => $data,
                    'trace' => $e->getTraceAsString()
                ]);

            throw $e;
        }
    });
    
   }

   public function delete($group){
    
        return DB::transaction(function () use ($group){
            try{
                
                $clone = $group->replicate();

                $group->hosts()->update(['group_id' => null]);
                $group->delete();
                $this->notifyUsers($clone, TypeNotification::GROUP_DELETED);
                
                return 0;
                

            }catch(Exception $e){

                    Log::error('GroupService - Error al eliminar grupo: '.$e->getMessage(), [
                        'data' => $group->toArray(),
                        'trace' => $e->getTraceAsString()
                    ]);

                throw $e;
            }
        });

   }

   protected function notifyUsers(Group $group, TypeNotification $type): void
    {
        try {

            $users = User::get();
            
            Notification::send($users, $type->createNotification($group));

        } catch (Exception $e) {
            
            Log::error('Error enviando group notifications: '.$e->getMessage(), [
                'group_id' => $group->id,
                'error' => $e->getTraceAsString()
            ]);
        }
    }

}