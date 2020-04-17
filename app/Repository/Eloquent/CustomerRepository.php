<?php


namespace App\Repository\Eloquent;


use App\Models\Customer;
use App\Repository\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    private $model;

    public function __construct(Customer $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($data, $customerId)
    {
        return $this->model->find($customerId)->update($data);
    }

    public function withdraw($customer, $amount)
    {
        $customer->balance = $customer->balance - $amount;

        return $customer->save();
    }

    public function deposit($customer, $amount, $bonus = false)
    {
        $customer->balance = $customer->balance + $amount;

        if ($bonus) {
            $customer->bonus_balance = $customer->bonus_balance + ($amount * $customer->bonus_percentage);
            $customer->deposit_count = 0;
            return $customer->save();
        }

        $customer->deposit_count++;

        return $customer->save();
    }
}