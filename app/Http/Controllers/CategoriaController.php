<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoriaCollection;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index() {
        // return response()->json(['categorias' => Categoria::all()]); // una forma de retornar respuesta JSON

        return new CategoriaCollection(Categoria::all()); // otra forma de retornar respuesta JSON
        // usando API resources. En CategoriaResources pones lo que quieres retornar en la respuesta JSON
    }
}
