<?php

namespace Tests\Feature\app\Http\Controllers;
use App\Models\Transactions\Wallet;
use App\Models\User;
use App\Repositories\Transaction\TransactionRepository;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;


class TransactionsControllerTest extends TestCase
{
    protected $transactionRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionRepository = new TransactionRepository();
    }

    //php artisan test --filter=TransactionsControllerTest

    public function testuserCanTransfer() //php artisan test --filter=TransactionsControllerTest::testuserCanTransfer
    {
        $user = User::factory()->create();
        
        $transaction = $this->transactionRepository->userCanTransfer($user);

        $this->assertTrue($transaction);
    }

    public function testUserHasNoMoney() //php artisan test --filter=TransactionsControllerTest::testUserHasNoMoney
    {
        $user = User::factory()->create();

        Wallet::create([
            'id' => Uuid::uuid4()->toString(),
            'user_id' => $user['id'],
            'balance' => '0'
        ]);

        $payload = [
            'document_payee' => '11111111111',
            'value' => 100
        ];

        $request = $this->actingAs($user)
            ->post(route('validate.transaction'), $payload);
        $request->assertStatus(422);

    }

    public function testPayeeDoesNotExist() //php artisan test --filter=TransactionsControllerTest::testPayeeDoesNotExist
    {
        $user = User::factory()->create();

        Wallet::create([
            'id' => Uuid::uuid4()->toString(),
            'user_id' => $user['id'],
            'balance' => '1000'
        ]);

        $payload = [
            'document_payee' => '99999999999',
            'value' => 100
        ];

        $request = $this->actingAs($user)
            ->post(route('validate.transaction'), $payload);
        $request->assertStatus(441);

    }

    public function testValidadeTransaction() // php artisan test --filter=TransactionsControllerTest::testPayeeDoesNotExist
    {
        
        $user = User::factory()->create();
        
        Wallet::create([
            'id' => Uuid::uuid4()->toString(),
            'user_id' => $user['id'],
            'balance' => '1000'
        ]);

        $payload = [
            'document_payee' => '11111111111',
            'value' => 100
        ];
        
        $request = $this->actingAs($user)
            ->post(route('validate.transaction'), $payload);
        $request->assertStatus(200);

    }
    
}
