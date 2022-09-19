<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAttrRegistrosRelacionados($attr)
    {
        $this->model = $this->model->with($attr);
    }

    public function filtro($filtros)
    {
        $filtros = explode(';',$filtros);
        foreach ($filtros as $key => $condicao) {
            $c = explode(':',$condicao);
            $this->model = $this->model->where($c[0],$c[1],$c[2]);   
        }
    }

    public function selectAttr($attr)
    {
        $this->model = $this->model->selectRaw($attr);
    }

    public function getResult()
    {
        return $this->model->get();
    }
}