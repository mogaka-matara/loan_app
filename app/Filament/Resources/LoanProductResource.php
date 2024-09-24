<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanProductResource\Pages;
use App\Filament\Resources\LoanProductResource\RelationManagers;
use App\Models\LoanProduct;
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

class LoanProductResource extends Resource
{
    protected static ?string $model = LoanProduct::class;


    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?int $navigationSort = 0;


    protected static ?string $navigationGroup ='Loans System';

    protected static ?string $label = 'Loan Products';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->disabled()
                    ->maxLength(255),
                MoneyInput::make('minimum_amount')
                    ->currency('KES')
                    ->locale('sw-KE')
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                    ->required(),

                MoneyInput::make('maximum_amount')
                    ->currency('KES')
                    ->locale('sw-KE')
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->rules('regex:/^\d{1,6}(\.\d{0,2})?$/')
                    ->required(),
                Forms\Components\TextInput::make('interest_rate')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('minimum_amount'),
                MoneyColumn::make('minimum_amount')
                    ->currency('KES')
                    ->locale('en_KE')
                    ->sortable()
                    ->toggleable(),

                MoneyColumn::make('maximum_amount')
                    ->currency('KES')
                    ->locale('en_KE')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make()
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
            'index' => Pages\ListLoanProducts::route('/'),
            'create' => Pages\CreateLoanProduct::route('/create'),
            'edit' => Pages\EditLoanProduct::route('/{record}/edit'),
        ];
    }
}
