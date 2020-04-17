<?php


namespace App\Repository;


interface ReportsRepositoryInterface
{
    public function generate($from = null,$to = null);
}