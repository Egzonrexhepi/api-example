<?php

namespace App\Http\Controllers;

use App\Constants\ActionTypes;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\Customer;
use App\Repository\Eloquent\ActionRepository;
use App\Repository\Eloquent\CustomerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    private $customerRepository;
    private $actionRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        ActionRepository $actionRepository
    )
    {
        $this->customerRepository = $customerRepository;
        $this->actionRepository = $actionRepository;
    }

    /**
     * Store Customer
     * @param StoreCustomerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCustomerRequest $request)
    {
        $data = $request->all();
        $data['bonus_percentage'] = randomBonusPercentage();

        $result = $this->customerRepository->create($data);

        if (!$result->id) {
            return response()->json([
                'success' => false
            ], 200);
        }

        return response()->json([
            'success' => true,
            'Id' => $result->id
        ]);
    }

    /**
     * Update Customer
     * @param UpdateCustomerRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ]);
        }

        $result = $this->customerRepository->update($request->all(), $id);

        return response()->json([
            'success' => $result ? true : false
        ]);
    }

    /**
     * Deposit from customer Account
     * @param DepositRequest $request
     * @param $customerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deposit(DepositRequest $request, $customerId)
    {
        $amount = $request->get('amount');
        $customer = Customer::find($customerId);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ]);
        }

        try {
            DB::beginTransaction();

            $result = $this->customerRepository->deposit($customer, $amount, $customer->deposit_count == 2 ? true : false);

            if ($result) {
                $actionData = [
                    'customer_id' => $customerId,
                    'amount' => $amount,
                    'date' => Carbon::now(),
                    'type' => ActionTypes::DEPOSIT
                ];

                if (!$this->actionRepository->insert($actionData)) {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to complete deposit'
                    ], 200);
                }
            } else {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to complete deposit'
                ], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete deposit',
                'exception' => $e->getMessage()
            ], 200);
        }

        DB::commit();

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * Withdraw from Customer Account
     * @param WithdrawRequest $request
     * @param $customerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(WithdrawRequest $request, $customerId)
    {
        $amount = $request->get('amount');
        $customer = Customer::find($customerId);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 200);
        }

        if ($customer->balance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insuficient funds, withdrawable balance: ' . $customer->balance
            ], 200);
        }

        DB::beginTransaction();

        try {

            $result = $this->customerRepository->withdraw($customer, $amount);

            if (!$result) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to complete withdraw action'
                ], 200);
            }

            $actionData = [
                'customer_id' => $customerId,
                'amount' => $amount,
                'date' => Carbon::now(),
                'type' => ActionTypes::WITHDRAW
            ];

            $this->actionRepository->insert($actionData);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed complete withdraw',
                'Exception' => $e->getMessage()
            ], 200);
        }

        Db::commit();

        return response()->json([
            'success' => true,
        ], 200);
    }
}
