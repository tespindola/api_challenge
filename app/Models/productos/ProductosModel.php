<?php

namespace App\Models\productos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class ProductosModel extends Model{
    use HasFactory;

    protected $table = 'productos';
    protected $fillable = ['nombre', 'descripcion', 'cantidad', 'precio_compra', 'total_compra', 'precio_venta'];

    // Validamos los datos para el crear/editar producto
    public static function validator($input){
        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'cantidad' => 'required|numeric',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
        ];

        $validator = Validator::make($input, $rules);

        return $validator;
    }
}
