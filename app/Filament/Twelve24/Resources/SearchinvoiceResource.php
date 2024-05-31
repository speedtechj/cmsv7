<?php

namespace App\Filament\Twelve24\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Searchinvoice;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Appuser\Resources\SenderResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\ReceiverResource;
use App\Filament\Appuser\Resources\SenderaddressResource;
use App\Filament\Twelve24\Resources\SearchinvoiceResource\Pages;
use App\Filament\Twelve24\Resources\SearchinvoiceResource\RelationManagers;
use App\Filament\Appuser\Resources\SearchinvoiceResource\RelationManagers\InvattachRelationManager;
use App\Filament\Appuser\Resources\SearchinvoiceResource\RelationManagers\InvoicestatusRelationManager;
use App\Filament\Twelve24\Resources\SearchinvoiceResource\RelationManagers\RemarkstatusRelationManager;

class SearchinvoiceResource extends Resource
{
    protected static ?string $model = Searchinvoice::class;

    protected static ?string $navigationLabel = 'Track Invoice';
    public static ?string $label = 'Track Invoice';
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

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
                Tables\Columns\TextColumn::make('booking_invoice')
                ->label('Generated Invoice')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('manual_invoice')
                ->label('Manual Invoice')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender Name')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('sender.mobile_no')
                ->label('Sender Number')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('receiver.full_name')
                ->label('Receiver Name')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('receiver.mobile_no')
                ->label('Receiver Number')
                ->searchable(isIndividual: true, isGlobal: false),
            ])->searchOnBlur()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            InvoicestatusRelationManager::class,
            RemarkstatusRelationManager::class,
            InvattachRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSearchinvoices::route('/'),
            // 'create' => Pages\CreateSearchinvoice::route('/create'),
            'view' => Pages\Viewsearchinvoice::route('/{record}'),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Sender Details')
                    ->columns(5)
                    ->schema([
                        TextEntry::make('booking_invoice')
                            ->label('Generated Invoice'),
                            TextEntry::make('manual_invoice')
                            ->label('Manual Invoice'),
                        TextEntry::make('sender.full_name')
                            ->label('Name'),
                           
                        TextEntry::make('senderaddress.address'),
                            
                        TextEntry::make('senderaddress.provincecan.name')
                            ->label('Province'),
                        TextEntry::make('senderaddress.citycan.name')
                            ->label('City'),
                        TextEntry::make('senderaddress.postal_code')
                            ->label('Postal Code'),
                            TextEntry::make('sender.mobile_no')
                            ->label('Mobile Number'),
                        TextEntry::make('sender.email')
                            ->label('Email'),
                    ]),
                Section::make('Receiver Details')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('receiver.full_name')
                            ->label('Name'),
                         
                        TextEntry::make('receiveraddress.address')
                            ->label('Address'),
                        TextEntry::make('receiveraddress.provincephil.name')
                            ->label('Province'),
                        TextEntry::make('receiveraddress.cityphil.name')
                            ->label('City'),
                        TextEntry::make('receiveraddress.barangayphil.name')
                            ->label('Barangay'),
                            TextEntry::make('receiver.mobile_no')
                            ->label('Mobile Number'),
                    ]),
                
            ]);
    }
    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->where('is_deliver', false);
    // }
}
