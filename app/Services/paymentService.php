<?php

namespace App\Services;

use App\Repositories\PaymentRepository;

class PaymentService
{
    protected PaymentRepository $repo;

    public function __construct(PaymentRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create(array $data)
    {
        $data['status'] = 'pending';
        $data['payment_date'] = now();

        return $this->repo->create($data);
    }

    public function list(array $filters)
    {
        return $this->repo->filter($filters);
    }

    public function confirm(string $id)
    {
        return $this->repo->update($id, [
            'status' => 'paid',
            'payment_date' => now(),
        ]);
    }
}
