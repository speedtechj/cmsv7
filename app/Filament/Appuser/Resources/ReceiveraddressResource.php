<?php

namespace App\Filament\Appuser\Resources;

use App\Filament\Appuser\Resources\ReceiveraddressResource\Pages;
use App\Filament\Appuser\Resources\ReceiveraddressResource\RelationManagers;
use App\Models\Receiveraddress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceiveraddressResource extends Resource
{
    protected static ?string $model = Receiveraddress::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListReceiveraddresses::route('/'),
            'create' => Pages\CreateReceiveraddress::route('/create'),
            'edit' => Pages\EditReceiveraddress::route('/{record}/edit'),
        ];
    }
}
