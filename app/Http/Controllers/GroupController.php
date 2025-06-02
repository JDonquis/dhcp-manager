<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Models\Department;
use App\Models\Group;
use App\Services\GroupService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class GroupController extends Controller
{
    private GroupService $groupService;
    
    public function __construct()
    {
        $this->groupService = new GroupService;
    }

    public function index()
    {
        return view('admin.groups.index');
    }

    public function data(){

        $group = Group::query()
        ->with('department')
        ->withCount('hosts');
        
        return DataTables::eloquent($group)
            ->addColumn('action', function($group) {
                return '<div class="btn-group" role="group">
                                    <a href="'.route("groups.edit",["group" => $group->id]).'" class="btn btn-outline-secondary">
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
        $departments = Department::get();
        return view('admin.groups.create')
        ->with(compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupRequest $request)
    {
        try {
            
            $newGroup = $this->groupService->create($request->validated());

            return redirect()
            ->route('groups.index')
            ->with([
                'success' => 'Grupo creado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El grupo '.$newGroup->name.' ha sido registrado correctamente'
                ]
            ]);

        } catch (Exception $e) {


            Log::error('Error al crear group: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);
        
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'error' => 'Ocurrió un error al crear el grupo. Por favor intente nuevamente',
                ]);
            
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {   
        
        $departments = Department::get();

        return view('admin.groups.edit')
        ->with(compact('group', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupRequest $request, Group $group)
    {
        try{
            
            $updatedGroup = $this->groupService->update($request->validated(), $group);

            return redirect()
            ->route('groups.index')
            ->with([
                'success' => 'Grupo actualizado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El grupo ' . $updatedGroup->name . ' ha sido actualizado correctamente'
                ]
                ]);

        }catch(Exception $e){

            Log::error('Error al actualizar grupo: '. $e->getMessage(), [
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
    public function destroy(Group $group)
    {
        try{

            $name = $group->name;
            $this->groupService->delete($group);

            return redirect()
            ->route('groups.index')
            ->with([
                'success' => 'Grupo eliminado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El grupo ' . $name . ' ha sido eliminado correctamente' 
                ]
                ]);

        }catch(Exception $e){

            Log::error('Error al eliminar grupo: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $group->toArray(),
            ]);

            return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'error' => 'Ocurrió un error al eliminar el grupo. Por favor intente nuevamente',
            ]);
        }
    }
}
