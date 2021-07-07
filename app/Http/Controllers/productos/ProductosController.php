<?php

namespace App\Http\Controllers\productos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\productos\ProductosModel;

class ProductosController extends Controller{
    
    /**
     * Metodo para obtener los productos
     * @return \Illuminate\Http\JsonResponse
     * @return $productos
     */
    public function index(){
        // Obtenemos todos los productos con una paginacion de 20 items por pagina
        $productos = ProductosModel::paginate(20);

        return response()->json([
            'productos' => $productos->items(),
            'current_page' => $productos->currentPage(),
            'total' => $productos->total(),
            'per_page' => $productos->perPage(),
        ]);
    }

    /**
     * Metodo para crear un nuevo producto
     * @param Request $request (nombre, descripcion, precio_compra, precio_venta)
     * @return \Illuminate\Http\JsonResponse
     * @return $producto
     */
    public function store(Request $request){
        $data = $request->all();
        // Validamos la informacion que llega en request
        $validator = ProductosModel::validator($data);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        // Generamos una nueva instancia de ProductosModel
        $producto = new ProductosModel($data + ['total_compra' => $data['precio_compra'] * $data['cantidad']]);
        try {
            // Guardamos el producto nuevo
            $producto->save();
        } catch (Exception $err) {
            // En caso de error abortamos y devolvemos el error
            return response()->json($err, 500);
        }

        // Retornamos el producto que se creo
        return response()->json(['producto' => $producto]);
    }

    /**
     * Metodo para editar un producto
     * @param Request $request (nombre, descripcion, precio_compra, precio_venta), $id
     * @return \Illuminate\Http\JsonResponse
     * @return $producto
     */
    public function update(Request $request, $id){
        $data = $request->all();
        if(!isset($data['nombre']) && !isset($data['descripcion']) && !isset($data['precio_compra']) && !isset($data['precio_venta']) && !isset($data['cantidad'])){
            return response()->json(['message' => 'Send data to edit']);
        }

        try {
            // Buscamos el id que nos pasan y actualizamos la info de dicho producto
            $producto_edit = ProductosModel::findOrFail($id);
            $producto_edit->fill($data);
            $producto_edit->save();
        } catch (Exception $err) {
            // En caso de error abortamos y devolvemos el error
            return response()->json($err, 500);
        }

        // Retornamos el producto editado
        return response()->json(['producto' => $producto_edit]);
    }

    /**
     * Metodo para eliminar un producto
     * @param Number $id
     * @return \Illuminate\Http\JsonResponse
     * @return Boolean
     */
    public function delete($id){
        try {
            // Buscamos el producto y lo eliminamos
            ProductosModel::findOrFail($id)->delete();
        } catch (\Throwable $th) {
            // En caso de error abortamos y devolvemos el error
            abort(response($err, 500));
        }

        // Notificamos que se elimino el producto
        return response()->json(['delete' => true]);
    }

}
