<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanProduct extends Model
{

    protected $casts = [
        'interest_rate' => 'decimal:2',
    ];

    protected $fillable = [
        'name',
        'code',
        'minimum_amount',
        'maximum_amount',
        'interest_rate',
        'currency',
    ];
    public function loanApplications(): HasMany
    {
        return $this->hasMany(LoanApplication::class);
    }

    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($loanProduct) {
            $loanProduct->code = self::generateUniqueCode();
        });
    }


    private static function generateUniqueCode()
    {
        $code = 'LP' . strtoupper(substr(md5(uniqid()), 0, 6));
        while (LoanProduct::where('code', $code)->exists()) {
            $code = 'LP' . strtoupper(substr(md5(uniqid()), 0, 6));
        }

        return $code;
    }
}
