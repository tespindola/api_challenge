<?php

namespace App\Models\pokemons;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class PokemonsModel extends Model{
    use HasFactory;

    protected $table = 'pokemons';
    protected $fillable = ['nombre', 'detalle_url'];

    // Validamos los datos para el crear/editar producto
    public static function validator($input){
        $rules = [
            'nombre' => 'required|string',
            'detalle_url' => 'required|string',
        ];

        $validator = Validator::make($input, $rules);

        return $validator;
    }
}
