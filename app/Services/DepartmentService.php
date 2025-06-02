<?php  

namespace App\Services;

use App\Enums\TypeNotification;
use App\Models\Department;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class DepartmentService
{	
   public function create($data){
   
    return DB::transaction(function () use ($data){

        try {
            
            $newDepartment =  Department::create([
            'name' => $data['name'],
            'ip_range_start' => $data['ip_range_start'],
            'ip_range_end' => $data['ip_range_end'],
        ]);

        $this->notifyUsers($newDepartment,TypeNotification::DEPARTMENT_CREATED);

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

   public function update($data, $department){
   
    return DB::transaction(function () use ($data, $department){

        try {
            
            if($data['ip_range_start'] != $department->ip_range_start || $data['ip_range_end'] != $department->ip_range_end ){

                $this->validateHostsWithinRange($department, $data['ip_range_start'], $data['ip_range_end']);
                $this->validateRangeOverlap($data['ip_range_start'], $data['ip_range_end'], $department->id);
            }
            

            $department->update([
            'name' => $data['name'],
            'ip_range_start' => $data['ip_range_start'],
            'ip_range_end' => $data['ip_range_end'],
        ]);

        $this->notifyUsers($department, TypeNotification::DEPARTMENT_UPDATED);

        return $department;

        } catch (Exception $e) {
            
            Log::error('DepartmentService - Error al actualizar departamento: '.$e->getMessage(), [
                    'data' => $data,
                    'trace' => $e->getTraceAsString()
                ]);

            throw $e;
        }
    });
    
   }

   public function delete($department){
    
        return DB::transaction(function () use ($department){
            try{
                
                $clone = $department->replicate();

                $department->hosts()->delete();
                $department->delete();
                $this->notifyUsers($clone, TypeNotification::DEPARTMENT_DELETED);
                
                return 0;
                

            }catch(Exception $e){

                    Log::error('DepartmentService - Error al eliminar departamento: '.$e->getMessage(), [
                        'data' => $department->toArray(),
                        'trace' => $e->getTraceAsString()
                    ]);

                throw $e;
            }
        });

   }

   protected function ipToLong(string $ip): int
    {
        return ip2long($ip) ?: 0;
    }

   protected function validateHostsWithinRange(Department $department, string $newStartIp, string $newEndIp)
    {
        $startLong = $this->ipToLong($newStartIp);
        $endLong = $this->ipToLong($newEndIp);

        $hostsOutsideRange = $department->hosts()
            ->where(function($query) use ($startLong, $endLong) {
                $query->whereRaw('INET_ATON(ip) < ?', [$startLong])
                    ->orWhereRaw('INET_ATON(ip) > ?', [$endLong]);
            })
            ->exists();

        if ($hostsOutsideRange) {
            throw new \Exception('Existen hosts en este departamento que quedan fuera del nuevo rango IP');
        }
    }

    protected function validateRangeOverlap(string $newStartIp, string $newEndIp, $currentDepartmentId = null)
    {
        $newStart = $this->ipToLong($newStartIp);
        $newEnd = $this->ipToLong($newEndIp);

        $overlappingDepartment = Department::where('id', '!=', $currentDepartmentId)
            ->where(function($query) use ($newStart, $newEnd) {
                $query->where(function($q) use ($newStart, $newEnd) {
                        $q->whereRaw('INET_ATON(ip_range_start) <= ?', [$newStart])
                        ->whereRaw('INET_ATON(ip_range_end) >= ?', [$newStart]);
                    })
                    ->orWhere(function($q) use ($newStart, $newEnd) {
                        $q->whereRaw('INET_ATON(ip_range_start) <= ?', [$newEnd])
                        ->whereRaw('INET_ATON(ip_range_end) >= ?', [$newEnd]);
                    })
                    ->orWhere(function($q) use ($newStart, $newEnd) {
                        $q->whereRaw('INET_ATON(ip_range_start) >= ?', [$newStart])
                        ->whereRaw('INET_ATON(ip_range_end) <= ?', [$newEnd]);
                    });
            })
            ->first();

        if ($overlappingDepartment) {
            throw new \Exception("El rango IP se solapa con el departamento: {$overlappingDepartment->name}");
        }
    }

   protected function notifyUsers(Department $department, TypeNotification $type): void
    {
        try {
            $users = User::get();
            
            Notification::send($users, $type->createNotification($department));

        } catch (Exception $e) {
            
            Log::error('Error enviando departamento notifications: '.$e->getMessage(), [
                'department_id' => $department->id,
                'error' => $e->getTraceAsString()
            ]);
        }
    }

}