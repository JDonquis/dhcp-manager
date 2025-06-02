<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Group;
use App\Models\Host;
use App\Models\User;
use App\Services\NetworkService;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function welcome(){
        return view('login');
    }

    public function admin(){

        $hosts = Host::count();
        $departments = Department::count();
        $groups = Group::count();
        $users = User::count();
        
        return view('admin.index')
        ->with(compact('hosts','departments','groups','users'));
    }

    public function generateDHCP(Request $request){

        $action = $request->input('action', 'generate');
        $networkService = new NetworkService;

        try {
            
            $response = $networkService->generateDHCP($request->input('action'));
            
            return response()->json([
                'success' => $response['success'],
                'message' => $response['message'],
                'download_url' => $response['download_url'] ?? null
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
    }

        return response()->json(['a' => $request->input('action')]);
    }

    public function reloadDHCP(){
        
        $networkService = new NetworkService;

        try {
            
            $response = $networkService->reloadDHCP();
            
            return response()->json([
                'success' => $response['success'],
                'message' => $response['message'],
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
    }
    }

    public function downloadDHCP($file) {

        $path = storage_path('app/dhcp/' . $file);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->download($path, 'dhcpd.conf', [
            'Content-Type' => 'text/plain',
        ]);
    
    }



}
