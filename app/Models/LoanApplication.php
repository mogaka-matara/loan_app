<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loan_product_id',
        'amount_applied',
        'amount_disbursed',
        'amount_repaid',
        'balance',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loanProduct(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($loanApplication) {

            if (!$loanApplication->relationLoaded('loanProduct')) {
                $loanApplication->load('loanProduct');
            }

            // Check for existing loans with outstanding balance
            $outstandingLoan = LoanApplication::where('user_id', $loanApplication->user_id)
                ->where('balance', '>', 0)
                ->where('id', '!=', $loanApplication->id)
                ->exists();

            if ($outstandingLoan) {
                Notification::make()
                    ->title('Validation error')
                    ->danger()
                    ->body('You cannot apply for a new loan while having an outstanding balance')
                    ->send();
                throw ValidationException::withMessages([
                    'loan' => 'You cannot apply for a new loan while having an outstanding balance.',
                ]);
            }


            $loanProduct = $loanApplication->loanProduct;
           // dd($loanApplication->amount_applied, $loanProduct->minimum_amount, $loanProduct->maximum_amount);
            if ($loanApplication->amount_applied < $loanProduct->minimum_amount || $loanApplication->amount_applied > $loanProduct->maximum_amount) {
                Notification::make()
                    ->title('Validation error')
                    ->danger()
                    ->body("Loan must be within the Ksh. " . $loanProduct->minimum_amount / 100 . " and Ksh. "
                        . $loanProduct->maximum_amount / 100 . " of product limit")
                    ->send();

                throw ValidationException::withMessages([
                    'amount_applied' => "Loan must be within the min and max of product limit"
                ]);
            }

            if ($loanProduct && $loanProduct->interest_rate) {
                $interest = $loanApplication->amount_applied * ($loanProduct->interest_rate / 100);
                $totalWithInterest = $loanApplication->amount_applied + $interest;
                $loanApplication->balance = $totalWithInterest - $loanApplication->amount_repaid;
            } else {
                $loanApplication->balance = $loanApplication->amount_disbursed - $loanApplication->amount_repaid;
            }
        });
    }
}
