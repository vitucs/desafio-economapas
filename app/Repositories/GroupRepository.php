<?php

namespace App\Repositories;

use App\Models\Groups;

class GroupRepository extends Eloquent\AbstractRepository
{
    protected $model = Groups::class;

    public function getAllGroups()
    {
        return $this->model::query()
            ->get();
    }

    public function getLastGroupId()
    {
        
        if($this->model::query()->exists()==0)
            return '1';
        else
            return $this->model::query()
            ->orderBy('id', 'DESC')
            ->first();
    }
}
