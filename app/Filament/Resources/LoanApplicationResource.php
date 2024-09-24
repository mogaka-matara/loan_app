<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanApplicationResource\Pages;
use App\Filament\Resources\LoanApplicationResource\RelationManagers;
use App\Models\LoanApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;

class LoanApplicationResource extends Resource
{
    protected static ?string $model = LoanApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 2;


    protected static ?string $navigationGroup ='Loans System';

    protected static ?string $label = 'Loan Applications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
//                    ->relationship('user', 'name')
                    ->relationship('user', 'name', function (Builder $query) {
                        $query->where('id', '!=', 1);
                    })
                    ->required(),
                Forms\Components\Select::make('loan_product_id')
                    ->relationship('loanProduct', 'name')
                    ->required(),
                MoneyInput::make('amount_applied')
                    ->currency('KES')
                    ->locale('sw-KE')
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                    ->required(),
                MoneyInput::make('amount_disbursed')
                    ->currency('KES')
                    ->locale('sw-KE')
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                    ->required(),
                MoneyInput::make('amount_repaid')
                    ->default('0.00')
                    ->currency('KES')
                    ->locale('sw-KE')
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                    ->required(),
                MoneyInput::make('balance')
                    ->currency('KES')
                    ->locale('sw-KE')
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                    ->disabled(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'disbursed' => 'Disbursed',
                        'repaid' => 'Repaid',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('user.name')->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('loanProduct.name')->label('Loan Product'),
                MoneyColumn::make('amount_applied')
                    ->currency('KES')
                    ->locale('en_KE')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('amount_disbursed'),
                MoneyColumn::make('amount_disbursed')
                    ->currency('KES')
                    ->locale('en_KE')
                    ->sortable()
                    ->toggleable(),
                MoneyColumn::make('amount_repaid')
                    ->currency('KES')
                    ->locale('en_KE')
                    ->sortable()
                    ->toggleable(),
                MoneyColumn::make('balance')
                    ->currency('KES')
                    ->locale('en_KE')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->colors([
                        'primary' => 'Pending',
                        'secondary' => 'Approved',
                        'success'   => 'Disbursed',
                        'danger'    => 'Repaid',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),


                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoanApplications::route('/'),
            'create' => Pages\CreateLoanApplication::route('/create'),
            'edit' => Pages\EditLoanApplication::route('/{record}/edit'),
        ];
    }


}
