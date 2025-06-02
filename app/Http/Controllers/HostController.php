<?php

namespace App\Http\Controllers;

use App\Http\Requests\HostRequest;
use App\Models\Department;
use App\Models\Host;
use App\Services\HostService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class HostController extends Controller
{
    
    private HostService $hostService;
    
    public function __construct()
    {
        $this->hostService = new HostService;
    }

    public function index()
    {
        return view('admin.hosts.index');
    }

    public function data(){

        $host = Host::query()
        ->with('department','group'); // Ajusta según tu modelo
        
        return DataTables::eloquent($host)
            ->addColumn('action', function($host) {
                return '<div class="btn-group" role="group">
                                    <a href="'.route("hosts.edit",["host" => $host->id]).'" class="btn btn-outline-secondary">
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
        $departments = Department::with(['groups', 'hosts'])->get()->map(function($department) {
            $department->available_ips = $department->availableIps();
        return $department;
    });

        return view('admin.hosts.create')
        ->with([
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HostRequest $request)
    {   
        
        try {
            
            $newHost = $this->hostService->create($request->validated());

            return redirect()
            ->route('hosts.index')
            ->with([
                'success' => 'Host creado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El host '.$newHost->name.' ha sido registrado correctamente'
                ]
            ]);

        } catch (Exception $e) {


            Log::error('Error al crear host: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);
        
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'error' => 'Ocurrió un error al crear el host. Por favor intente nuevamente',
                ]);
            
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Host $host)
    {
        $departments = Department::with(['groups', 'hosts'])->get()->map(function($department) {
            $department->available_ips = $department->availableIps();
        return $department;
    });


        return view('admin.hosts.edit')
        ->with([
            'host' => $host,
            'departments' => $departments,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HostRequest $request, Host $host)
    {
        try{

            $updatedHost = $this->hostService->update($request->validated(), $host);

            return redirect()
            ->route('hosts.index')
            ->with([
                'success' => 'Host actualizado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El host ' .$updatedHost->name . ' ha sido registrado correctamente'
                ]
                ]);
        } catch(Exception $e){

            Log::error('Error al actualizar host: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'error' => 'Ocurrió un error al actualizar el host. Por favor intente nuevamente',
            ]);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Host $host)
    {
        try{

            $name = $host->name;
            $this->hostService->delete($host);

            return redirect()
            ->route('hosts.index')
            ->with([
                'success' => 'Host eliminado exitosamente',
                'notification' => [
                    'type' => 'success',
                    'message' => 'El host ' . $name . ' ha sido eliminado correctamente' 
                ]
                ]);

        }catch(Exception $e){

            Log::error('Error al eliminar host: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $host->toArray(),
            ]);

            return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'error' => 'Ocurrió un error al eliminar el host. Por favor intente nuevamente',
            ]);
        }
    }
}
