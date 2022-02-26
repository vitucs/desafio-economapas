<?php

namespace App\Services;

use App\Helpers\Enum\RoleEnum;
use App\Messages\UserMessages;
use App\Models\Groups;
use App\Models\User;
use App\Repositories\GroupRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class GroupService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new GroupRepository();
    }

    /**
     * List All
     */
    public function listAll()
    {
        return $this->repository->getAllGroups();
    }

    /**
     * Update Registre
     *
     * @param array $data
     * @param integer $id
     * @return boolean
     */
    
    public function getLastGroupId()
    {
        return $this->repository->getLastGroupId();
    }


}
