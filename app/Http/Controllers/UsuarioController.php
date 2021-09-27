<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuario=Cache::remember('cacheusuario',20/60, function()
        {
            return Usuario::all();
        });

        return response()->json(['status'=>'ok','data'=>$usuario], 200);
    }

    public function store(Request $request)
    {
        if (!$request->nombre || !$request->clave || !$request->idTrabajador)
		{         
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos para acceder a su solicitud.'])], 422);
        }

        $nuevoUsuario=Usuario::create($request->all());

        return response()->json(['data'=>$nuevoUsuario], 201)->header('Location', url('/api/v1/').'/usuario/'.$nuevoUsuario->id);
    }

    public function show($id)
    {
        $usuario=Usuario::find($id);

		if (!$usuario)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encontró ningún usuario con este código.'])], 404);
		}

        return response()->json(['status'=>'ok','data'=>$usuario], 200);
    }

    public function update(Request $request, $id)
	{
		$usuario=Usuario::find($id);

		if (!$usuario)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un usuario con ese código.'])],404);
		}

		$nombre=$request->nombre;
		$clave=$request->clave;
		$idTrabajador=$request->idTrabajador;


		if ($request->method()=='PATCH')
		{
			$bandera=false;

			if ($nombre !=null && $nombre!='')
			{
				$usuario->nombre=$nombre;
				$bandera=true;
			}

			if ($clave !=null && $clave!='')
			{
				$usuario->clave=$clave;
				$bandera=true;
			}

            if ($idTrabajador !=null && $idTrabajador!='')
			{
				$usuario->idTrabajador=$idTrabajador;
				$bandera=true;
			}
			if ($bandera)
			{
				$usuario->save();

				return response()->json(['status'=>'ok','data'=>$usuario],200);
			}
			else
			{
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato del usuario.'])],304);
			}
		}

		if (!$usuario)
		{
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
		}

		$usuario->nombre=$nombre;
		$usuario->clave=$clave;
		$usuario->idTrabajador=$idTrabajador;

		$usuario->save();
		return response()->json(['status'=>'ok','data'=>$usuario],200);

	}
}
