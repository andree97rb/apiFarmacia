<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Trabajador;

class TrabajadorController extends Controller
{
    public function index()
    {
        $trabajador=Cache::remember('cachetrabajador',20/60, function()
        {
            return Trabajador::all();
        });

        return response()->json(['status'=>'ok','data'=>$trabajador], 200);
    }

    public function store(Request $request)
    {
        if (!$request->nombres || !$request->apellidoPaterno || !$request->apellidoMaterno || !$request->tipoDocumento || !$request->numeroDocumento || !$request->correo  || !$request->celular)
		{         
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos para acceder a su solicitud.'])], 422);
        }

        $nuevoTrabajador=Trabajador::create($request->all());

        return response()->json(['data'=>$nuevoTrabajador], 201)->header('Location', url('/api/v1/').'/trabajador/'.$nuevoTrabajador->id);
    }

    public function show($id)
    {
        $trabajador=Trabajador::find($id);

		if (!$trabajador)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encontró ningún trabajador con este código.'])], 404);
		}

        return response()->json(['status'=>'ok','data'=>$trabajador], 200);
    }
    
    public function update(Request $request, $id)
	{
		$trabajador=Trabajador::find($id);

		if (!$trabajador)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un trabajador con ese código.'])],404);
		}

		$nombres=$request->nombres;
		$apellidoPaterno=$request->apellidoPaterno;
		$apellidoMaterno=$request->apellidoMaterno;
		$tipoDocumento=$request->tipoDocumento;
		$numeroDocumento=$request->numeroDocumento;
		$correo=$request->correo;
		$celular=$request->celular;

		if ($request->method()=='PATCH')
		{
			$bandera=false;

			if ($nombres !=null && $nombres!='')
			{
				$trabajador->nombres=$nombres;
				$bandera=true;
			}

			if ($apellidoPaterno !=null && $apellidoPaterno!='')
			{
				$trabajador->apellidoPaterno=$apellidoPaterno;
				$bandera=true;
			}
            if ($apellidoMaterno !=null && $apellidoMaterno!='')
			{
				$trabajador->apellidoMaterno=$apellidoMaterno;
				$bandera=true;
			}
            if ($tipoDocumento !=null && $tipoDocumento!='')
			{
				$trabajador->tipoDocumento=$tipoDocumento;
				$bandera=true;
			}
            if ($numeroDocumento !=null && $numeroDocumento!='')
			{
				$trabajador->numeroDocumento=$numeroDocumento;
				$bandera=true;
			}
            if ($correo !=null && $correo!='')
			{
				$trabajador->correo=$correo;
				$bandera=true;
			}
            if ($celular !=null && $celular!='')
			{
				$trabajador->celular=$celular;
				$bandera=true;
			}
			else
			{
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de trabajador.'])],304);
			}
		}

		if (!$trabajador)
		{
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
		}

		$trabajador->nombres=$nombres;
		$trabajador->apellidoPaterno=$apellidoPaterno;
		$trabajador->apellidoMaterno=$apellidoMaterno;
		$trabajador->tipoDocumento=$tipoDocumento;
		$trabajador->numeroDocumento=$numeroDocumento;
		$trabajador->correo=$correo;
		$trabajador->celular=$celular;

		$trabajador->save();
		return response()->json(['status'=>'ok','data'=>$trabajador],200);

	}

}
