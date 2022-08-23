<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    
    public function __construct(Marca $marca){
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos_modelos')){
            $atributos_modelos = 'modelos:id,' . $request->atributos_modelos;
            $marcaRepository->selectAttrRegistrosRelacionados($atributos_modelos);
        }else{
            $marcaRepository->selectAttrRegistrosRelacionados('modelos');
        }

        if($request->has('filtro')){
            $marcaRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $marcaRepository->selectAttr($request->atributos);
        }
        
        return response()->json($marcaRepository->getResult(),200);

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request->validate($this->marca->rules(),$this->marca->feedback());
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens','public');
        

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

        return response()->json($marca,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'],404);
        }
        return response()->json($marca,200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $marca = $this->marca->find($id);
        //dd($request->file('imagem'));
        if($marca === null){
            return response()->json(['erro' => 'Não foi possivel atualizar'],404);
        }
        
        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            foreach ($marca->rules() as $input => $regra) {
                if(array_key_exists($input,$request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas,$marca->feedback());
        }else{
            $request->validate($marca->rules(),$marca->feedback());
        }

        //remove o arquivo antigo cado um novo arquivo tenha sido enviado no request
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens','public');    

        $marca->fill($request->all());
        $marca->imagem = $imagem_urn;
        $marca->save();

        /* $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]); */

        return response()->json($marca,200); 
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Não foi possivel deletar'],404);
        }

        //remove o arquivo antigo
        
        Storage::disk('public')->delete($marca->imagem);
        

        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso!'],200);
        
    }
}
