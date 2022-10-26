<?php

namespace App\Http\Controllers;

use App\Models\Loja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LojaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lojas = Loja::all(['id', 'nome', 'email']);
        return response()->json($lojas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:40|min:3',
            'email' => 'required|email|unique:lojas,email',
        ], [
            'required' => 'O campo :attribute é necessário',
            'string'    => 'O campo :attribute deve ser formato texto',
            'max'      => 'O campo :attribute deve ter tamanho máximo de :max',
            'min'      => 'O campo :attribute deve ter tamanho mínimo de :min',
            'email'      => 'O campo :attribute deve ser um email válido',
            'unique'    => 'O :attribute já existe'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toArray(), 400);
        }
        $loja = new Loja();
        $loja->nome = $request->nome;
        $loja->email = $request->email;
        $loja->save();

        return response()->json([ 'success' => 'true' ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loja  $loja
     * @return \Illuminate\Http\Response
     */
    public function show(Loja $loja)
    {
        // TODO: deverá retornar todos os produtos
        $loja = Loja::where('id', $loja->id)->with('produtos')->first();
        return response()->json($loja);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loja  $loja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loja $loja)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'string|max:40|min:3',
            'email' => 'email|unique:lojas,email',
        ], [
            'required' => 'O campo :attribute é necessário',
            'string'    => 'O campo :attribute deve ser formato texto',
            'max'      => 'O campo :attribute deve ter tamanho máximo de :max',
            'min'      => 'O campo :attribute deve ter tamanho mínimo de :min',
            'email'      => 'O campo :attribute deve ser um email válido',
            'unique'    => 'O :attribute já existe'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toArray(), 400);
        }
        $loja->nome = $request->nome ?? $loja->nome;
        $loja->email = $request->email ?? $loja->email;
        $loja->save();

        return response()->json([ 'success' => 'true' ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loja  $loja
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loja $loja)
    {
        //
        $loja->delete();
    }
}
