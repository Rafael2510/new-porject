<?php


namespace App\Repositories\Transaction;


use App\Events\SendNotification;
use App\Exceptions\WithoutMoneyException;
use App\Exceptions\IdleServiceException;
use App\Exceptions\InvalidUserException;
use App\Exceptions\TransactionDeniedException;
use App\Http\Controllers\Controller;
use App\Models\Retailer;
use App\Models\Transactions\Transaction;
use App\Models\Transactions\Wallet;
use App\Models\User;
use App\Services\MockyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\InvalidDataProviderException;
use Ramsey\Uuid\Uuid;
 
class TransactionRepository 
{
   
    public function handle(array $transactionInfo, User $user): Transaction
    {
        if (!$this->userCanTransfer($user)) {
            throw new TransactionDeniedException('Shopkeepers are not authorized to make transactions', 411);
        }
        
        $myBalance = Auth::user()->wallet;

        if (!$this->UserHasEnoughMoneyToTransaction($myBalance, $transactionInfo['amount'])) {
            throw new WithoutMoneyException('You dont have this amount to transfer.', 422);
        }
       
        if (!$this->areServiceWorkingToMakeTransaction()) {
            throw new IdleServiceException('Service is not responding. Try again later.', 433);
        }

        return $this->makeTransaction($transactionInfo);
    }

    public function userCanTransfer(User $user): bool
    {
        return $user['type'] != 'shopkeeper';
    }

    private function UserHasEnoughMoneyToTransaction(Wallet $wallet, $money)
    {
        return $wallet->balance >= $money;
    }

    private function areServiceWorkingToMakeTransaction(): bool
    {
        $service = app(MockyService::class)->authorizeTransaction();
        return $service['message'] == 'Autorizado';
    }

    
    private function makeTransaction($data)
    {
        $payload = [
            'id' => Uuid::uuid4()->toString(),
            'payee_wallet_id' => $data['payee_wallet_id'],
            'payer_wallet_id' => $data['payer_wallet_id'],
            'amount' => $data['amount']
        ];

        return DB::transaction(function () use ($payload) {
            $transaction = Transaction::create($payload);
            $transaction->walletPayer->withdraw($payload['amount']);
            $transaction->walletPayee->deposit($payload['amount']);

            event(new SendNotification($transaction));

        });
        
    }
}
