<?php

namespace App\Http\Controllers;

use App\Exports\ActionsExport;
use App\Repository\Eloquent\ReportsRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    private $reportsRepository;

    public function __construct(ReportsRepository $reportsRepository)
    {
        $this->reportsRepository = $reportsRepository;
    }

    public function generateExcel()
    {
        return Excel::download(new ActionsExport($this->reportsRepository), 'list.xlsx');
    }

    public function list() {
        return response()->json([
            'success' => true,
            'data' => $this->reportsRepository->generate()
        ],200);
    }
}
