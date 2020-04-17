<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test costumer creation
     */
    public function testRegister()
    {
        $customer = [
            'first_name' => 'Egzon',
            'last_name' => 'Rexhepi',
            'email' => 'e.rexhepi2@gmail.com',
            'gender' => 'm',
            'country' => 'KS'
        ];

        $response = $this->post('/api/customer', $customer);

        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    /**
     * Test customer update
     */
    public function testUpdate()
    {
        $customer = factory(Customer::class)->create();

        $updatedCustomer = [
            'first_name' => 'Egzon',
            'last_name' => 'Rexhepi',
            'email' => 'e.rexhepi2@gmail.com',
            'gender' => 'm',
            'country' => 'KS'
        ];

        $response = $this->put('/api/customer/' . $customer->id, $updatedCustomer);

        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    /**
     * Test deposit to balance
     */
    public function testDeposit()
    {
        $customer = factory(Customer::class)->create();

        $response = $this->post('api/customer/deposit/' . $customer->id, [
            'amount' => 100
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    /**
     * Every third deposit costumer gets a bonus based on his bonus percentage
     * which is assigned on costumer registration
     */
    public function testThirdDeposit()
    {
        $customer = factory(Customer::class)->create();

        $customer->deposit_count = 2; // assume this is third deposit

        $customer->save();

        $amount = 100;
        $bonusAmount = floatval($amount * $customer->bonus_percentage);

        $this->post('api/customer/deposit/' . $customer->id, [
            'amount' => $amount
        ]);

        $updatedCustomer = Customer::find($customer->id);

        $this->assertEquals($updatedCustomer->bonus_balance, $bonusAmount);
    }

    /**
     * Test withdraw balance
     */
    public function testWithdraw()
    {
        $customer = factory(Customer::class)->create();

        $customer->balance = 100;
        $customer->bonus_balance = 15;

        $customer->save();

        $response = $this->post('api/customer/withdraw/' . $customer->id, [
            'amount' => 50
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    /**
     * Test withdraw when no funds
     */
    public function testNoFundsWithdraw()
    {
        $customer = factory(Customer::class)->create();

        $customer->balance = 100;
        $customer->bonus_balance = 15;

        $customer->save();

        $response = $this->post('api/customer/withdraw/' . $customer->id, [
            'amount' => 200
        ]);

        $response->assertStatus(200)->assertJson(['success' => false]);
    }

    /**
     * Test wihdrawing bonus balance
     */
    public function testWithdrawBonusBalance()
    {
        $customer = factory(Customer::class)->create();

        $customer->balance = 0;
        $customer->bonus_balance = 15;

        $customer->save();

        $response = $this->post('api/customer/withdraw/' . $customer->id, [
            'amount' => 15
        ]);

        $response->assertStatus(200)->assertJson(['success' => false]);
    }
}
