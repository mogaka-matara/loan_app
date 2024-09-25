<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{

    protected $fillable = [
        'name',
        'dob',
        'email',
        'phone',
        'id_number',
        'address',
    ];
    public function loanApplications(): HasMany
    {
        return $this->hasMany(LoanApplication::class);
    }

    use HasFactory;
}
