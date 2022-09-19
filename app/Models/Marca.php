<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome','imagem'];

    public function rules(){
        return [
            'nome' => 'required|unique:marcas',
            'imagem' => 'required|file|mimes:png'
        ];
    }

    public function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da marca já existe',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo png'
        ];
    }

    public function modelos(){
        // Uma marca poussi muitos modelos
        return $this->hasMany('App\Models\Modelo');
    }
}
