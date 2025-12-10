<?php

namespace App\Services;

use App\Repositories\HouseholdRepository;

class HouseholdService
{
    protected $repo;

    public function __construct(HouseholdRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function list(array $filters)
    {
        return $this->repo->search($filters);
    }

    public function detail($id)
    {
        return $this->repo->find($id);
    }

    public function update($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}
