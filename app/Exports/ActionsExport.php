<?php

namespace App\Exports;

use App\Repository\Eloquent\ReportsRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActionsExport implements FromCollection, WithHeadings
{
    private $repository;

    public function __construct(ReportsRepository $reportsRepository)
    {
        $this->repository = $reportsRepository;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->repository->generate());
    }

    public function headings() :array
    {
        return [
            'Date',
            'Country',
            'Unique Costumers',
            'No of Deposits',
            'Total Deposit Amount',
            'No of withdrawals',
            'No of withdrawals',
            'Total Withdrawal Amount'
        ];
    }
}
