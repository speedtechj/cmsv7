<?php

namespace App\Filament\Allport\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Shippingbooking;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Allport\Resources\ShippingbookingResource\Pages;
use App\Filament\Allport\Resources\ShippingbookingResource\RelationManagers;

class ShippingbookingResource extends Resource
{
    protected static ?string $model = Shippingbooking::class;
    protected static ?string $navigationLabel = 'Shipping Monitoring';
    public static ?string $label = 'Shipping Monitoring';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

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
        ->query(Shippingbooking::query()->where('branch_id', Auth::user()->branch_id))
            ->columns([
                Tables\Columns\TextColumn::make( 'booking_no' )
                ->label( 'Booking Number' )
                ->searchable(),
                Tables\Columns\TextColumn::make( 'shippingcontainer.container_no' )
                ->label( 'Container Number' )
                ->listWithLineBreaks()
                ->searchable(),
                Tables\Columns\TextColumn::make( 'shippingcontainer.seal_no' )
                ->label( 'Seal Number' )
                ->listWithLineBreaks()
                ->searchable(),
                Tables\Columns\TextColumn::make( 'carrier.name' )
                ->label( 'Carrier' )
                ->numeric(),
                Tables\Columns\TextColumn::make('bill_of_lading')
                ->label('Bill of Lading')
                ->numeric(),
                Tables\Columns\TextColumn::make('Bill_status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Request for Bill of Lading' => 'danger',
                    'Waiting for Bill of Lading' => 'warning',
                    'Completed' => 'success',
                })
                ->label('Bill of Lading Status')
                ->getStateUsing(function ($record) {
                    if($record->bill_of_lading != null){
                        return 'Completed';

                    }else {
                        $etd_date = Carbon::parse($record->etd);
                    $now = Carbon::now();
                    $diff = $etd_date->diffInDays($now, false);
                    if($diff > 14){
                        return'Request for Bill of Lading';
                    }else{
                        return 'Waiting for Bill of Lading';
                    }
                    };
                    
                }),
               
                Tables\Columns\TextColumn::make('eta')
                ->label('ETA'),
                Tables\Columns\TextColumn::make('etd')
                ->label('ETD'),
                // Tables\Columns\TextColumn::make('branch.business_name')
                // ->label('Broker')
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('bldownload')
                ->disabled(function ($record){
                    return $record->bl_attachments ? false : true;
                    })
                ->label('BL')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->modalSubmitAction(false)            //Remove Submit Button
                        ->modalCancelAction(false)    
                ->mountUsing(fn (Forms\ComponentContainer $form, Model $record) => $form->fill([
                  
                    'blattachments' => $record->bl_attachments,
                    
                ]))
                ->form([
                    Section::make('Bill of Lading Attachments')
                       ->schema([
                        Forms\Components\FileUpload::make('blattachments')
                            ->label('Bill of Lading Attachments')
                            ->openable()
                            ->deletable(false)
                            ->disk('public')
                            // ->directory('skidgallery')
                            ->visibility('private')
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                       ])
                    
                ]),
                Tables\Actions\Action::make('telexdownload')
                ->label('Telex')
                ->disabled(function ($record){
                return $record->telex_attachments ? false : true;
                })
                ->icon('heroicon-o-arrow-down-tray')
                ->modalSubmitAction(false)            //Remove Submit Button
                        ->modalCancelAction(false)    
                ->mountUsing(fn (Forms\ComponentContainer $form, Model $record) => $form->fill([
                  
                    'tlattachments' => $record->telex_attachments,
                    
                ]))
                ->form([
                    Section::make('Bill of Lading Attachments')
                       ->schema([
                        Forms\Components\FileUpload::make('tlattachments')
                            ->label('Telex Attachments')
                            ->openable()
                            ->deletable(false)
                            ->disk('public')
                            // ->directory('skidgallery')
                            ->visibility('private')
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                       ])
                    
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListShippingbookings::route('/'),
            // 'create' => Pages\CreateShippingbooking::route('/create'),
            // 'edit' => Pages\EditShippingbooking::route('/{record}/edit'),
        ];
    }
}
