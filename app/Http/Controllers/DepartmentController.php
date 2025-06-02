<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Models\Group;
use App\Services\DepartmentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    private DepartmentService $departmentService;
    
    public function __construct()
    {
        $this->departmentService = new DepartmentService;
    }

    public function index()
    {   
        return view('admin.departments.index');
    }

    public function data(){

        $department = Department::query()
        ->with('groups')
        ->withCount('hosts');
        
        return DataTables::eloquent($department)
            ->addColumn('groups', function ($department){

                return $department->groups->pluck('name')->toArray();
            })
            ->addColumn('action', function($department) {
                return '<div class="btn-group" role="group">
                                    <a href="'.route("departments.edit",["department" => $department->id]).'" class="btn btn-outline-secondary">
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
        return view('admin.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        try {
            
            $newDepartment = $this->departmentService->create($request->validated());

            return redirect()
            ->route('departments.index')
            ->with([
                'success' => 'Departamento creado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El departamento '.$newDepartment->name.' ha sido registrado correctamente'
                ]
            ]);

        } catch (Exception $e) {


            Log::error('Error al crear department: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);
        
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'error' => 'Ocurrió un error al crear el departamento. Por favor intente nuevamente',
                ]);
            
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit')
        ->with(compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        try{
            
            $updatedDepartment = $this->departmentService->update($request->validated(), $department);

            return redirect()
            ->route('departments.index')
            ->with([
                'succes' => 'Departamento actualizado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El departamento ' . $updatedDepartment->name . ' ha sido actualizado correctamente'
                ]
                ]);

        }catch(Exception $e){

            Log::erro('Error al actualizar departamento: '. $e->getMessage(), [
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
    public function destroy(Department $department)
    {
        try{

            $name = $department->name;
            $this->departmentService->delete($department);

            return redirect()
            ->route('departments.index')
            ->with([
                'success' => 'Departamento eliminado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El departamento ' . $name . ' ha sido eliminado correctamente' 
                ]
                ]);

        }catch(Exception $e){

            Log::error('Error al eliminar departamento: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $department->toArray(),
            ]);

            return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'error' => 'Ocurrió un error al eliminar el departamento:' . $e->getMessage(),
            ]);
        }
    }
}
