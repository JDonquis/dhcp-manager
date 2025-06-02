<?php  

namespace App\Services;

use App\Enums\TypeNotification;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class UserService
{	
   public function tryLogin($dataToLogin){

        if(Auth::attempt($dataToLogin))
            request()->session()->regenerate();
        else
            throw new Exception("Datos incorrectos, intente nuevamente", 400);

   }

   public function create($data){

    return DB::transaction(function () use ($data){

        try {
            
            $newUser =  User::create([
            'name' => $data['name'],
            'username' => strtolower($data['username']),
            'password' => Hash::make($data['password']),
        ]);

        $this->notifyUsers($newUser,TypeNotification::USER_CREATED);

        return $newUser->fresh(); 

        } catch (Exception $e) {
            
            Log::error('UserService - Error al crear servicio: '.$e->getMessage(), [
                    'data' => [$data['name'], $data['username']],
                    'trace' => $e->getTraceAsString()
                ]);

            throw $e;
        }
    });

   }

   public function update(array $data, User $user)
    {
        return DB::transaction(function () use ($data, $user) {
            try {
                // Actualizar datos básicos
                $updateData = [
                    'name' => $data['name'],
                    'username' => strtolower($data['username']), // Normalizar username
                ];
                
                // Actualizar contraseña solo si se proporcionó una nueva
                if (!empty($data['password'])) {
                    $updateData['password'] = Hash::make($data['password']);
                }
                
                $user->update($updateData);
                
                if ($user->wasChanged()) {
                    $this->notifyUsers($user, TypeNotification::USER_UPDATED);
                }
                
                return $user->fresh(); 

            } catch (Exception $e) {
                Log::error('UserService - Error al actualizar usuario: '.$e->getMessage(), [
                    'user_id' => $user->id,
                    'data' => $data, 
                    'trace' => $e->getTraceAsString()
                ]);
                
                throw new Exception('Failed to update user', 0, $e);
            }
        });
    }

   public function delete($user){
    
        return DB::transaction(function () use ($user){
            try{
                
                $clone = $user->replicate();

                $user->delete();
                $this->notifyUsers($clone, TypeNotification::USER_DELETED);
                
                return 0;
                

            }catch(Exception $e){

                    Log::error('UserService - Error al eliminar user: '.$e->getMessage(), [
                        'data' => $user->toArray(),
                        'trace' => $e->getTraceAsString()
                    ]);

                throw $e;
            }
        });

   }

   protected function notifyUsers(User $user, TypeNotification $type): void
    {
        try {
            $users = User::get();
            
            Notification::send($users, $type->createNotification($user));

        } catch (Exception $e) {
            
            Log::error('Error enviando user notifications: '.$e->getMessage(), [
                'user_id' => $user->id,
                'error' => $e->getTraceAsString()
            ]);
        }
    }

}