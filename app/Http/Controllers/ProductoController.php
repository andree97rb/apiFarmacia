<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        $producto=Cache::remember('cacheproducto',20/60, function()
        {
            return Producto::all();
        });

        return response()->json(['status'=>'ok','data'=>$producto], 200);
    }

    public function store(Request $request)
    {
        if (!$request->nombre || $request->idMarca == -1 || $request->idMarca == 0)
		{         
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos para acceder a su solicitud.'])], 422);
        }

        $nuevoProducto=Producto::create($request->all());

        return response()->json(['data'=>$nuevoProducto], 201)->header('Location', url('/api/v1/').'/producto/'.$nuevoProducto->id);
    }

    public function show($id)
    {
        $producto=Producto::find($id);

		if (!$producto)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encontró ningún producto con este código.'])], 404);
		}

        return response()->json(['status'=>'ok','data'=>$producto], 200);
    }

    public function update(Request $request, $id)
	{
		$producto=Producto::find($id);

		if (!$producto)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encontró ningún producto con este código.'])], 404);
		}

		$nombre=$request->nombre;
		$vigencia=$request->vigencia;
		$idMarca=$request->idMarca;

		if ($request->method()=='PATCH')
		{
			$bandera=false;

			if ($vigencia != null && $vigencia != '')
			{
				$producto->vigencia=$vigencia;
				$bandera=true;
			}

			if ($bandera)
			{
				$producto->save();
				return response()->json(['status'=>'ok','data'=>$producto], 200);
			}
			else
			{
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato del producto.'])], 304);
			}
		}

		if (!$nombre || $idMarca == -1 || $idMarca == 0)
		{
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])], 422);
		}

		$producto->nombre=$nombre;
		$producto->vigencia=$vigencia;
		$producto->idMarca=$idMarca;

		$producto->save();
		return response()->json(['status'=>'ok','data'=>$producto], 200);
	}
}
