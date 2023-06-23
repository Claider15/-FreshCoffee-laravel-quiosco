<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Models\PedidoProducto;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PedidoCollection;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new PedidoCollection(Pedido::with('user')->with('productos')->where('estado', 0)->get());
        // con with('user - métdodo deuser en Pedido.php) se puede cargar inf. que esté relacionada con
        // un registro
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Almacenar un pedido
        $pedido = new Pedido;
        $pedido->user_id = Auth::user()->id; //usando laravel obtenemos el usuario autenticado (peticion autenticada)
        $pedido->total = $request->total; // el nombre que coloque en axios, el mismo nombre se usa para recuperarlo en el $request (controller)
        $pedido->save();

        // Obtener el ID del Pedido
        $id = $pedido->id;

        // Obtener los productos
        $productos = $request->productos;

        // Formatear un arreglo con los IDs y las cantidades
        $pedido_producto = [];

        foreach($productos as $producto) {
            $pedido_producto[] = [
                'pedido_id'=> $id,
                'producto_id' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        //Almacenar en la BD
        PedidoProducto::insert($pedido_producto);

        return [
            'message' => 'Pedido realizado correctamente, estará listo en unos minutos'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        $pedido->estado = 1;
        $pedido->save();
        
        return [
            'pedido' => $pedido
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        //
    }
}
