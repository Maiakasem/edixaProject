<?php

namespace App\Services;

class BaseService
{
    /**
     * Create a new class instance.
     */
    public $repo;
    public function __construct($repo)
    {
        $this->repo = $repo;
    }
    public function all()
    {
        return $this->repo->all();
    }

    public function create($data)
    {
    
        return $this->repo->create($data);
    }

    public function update($data, $id)
    {
        return $this->repo->update($data, $id);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
    public function getPaginated($request) 
    {
        return $this->repo->getPaginated($request);
    }
    public function translateFields()
    {
        return $this->repo->translateFields();
    }
    public function columnsFields()
    {
        return $this->repo->columnsFields();
    }
    
}
