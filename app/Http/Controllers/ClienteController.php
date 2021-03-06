<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;
use Auth;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $personas = Persona::orderBy('id', 'desc');
        }
        else{
            $personas = Persona::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc');
        }
         
        // Filtro multiempresa 
        $personas->where('idempresa','=', Auth::user()->idempresa);

        $personas = $personas->paginate(6);

        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }
 
    public function selectCliente(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        $clientes = Persona::where(function($query) use ($filtro) {
            $query->where('nombre', 'like', '%'. $filtro . '%')
            ->orWhere('num_documento', 'like', '%'. $filtro . '%');
        })
        ->select('id','nombre','num_documento')
        ->orderBy('nombre', 'asc');

        // Filtro multiempresa 
        $clientes->where('idempresa','=', Auth::user()->idempresa);

        // $query = $clientes->toSql();
        $clientes = $clientes->get();
 
        return ['clientes' => $clientes];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = new Persona();
        $persona->idempresa = Auth::user()->idempresa;
        $persona->nombre = strtoupper($request->nombre);
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = strtoupper($request->direccion);
        $persona->telefono = $request->telefono;
        $persona->email = strtoupper($request->email);
 
        $persona->save();
    }
 
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        //$persona->idempresa = Auth::user()->idempresa;
        $persona->nombre = strtoupper($request->nombre);
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = strtoupper($request->direccion);
        $persona->telefono = $request->telefono;
        $persona->email = strtoupper($request->email);
        $persona->save();
    }
}
