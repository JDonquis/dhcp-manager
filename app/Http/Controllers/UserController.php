<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    private UserService $userService;
    
    public function __construct()
    {
        $this->userService = new UserService;
    }

    public function login(LoginRequest $request){
        try {
            $data = ['username' => $request->username, 'password' => $request->password];
            $this->userService->tryLogin($data);

            return redirect('/admin');

        } catch (Exception $e) {
            
            return redirect('/')->withErrors(['error' => $e->getMessage()]);

        }
    }

    public function logout(){
        
        Auth::logout();

        return redirect('/');
    }

    public function index()
    {
        return view('admin.users.index');
    }

    public function data(){

        $user = User::query();
        
        return DataTables::eloquent($user)
            ->addColumn('action', function($user) {
                return '<div class="btn-group" role="group">
                                    <a href="'.route("users.edit",["user" => $user->id]).'" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>';
            })
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            
            $newUser = $this->userService->create($request->validated());

            return redirect()
            ->route('users.index')
            ->with([
                'success' => 'Usuario creado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El usuario '.$newUser->name.' ha sido registrado correctamente'
                ]
            ]);

        } catch (Exception $e) {


            Log::error('Error al crear usuario: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);
        
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'error' => 'Ocurrió un error al crear el usuario. Por favor intente nuevamente',
                ]);
            
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit')
        ->with(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        try{
            
            $updatedUser = $this->userService->update($request->validated(), $user);

            return redirect()
            ->route('users.index')
            ->with([
                'succes' => 'Usuario actualizado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El usuario ' . $updatedUser->name . ' ha sido actualizado correctamente'
                ]
                ]);

        }catch(Exception $e){

            Log::error('Error al actualizar usuario: '. $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->validated(),
            ]);

            return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'error' => 'Ocurrio un error al actualizar. Por favor intente nuevamente'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try{

            if($user->id == auth()->user()->id)
                throw new Exception("No puede eliminarse a si mismo", 400);
                 

            $name = $user->name;
            $this->userService->delete($user);

            return redirect()
            ->route('users.index')
            ->with([
                'success' => 'Usuario eliminado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El usuario ' . $name . ' ha sido eliminado correctamente' 
                ]
                ]);

        }catch(Exception $e){

            Log::error('Error al eliminar usuario: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $user->toArray(),
            ]);

            return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'error' => 'Ocurrió un error al eliminar el usuario. Por favor intente nuevamente',
            ]);
        }
    }

    public function username(){
        return 'username';
    }
}
