<?php

namespace App\Filament\Allport\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Trackstatus;
use App\Models\Provincephil;
use Filament\Resources\Resource;
use App\Models\Editinvoicestatus;
use App\Models\Shippingcontainer;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Allport\Resources\EditinvoicestatusResource\Pages;
use App\Filament\Allport\Resources\EditinvoicestatusResource\RelationManagers;

class EditinvoicestatusResource extends Resource
{
    protected static ?string $model = Editinvoicestatus::class;
    static ?string $navigationLabel = 'Update Container Status';
    public static ?string $label = 'Update Container Status';


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                
              
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('generated_invoice')
                ->label('Invoice')
                ->searchable(isIndividual: true, isGlobal: false)
                ->label('Generated Invoice')
                ->sortable(),
            Tables\Columns\TextColumn::make('manual_invoice')
                ->label('Manual Invoice')
                ->searchable(isIndividual: true, isGlobal: false)
                ->sortable(),
            Tables\Columns\TextColumn::make('trackstatus.description')
                ->label('Status')
                ->sortable(),
            Tables\Columns\TextColumn::make('batch.batchno')
                ->label('Batch Number')
                ->sortable(),
            Tables\Columns\TextColumn::make('date_update')
                ->label('Status Date')

                ->sortable()
                ->date('Y-m-d'),
            Tables\Columns\TextColumn::make('remarks'),
            Tables\Columns\TextColumn::make('boxtype.description')
                ->label('Box Type')

                ->sortable(),

            Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender Name')

                ->sortable(),
            // ->url(fn (Booking $record) => route('filament.resources.senders.edit', $record->sender)),
            Tables\Columns\TextColumn::make('receiver.full_name')
                ->label('Receiver')

                ->sortable(),
            // ->url(fn (Booking $record) => route('filament.resources.receivers.edit', $record->receiver)),
            Tables\Columns\TextColumn::make('provincephil.name')
                ->label('Province')

                ->sortable(),
            Tables\Columns\TextColumn::make('cityphil.name')
                ->label('City')

                ->sortable(),
            Tables\Columns\TextColumn::make('location')
                ->label('Location')

                ->sortable(),
            Tables\Columns\TextColumn::make('waybill')
                ->label('Waybill')

                ->sortable(),
            ])->searchOnBlur()
            ->filters([
                
                SelectFilter::make('batch_id')
                ->label('Container Number')
                ->options(function (Shippingcontainer $record) {
                    return $record->all()->where('is_active',1)->where('branch_id', Auth::user()->branch_id)->pluck('container_no', 'batch_id');
                })->default(),
                SelectFilter::make('trackstatus_id')
                        ->multiple()
                        ->label('Status')
                        ->options(Trackstatus::all()->where('is_broker',1)->pluck('description', 'id'))
                        ->searchable(),
                    SelectFilter::make('provincephil_id')
                        ->label('Province')
                        ->options(Provincephil::all()->pluck('name', 'id'))
                        ->searchable(),
            ],
            layout: FiltersLayout::AboveContent)
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Update Status')
                    ->color('primary')
                    ->icon('heroicon-o-pencil')
                    ->action(function (Collection $records, array $data): void {
                        foreach ($records as $record) {
                            
                            $record->update([
                                'date_update' => $data['date_update'],
                                'remarks' => $data['remarks'],
                                'location' => $data['location'],
                                'waybill' => $data['waybill'],
                            ]);
                        }
                        Notification::make()
                        ->title('Upate successfully')
                        ->success()
                        ->send();
                    })
                    ->form([
                        DatePicker::make('date_update')
                            ->label('Date Updated')
                            ->native(false)
                            ->default(now())
                            ->closeOnDateSelection()
                            ->required(),
                        Forms\Components\TextInput::make('location'),
                        Forms\Components\TextInput::make('waybill'),
                        Forms\Components\Textarea::make('remarks')
                    ])
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
            'index' => Pages\ListEditinvoicestatuses::route('/'),
            'create' => Pages\CreateEditinvoicestatus::route('/create'),
            // 'edit' => Pages\EditEditinvoicestatus::route('/{record}/edit'),
        ];
    }
}
