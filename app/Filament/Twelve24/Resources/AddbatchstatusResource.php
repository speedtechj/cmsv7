<?php

namespace App\Filament\Twelve24\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Trackstatus;
use App\Models\Invoicestatus;
use App\Models\Addbatchstatus;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TagsColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Twelve24\Resources\AddbatchstatusResource\Pages;
use App\Filament\Twelve24\Resources\AddbatchstatusResource\RelationManagers;

class AddbatchstatusResource extends Resource
{
    protected static ?string $model = Addbatchstatus::class;

    protected static ?string $navigationGroup = 'Invoice Status';
    protected static ?string $navigationLabel = 'Add Batch Status';
    public static ?string $label = 'Add Batch Status';

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
                Tables\Columns\TextColumn::make('batch.batchno')
                    ->label('Batch Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_invoice')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('manual_invoice')
                    ->label('Manual Invoice')
                    ->searchable()
                    ->sortable(),
                TagsColumn::make('invoicestatus.trackstatus.description'),
                Tables\Columns\TextColumn::make('boxtype.description')
                    ->label('Box Type')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sender.full_name')
                    ->label('Sender Name')
                    ->searchable()
                    ->sortable(),
                // ->url(fn (Booking $record) => route('filament.resources.senders.edit', $record->sender)),
                Tables\Columns\TextColumn::make('receiver.full_name')
                    ->label('Receiver')
                    ->searchable()
                    ->sortable(),
                // ->url(fn (Booking $record) => route('filament.resources.receivers.edit', $record->receiver)),
                Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')
                    ->label('Province')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
                    ->label('City')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('batch_id')
                    ->multiple()
                    ->options(Batch::Currentyear())
                    ->label('Batch Number')
                    ->default(array('Select Batch Number')),
            ],layout: FiltersLayout::AboveContent)
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Add Invoice Status')
                        ->icon('heroicon-o-arrow-path')
                        ->color('primary')
                        ->form([
                            Section::make()
                                ->schema([
                                    Forms\Components\Select::make('id')
                                        ->label('Status')
                                        ->options(Trackstatus::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                                        ->required(),
                                DatePicker::make('date_updated')
                                        ->label('Update Date')
                                        ->native(false)
                                        ->default(now())
                                        ->closeOnDateSelection()
                                        ->required(),
                                    Forms\Components\TextInput::make('location'),
                                    Forms\Components\TextInput::make('waybill'),
                                    Forms\Components\Textarea::make('remarks')
                                ])

                        ])->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $statusupdate = InvoiceStatus::where('booking_id', $record->id)
                                    ->where('trackstatus_id', $data['id'])
                                    ->count();
                                  $flagcount = 0;  
                                if ($statusupdate == 0) {
                                    Invoicestatus::create([
                                        'generated_invoice' => $record->booking_invoice,
                                        'manual_invoice' => $record->manual_invoice,
                                        'provincephil_id' => $record->receiveraddress->provincephil_id,
                                        'cityphil_id' => $record->receiveraddress->cityphil_id,
                                        'booking_id' => $record->id,
                                        'trackstatus_id' => $data['id'],
                                        'date_update' => $data['date_updated'],
                                        'remarks' => $data['remarks'],
                                        'user_id' => auth()->user()->id,
                                        'batch_id' => $record->batch_id,
                                        'receiver_id' => $record->receiver_id,
                                        'sender_id' => $record->sender_id,
                                        'boxtype_id' => $record->boxtype_id,
                                        'location' => $data['location'],
                                        'waybill' => $data['waybill'],
                                    ]);
                                    $flagcount = 1;
                                }
                            }
                            if($flagcount == 1){
                                Notification::make()
                                ->title('Batch Status Successfully Created')
                                ->success()
                                ->send();
                            } else {
                                Notification::make()
                                        ->title('Batch Status Already Created')
                                        ->success()
                                        ->send();
                            }
                        })
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
            'index' => Pages\ListAddbatchstatuses::route('/'),
            'create' => Pages\CreateAddbatchstatus::route('/create'),
            // 'edit' => Pages\EditAddbatchstatus::route('/{record}/edit'),
        ];
    }
}
