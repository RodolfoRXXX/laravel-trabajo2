<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //obtener listado de marcas
        $marcas = Marca::orderBy('mkNombre')->paginate(3);
        return view('marcas', [ 'marcas'=>$marcas ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/marcaCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
     private function validarForm( Request $request )
     {
        $request->validate(
            ['mkNombre'=>'required|min:2|max:20'],
            [
                'mkNombre.required'=>'El campo nombre es obligatorio',
                'mkNombre.min'=>'El nombre debe tener al menos 2 caracteres',
                'mkNombre.max'=>'El nombre debe tener menos de 10 caracteres'
            ]
        );
     }
     /* @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //capturar dato enviado
            $mkNombre = $request->mkNombre;
        //validar
            $this->validarForm( $request );
        //instanciar+asignar atributo
            $Marca = new Marca;
            $Marca->mkNombre = $mkNombre;
        //guardar en base de datos
            $Marca->save();
        //retorno con flashing de mensaje
        return redirect('/marcas')
                ->with(['mensaje'=>'Marca: '.$mkNombre.' agregada correctamente']);
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
        //obtener datos de una marca filtrado por su id
        $Marca = Marca::find( $id );

        //retornar vista del formulario pasándole los datos
        return view('marcaEdit', [ 'Marca'=>$Marca ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //obtener los valores del formulario
        $mkNombre = $request->mkNombre;
        //validación
        $this->validarForm( $request );
        //obtener el valor del registro de la db para modificarlo
        $Marca = Marca::find( $request->idMarca );
        //modifica los cambios actualizados
        $Marca->mkNombre = $mkNombre;
        //guarda los cambios
        $Marca->save();

        return redirect('/marcas')
                ->with(['mensaje'=>'Marca: '.$mkNombre.' agregada correctamente']);
    }

    private function productoPorMarca($id)
    {
        //$check = Producto::where('idMarca', $id)->first(); //devuelve uel primer objeto encontrado
        $check = Producto::firstWhere('idMarca', $id);  //devuelve el primer objeto encontrado
        //$check = Producto::where('idMarca', $id)->count(); //devuelve la cantidad de objetos que coincidan con el dato
            return $check;
    }

    public function confirm($id)
    {
        //obtenemos datos de la marca
        $Marca = Marca::find($id);

        //si NO hay productos de esa marca
            if(!$this->productoPorMarca($id))
            {
                return view('marcaDelete', [ 'Marca'=>$Marca ]);
            }

        //si hay productos de esa marca
                return redirect('/marcas')
                        ->with(
                            [
                            'warning'=>'warning',
                            'mensaje'=>'La marca: '.$Marca->mkNombre.' no se puede eliminar ya que tiene productos relacionados'
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
        Marca::destroy( $request->idMarca );
        return redirect('/marcas')
               ->with(['mensaje'=>'La marca: '.$request->mkNombre.' ha sido eliminada.']);
    }
}
