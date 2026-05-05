<?php

namespace App\Models;

use App\Enums\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'document_number',
        'type',
        'id_program_kerja',
        'id_user',
        'cash_account_id',
        'transaction_account_id',
        'reference',
        'amount',
        'description',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'type' => TransactionTypeEnum::class,
        'amount' => 'decimal:2',
    ];

    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'id_program_kerja', 'id_program_kerja');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function cashAccount()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'cash_account_id', 'id');
    }

    public function transactionAccount()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'transaction_account_id', 'id');
    }

    public function generalLedgers()
    {
        return $this->hasMany(GeneralLedger::class, 'transaction_id');
    }

    public static function generateDocumentNumber(TransactionTypeEnum $type): string
    {
        $prefix = $type === TransactionTypeEnum::IN ? 'BKMUDD' : 'BKKUDD';

        $lastTransaction = self::where('type', $type)
            ->where('document_number', 'like', $prefix . '%')
            ->orderByDesc('document_number')
            ->first();

        if ($lastTransaction) {
            $sequence = (int) substr($lastTransaction->document_number, strlen($prefix));
            $nextSequence = str_pad($sequence + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextSequence = '001';
        }

        return $prefix . $nextSequence;
    }
}
