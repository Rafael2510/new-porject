<?php


namespace App\Models\Transactions;


use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $incrementing = false;

    protected $table = 'wallet_transactions';

    protected $fillable = [
        'id',
        'payee_wallet_id',
        'payer_wallet_id',
        'amount'
    ];

    public function walletPayer()
    {
        return $this->belongsTo(Wallet::class,  'payer_wallet_id');
    }

    public function walletPayee()
    {
        return $this->belongsTo(Wallet::class,  'payee_wallet_id');
    }

    
    public function getAll($return = 'get', $paginate = '*', $ignoreId = false)
    {
        $query = $this;

        if($ignoreId)
        {
            $query = $query->where('id', '!=', $ignoreId);
        }

        if(strpos($paginate, ','))
        {
            list($param1, $param2) = explode(',', $paginate);
            $param1 = str_replace(' ', '', $param1);
            $param2 = str_replace(' ', '', $param2);

            return $query
                    ->$return($param1, $param2);
        }

        return $query->latest()
                    ->$return($paginate);
    }

    public function getLatest($return = 'get', $paginate = '*')
    {
        return $this->getAll('orderBy', 'created_at, DESC')
                ->$return($paginate);
    }
}
