<?php

namespace App\Http\Controllers;

use App\Exceptions\WithoutMoneyException;
use App\Exceptions\IdleServiceException;
use App\Exceptions\InvalidUserException;
use App\Exceptions\TransactionDeniedException;
use App\Models\Transactions\Wallet;
use App\Models\User;
use App\Repositories\Transaction\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionsController extends Controller
{
    private $transactionRepository;

    private $userModel;

    private $walletModel;

    public function __construct(
        TransactionRepository $transactionRepository, User $userModel, 
        Wallet $walletModel)
    {
        $this->transactionRepository = $transactionRepository;
        $this->userModel = $userModel;
        $this->walletModel = $walletModel;
    }

    public function validateTransaction(Request $request)
    {
        $user = Auth::user();

        $walletPayer = $this->walletModel->getWalletByUserId(Auth::user()->id);

        $this->validate($request,[
            'document_payee' => 'required',
            'value' => 'required'
        ]);

        $data = $request->only(['document_payee', 'value']);
        
        $WalletPayee = $this->GetwalletByDocument($data);

        $transactionInfo = [
            'payee_wallet_id' => $WalletPayee,
            'payer_wallet_id' => $walletPayer['id'],
            'amount' => $data['value']
        ];
        
        try {
            $result = $this->transactionRepository->handle($transactionInfo, $user);
            return response()->json($result);
        } catch ( WithoutMoneyException $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], $exception->getCode());
        } catch (TransactionDeniedException $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], $exception->getCode());
        } catch (IdleServiceException $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], $exception->getCode());
        } catch (\Exception $exception) {
            Log::critical('[Transaction Gone Wrong]', [
                'message' => $exception->getMessage()
            ]);
        }
     
    }

    private function GetwalletByDocument($data)
    {
        if($payeeId = $this->userModel->getUserIdByDocument($data['document_payee']))
        {
            return $this->walletModel->getWalletByUserId($payeeId);
        }
        throw new InvalidUserException();
        
    }
          
}
