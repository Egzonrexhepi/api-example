<?php


namespace App\Repository\Eloquent;


use App\Models\Action;
use App\Repository\ActionRepositoryInterface;

class ActionRepository implements ActionRepositoryInterface
{
    private $model;

    public function __construct(Action $model)
    {
        $this->model = $model;
    }

    public function insert($data)
    {
       return $this->model->create($data);
    }
}