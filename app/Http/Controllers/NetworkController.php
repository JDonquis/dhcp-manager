<?php

namespace App\Http\Controllers;

use App\Http\Requests\NetworkRequest;
use App\Models\Network;
use App\Services\NetworkService;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    


    public function edit()
    {
        $network = Network::firstOrNew();

        return view('admin.configuration.index')
        ->with(['network' => $network]);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(NetworkRequest $request)
    {   

        $networkService = new NetworkService;
        $networkService->update($request->validated());


        return back()->with([
            'success' => 'Configuracion actualizada exitosamente',
            'notification' => [
                'type' => 'success',
                'message' => 'Configuracion ha sido actualizada correctamente'
            ]]);

    }

   
}
