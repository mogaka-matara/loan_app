<?php

namespace App\Filament\Widgets;

use App\Models\LoanApplication;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Loans', LoanApplication::where('status', 'pending')->count())
                ->description('Loans awaiting approval')
                ->color('warning'),

            Stat::make('Approved Loans', LoanApplication::where('status', 'approved')->count())
                ->description('Loans approved by admin')
                ->color('success'),

            Stat::make('Disbursed Loans', LoanApplication::where('status', 'disbursed')->count())
                ->description('Loans disbursed to customers')
                ->color('info'),

            Stat::make('Repaid Loans', LoanApplication::where('status', 'repaid')->count())
                ->description('Loans fully repaid')
                ->color('success'),

            Stat::make('Total Amount Applied', number_format(LoanApplication::sum('amount_applied') / 100, 2))
                ->description('Sum of all loan applications')
                ->color('primary'),

            Stat::make('Total Amount Disbursed', number_format(LoanApplication::sum('amount_disbursed') / 100, 2))
                ->description('Sum of all disbursed loans')
                ->color('info'),

            Stat::make('Total Amount Repaid', number_format(LoanApplication::sum('amount_repaid') / 100, 2))
                ->description('Sum of all repayments')
                ->color('success'),

            Stat::make('Total Amount Balance', number_format(LoanApplication::sum('balance') / 100, 2))
                ->description('Sum of outstanding balances')
                ->color('danger'),



        ];
    }


}
