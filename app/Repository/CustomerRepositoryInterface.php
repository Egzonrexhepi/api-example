<?php


namespace App\Repository;


interface CustomerRepositoryInterface
{

    public function create($data);
    public function update($data, $customerId);
    public function deposit($amount, $customerId, $bonus = false);
    public function withdraw($customer, $amount);

}