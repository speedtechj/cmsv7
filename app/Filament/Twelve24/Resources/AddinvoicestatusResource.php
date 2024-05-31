<?php

namespace App\Filament\Twelve24\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Trackstatus;
use App\Models\Provincephil;
use App\Models\Invoicestatus;
use App\Models\Addinvoicestatus;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Contracts\Pagination\CursorPaginator;
use App\Filament\Twelve24\Resources\AddinvoicestatusResource\Pages;
use App\Filament\Twelve24\Resources\AddinvoicestatusResource\RelationManagers;

class AddinvoicestatusResource extends Resource
{
    protected static ?string $model = Addinvoicestatus::class;

    protected static ?string $navigationGroup = 'Invoice Status';
    protected static ?string $navigationLabel = 'Add Invoice Status';
    public static ?string $label = 'Add Invoice Status';
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
            ->paginated([10, 25])
            ->columns([
                Tables\Columns\TextColumn::make('booking_invoice')
                ->label('Invoice')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('manual_invoice')
                ->label('Manual Invoice')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('boxtype.description')
                ->label('Box Type')
                ->sortable(),
            Tables\Columns\TextColumn::make('receiver.full_name')
                ->label('Receiver Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')
                ->label('Province'),
               
            Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
                ->label('City'),
               
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->tooltip(function (Model $record) {
                    // return whatever you need to show
                    // return $record->field1 + $record->field2
                    $status = Invoicestatus::where('booking_id', $record->id)->orderBy('id', 'desc')->get();
                    // dd($status);

                    return collect($status)->map(function ($status) {
                        return $status->trackstatus->description;
                    })->implode(',' . PHP_EOL);
                })
                ->getStateUsing(function (Model $record) {
                    // return whatever you need to show
                    // return $record->field1 + $record->field2
                    $status = Invoicestatus::where('generated_invoice', $record->booking_invoice)->orderBy('id', 'desc')->get();
                    
                    return collect($status)->map(function ($status) {
                        return $status->trackstatus->description;
                    })->implode(',' . PHP_EOL);
                })->limit(10),
            ])->searchOnBlur()
            ->filters([
                SelectFilter::make('province')
                ->label('Province')
                ->options(
                    function () {
                        // could be more discerning here, and select a distinct list of aircraft id's
                        // that actually appear in the Daily Logs, so we aren't presenting filter options
                        // which don't exist in the table, but in my case we know they are all used
                        return Provincephil::all()->pluck('name', 'id')->toArray();
                    }
                )
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        // if we have a value (the aircraft ID from our options() query), just query a nested
                        // set of whereHas() clauses to reach our target, in this case two deep
                        $query->whereHas(
                            'receiveraddress',
                            fn (Builder $query) => $query->whereHas(
                                'provincephil',
                                fn (Builder $query) => $query->where('id', '=', (int) $data['value'])
                            )
                        );
                    }
                }),
            ])
            ->actions([
                Tables\Actions\Action::make('Update')
                ->color('warning')
                ->icon('heroicon-o-clipboard-document')
                ->label('Update')
                ->action(function (Model $record, array $data): void {
                    
                    $statusupdate = InvoiceStatus::where('booking_id', $record->id)
                    ->where('trackstatus_id', $data['id'])
                    ->count();
                    $trackstatus = Trackstatus::where('id', $data['id'])->first()->description;
                    
                   
                        if( $trackstatus == 'Delivered')
                        {
                            $record->update([
                                'is_deliver' => true,
                            ]);
                        }
                        
               
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
                            'waybill' => $data['waybill'],
                            'location' => $data['location'],
                        ]);
                        Notification::make()
                            ->title('Update Status successfully')
                            ->icon('heroicon-o-document-text')
                            ->iconColor('success')
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Status Already Exist')
                            ->icon('heroicon-o-exclamation-circle')
                            ->iconColor('danger')
                            ->send();
                    }
                })
                ->form([
                    Forms\Components\Select::make('id')
                        ->label('Status')
                        ->options(Trackstatus::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                        ->required(),
                    DatePicker::make('date_updated')
                        ->label('Date Updated')
                        ->required()
                        ->native(false)
                        ->closeOnDateSelection(),
                    Forms\Components\TextInput::make('waybill'),
                    Forms\Components\TextInput::make('location'),
                    Forms\Components\MarkdownEditor::make('remarks')
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Update Status')
                ->label('Update Status')
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        $statusupdate = InvoiceStatus::where('booking_id', $record->id)
                            ->where('trackstatus_id', $data['id'])
                            ->count();
                            $trackstatus = Trackstatus::where('id', $data['id'])->first()->description;
                    
                   
                            if( $trackstatus == 'Delivered')
                            {
                                $record->update([
                                    'is_deliver' => true,
                                ]);
                            }
                            
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
                                'waybill' => $data['waybill'],
                                'location' => $data['location'],
                            ]);
                            Notification::make()
                                ->title('Update Status successfully')
                                ->icon('heroicon-o-document-text')
                                ->iconColor('success')
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Status Already Exist')
                                ->icon('heroicon-o-exclamation-circle')
                                ->iconColor('danger')
                                ->send();
                        }
                    }
                })
                ->form([
                    Forms\Components\Select::make('id')
                        ->label('Status')
                        ->options(Trackstatus::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                        ->required(),
                    DatePicker::make('date_updated')
                        ->label('Date Updated')
                        ->native(false)
                        ->closeOnDateSelection()
                        ->required(),
                    Forms\Components\TextInput::make('waybill'),
                    Forms\Components\TextInput::make('location'),
                    Forms\Components\MarkdownEditor::make('remarks')
                ])
                ]),
            ]);
    }
    

#
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAddinvoicestatuses::route('/'),
            'create' => Pages\CreateAddinvoicestatus::route('/create'),
            // 'edit' => Pages\EditAddinvoicestatus::route('/{record}/edit'),
        ];
    }
}
