<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Devuelve los registros de categorías
        $categorias = Categoria::orderBy('catNombre')->paginate(12);
        return view('/categorias', ['categorias'=>$categorias]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/categoriaCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     */ 
     private function validarForm( Request $request )
     {
        $request->validate(
                ['catNombre'=>'required|min:3|max:20'],
                [
                    'catNombre.required'=>'El campo nombre es obligatorio',
                    'catNombre.min'=>'El nombre debe tener al menos 3 caracteres',
                    'catNombre.max'=>'El nombre debe tener menos de 20 caracteres'
                ]
        );
     }

     
     /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //capturar dato enviado
        $catNombre = $request->catNombre;
        //validar
        $this->validarForm( $request );
        //instanciar+asignar atributo
        $Categoria = new Categoria;
        $Categoria->catNombre = $catNombre;
        //guardar
        $Categoria->save();
        //retorno de resultado con flashing
        return redirect('/categorias')
               ->with(['mensaje'=>'Categoría: '.$catNombre.' agregada correctamente']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Categoria = Categoria::find($id);

        return view('/categoriaEdit', [ 'Categoria'=>$Categoria ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request )
    {
        $catNombre = $request->catNombre;
        $this->validarForm( $request );
        $Categoria = Categoria::find($request->idCategoria);
        $Categoria->catNombre = $catNombre;
        $Categoria->save();
        return redirect('/categorias')
               ->with([ 'mensaje'=>'La categoría '.$catNombre.' ha sido modificada correctamente' ]);
    }

        private function productoPorCategoria($id)
        {
            $check = Producto::firstWhere('idCategoria', $id);
            return $check;
        }


    public function confirm($id)
    {
        $Categoria = Categoria::find($id);
        if(!$this->productoPorCategoria($id))
        {
            return view('/categoriaDelete', [ 'Categoria'=>$Categoria ]);
        }
        return redirect('/categorias')
               ->with(
                   [
                    'warning'=>'warning',
                    'mensaje'=>'La categoría '.$Categoria->catNombre.' no puede eliminarse por tener productos relacionados'    
                   ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request )
    {
        Categoria::destroy($request->idCategoria);
        return redirect('/categorias')
               ->with(['mensaje'=>'La categoría '.$request->catNombre.' ha sido eliminada']);
    }
}
