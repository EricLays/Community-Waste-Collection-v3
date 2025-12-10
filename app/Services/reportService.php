<?php
namespace App\Services;

use App\Repositories\ReportRepository;

class ReportService
{
    protected ReportRepository $repo;

    public function __construct(ReportRepository $repo)
    {
        $this->repo = $repo;
    }

    public function wasteSummary()
    {
        return $this->repo->wasteSummary();
    }

    public function paymentSummary()
    {
        return $this->repo->paymentSummary();
    }

    public function householdHistory($id)
    {
        return $this->repo->householdHistory($id);
    }
}
