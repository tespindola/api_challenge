<?php

namespace App\Http\Controllers\pokemons;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\pokemons\PokemonsModel;
use Exception;

class PokemonsController extends Controller{
    
    // Consultamos a la api y guardamos todos los datos obtenidos
    public function savePokemons(){
        try {
            $response = Http::get('https://pokeapi.co/api/v2/pokemon?limit=1118');
            $pokemons = $response->json()['results'];
            foreach($pokemons as $pokemon){
                $search = PokemonsModel::where('nombre', $pokemon['name'])->first();
                if(!isset($search)){
                    $create = new PokemonsModel([
                        'nombre' => $pokemon['name'],
                        'detalle_url' => $pokemon['url']
                    ]);
                    $create->save();
                }
            }
        } catch (Exception $err) {
            return response()->json($err, 500);
        }
        
        return response()->json(['message' => 'Save success'], 200);;
    }

    /**
     * Metodo para obtener los pokemons
     * @return \Illuminate\Http\JsonResponse
     * @return $pokemons
     */
    public function index(Request $request){
        // Obtenemos todos los pokemons con una paginacion de 20 items por pagina
        $pokemons = PokemonsModel::query();
        if(isset($request['q'])){
            $pokemons->where('nombre', 'LIKE', "%{$request['q']}%")->paginate(20);
        }
        $pokemons = $pokemons->paginate(20);

        return response()->json([
            'pokemons' => $pokemons->items(),
            'current_page' => $pokemons->currentPage(),
            'total' => $pokemons->total(),
            'per_page' => $pokemons->perPage(),
        ]);
    }

    /**
     * Metodo para crear un nuevo pokemon
     * @param Request $request (nombre, detalle_url)
     * @return \Illuminate\Http\JsonResponse
     * @return $pokemons
     */
    public function store(Request $request){
        $data = $request->all();
        // Validamos la informacion que llega en request
        $validator = PokemonsModel::validator($data);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        // Generamos una nueva instancia de PokemonsModel
        $pokemon = new PokemonsModel($data);
        try {
            // Guardamos el pokemon nuevo
            $pokemon->save();
        } catch (Exception $err) {
            // En caso de error abortamos y devolvemos el error
            return response()->json($err, 500);
        }

        // Retornamos el pokemon que se creo
        return response()->json(['pokemon' => $pokemon]);
    }

}
