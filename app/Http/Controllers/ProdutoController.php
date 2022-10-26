<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produtos = Produto::all(['id', 'nome', 'valor', 'loja_id', 'ativo']);
        return response()->json($produtos);
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
            'nome' => 'required|string|max:60|min:3',
            'valor' => 'required|digits_between:2,6',
            'loja_id' => 'min:1',
            'ativo' => 'required|boolean'
        ], [
            'required' => 'O campo :attribute é necessário',
            'string' => 'O campo :attribute deve ser formato texto',
            'max' => 'O campo :attribute deve ter tamanho máximo de :max',
            'min' => 'O campo :attribute deve ter tamanho mínimo de :min',
            'digits_between' => 'O campo :attribute deve ter :digits_between dígitos',
            'unique' => 'O :attribute já existe',
            'boolean' => 'O :attribute deverá ser verdadeiro ou falso'
        ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(),400);
        $produto = new Produto();
        $produto->nome = $request->nome;
        $produto->valor = $request->valor;
        $produto->loja_id = $request->loja_id ?? null;
        $produto->ativo = $request->ativo;
        $produto->save();

        $loja = \App\Models\Loja::find($produto->loja_id);
        Mail::raw("Produto criado!", function ($m) use ($loja) {
            $m->to($loja->email)->subject('Teste');
        });
        print_r($loja ? "true" : "false");
        return response()->json([ 'success' => 'true' ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function show(Produto $produto)
    {
        return response()->json($produto);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Produto $produto)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'string|max:60|min:3',
            'valor' => 'digits_between:2,6',
            'loja_id' => 'min:1',
            'ativo' => 'boolean'
        ], [
            'string' => 'O campo :attribute deve ser formato texto',
            'max' => 'O campo :attribute deve ter tamanho máximo de :max',
            'min' => 'O campo :attribute deve ter tamanho mínimo de :min',
            'digits_between' => 'O campo :attribute deve ter :digits_between dígitos',
            'unique' => 'O :attribute já existe',
            'boolean' => 'O :attribute deverá ser verdadeiro ou falso'
        ]);
        if($validator->fails())
            return response()->json($validator->errors()->toArray(),400);
        $produto->nome = $request->nome ?? $produto->nome;
        $produto->valor = $request->valor ?? $produto->valor;
        $produto->loja_id = $request->loja_id ?? $produto->loja_id;
        $produto->ativo = $request->ativo ?? $produto->ativo;
        $produto->save();

        $loja = \App\Models\Loja::find($produto->loja_id);
        Mail::raw("Produto atualizado!", function ($m) use ($loja) {
            $m->to($loja->email)->subject('Teste');
        });

        return response()->json([ 'success' => 'true' ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produto $produto)
    {
        $produto->delete();
    }
}
