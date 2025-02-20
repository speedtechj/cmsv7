<?php

namespace App\Filament\Appuser\Resources;

use data;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Cityphil;
use Filament\Forms\Form;
use App\Mail\ShipmentMail;
use Filament\Tables\Table;
use App\Models\EmailStatus;
use App\Models\Trackstatus;
use App\Models\EmailSetting;
use App\Models\Provincephil;
use App\Models\Invoicestatus;
use App\Models\Shipmentstatus;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use function PHPUnit\Framework\returnSelf;
use Filament\Actions\Exports\Models\Export;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Exports\ShipmentstatusExporter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\ShipmentstatusResource\Pages;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use App\Filament\Appuser\Resources\ShipmentstatusResource\RelationManagers;

use App\Filament\Appuser\Resources\ShipmentstatusResource\RelationManagers\EmailstatusRelationManager;
use App\Filament\Appuser\Resources\ShipmentstatusResource\RelationManagers\InvoicestatusesRelationManager;


class ShipmentstatusResource extends Resource
{
    protected static ?string $model = Shipmentstatus::class;
    protected static ?string $navigationLabel = 'Shipment Status';
    public static ?string $label = 'Shipment Status';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationBadge(): ?string
{
    return "New";
}
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
        ->headerActions([
                    ExportAction::make()
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('is_deliver', false))
                    ->label('Export Shipmentstatus')
                    ->icon('heroicon-o-folder-arrow-down')
                    ->color('primary')
                    ->exporter(ShipmentstatusExporter::class)
                    ->fileName(fn (Export $export): string => "Shipmentstatus")
        ])
            ->columns([
                Tables\Columns\TextColumn::make('invoice')
                    ->label('Invoice')
                    ->searchable(['manual_invoice', 'booking_invoice'])
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        if ($record->manual_invoice != null) {
                            return $record->manual_invoice;
                        } else {
                            return $record->booking_invoice;
                        }
                    }),

                // Tables\Columns\TextColumn::make('boxtype.description')
                //     ->label('Box Type')
                //     ->searchable()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('batch.id')
                    ->label('Batch No')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return $record->batch->batchno . "-" . $record->batch->batch_year;
                    }),
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->label('Sender Name')
                    ->searchable()
                    ->sortable(),
                // ->url(fn (Model $record) => SenderResource::getUrl('edit', ['record' => $record->sender])),
                Tables\Columns\TextColumn::make('receiver.full_name')
                    ->label('Receiver Name')
                    ->searchable()
                    ->sortable(),
                // ->url(fn (Model $record) => ReceiverResource::getUrl('edit', ['record' => $record->receiver])),
                Tables\Columns\TextColumn::make('receiveraddress.address')
                    ->label('Address')
                    ->wrap()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiveraddress.barangayphil.name')
                    ->label('Barangay')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')
                    ->label('Province'),
                Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
                    ->label('City')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Unloaded')
                ->label('Unloaded')
                ->getStateUsing(function (Model $record){
                    $unloadedid = Trackstatus::where('code', 'op')->first()->id;
                    $unloaded = Invoicestatus::where('generated_invoice',$record->booking_invoice)
                    ->where('trackstatus_id', $unloadedid)->first();
                    if ($unloaded != null) {
                        return $unloaded->date_update;
                    }else{
                        return 0;
                    }
                    // return $unloaded->date_update ?? 0; 
                }),
                Tables\Columns\TextColumn::make('Day')
                ->label('Day(s) Due')
                ->getStateUsing(function (Model $record) {
                    $endpoint = Trackstatus::where('code', 'ed')->first()->id;
                        $end_point_stat = $record->invoicestatuses->where('trackstatus_id', $endpoint)->first();
                        if ($end_point_stat == null) {
                            $origin = Trackstatus::where('code', 'op')->first()->id;
                            $origin_date = Carbon::parse($record->invoicestatuses->where('trackstatus_id', $origin)->first()->date_update ?? now());
                            $now = Carbon::now()->addDays(1);
                            $diff_date = $origin_date->diffInDays($now, false);
                            $agingdays = $record->receiveraddress->cityphil->no_days;
                        //    dd($agingdays, $diff_date);
                            if ($diff_date > $agingdays) {
                                $days_due = $diff_date - $agingdays;
                           return $days_due;
                            } else {
                                return 0;
                            }
                        }else {
                            return 0;
                        }
                    
                }),
                Tables\Columns\TextColumn::make('invoice')
                    ->label('Invoice')
                    ->tooltip('Combined invoice number from manual or booking invoice')
                    ->searchable(['manual_invoice', 'booking_invoice'])
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        if ($record->manual_invoice != null) {
                            return $record->manual_invoice;
                        } else {
                            return $record->booking_invoice;
                        }
                    }),
                
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->label('Sender Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Status')
                    ->badge()
                    ->label('Status')
                    ->color(fn(string $state): string => match ($state) {
                        'Follow Up' => 'danger',
                        'Waiting for Delivery' => 'warning',
                        'Delivered' => 'success',
                    })
                    ->getStateUsing(function (Model $record) {
                        $endpoint = Trackstatus::where('code', 'ed')->first()->id;
                        $end_point_stat = $record->invoicestatuses->where('trackstatus_id', $endpoint)->first();
                        if ($end_point_stat == null) {
                            $origin = Trackstatus::where('code', 'op')->first()->id;
                            $origin_date = Carbon::parse($record->invoicestatuses->where('trackstatus_id', $origin)->first()->date_update ?? now());
                            $now = Carbon::now()->addDays(1);
                            $diff_date = $origin_date->diffInDays($now, false);
                            $agingdays = $record->receiveraddress->cityphil->no_days;
                            if ($diff_date > $agingdays) {
                                return 'Follow Up';
                            } else {
                                return 'Waiting for Delivery';
                            }
                        } else {
                            return 'Delivered';
                        }
                    })
            ])
            ->filters([
                SelectFilter::make('batch_id')
                    // ->multiple()
                    ->label('Batch Number')
                    ->options(Batch::Batchmanifest())
                    // ->relationship('batch', 'batchno', fn (Builder $query) => $query->where('is_active', '1'))
                    // ->default(array('Select Batch Number')),
                    ->default(),
                SelectFilter::make('provincephil_id')
                    ->searchable()
                    ->label('Province')
                    ->options(function () {
                        // could be more discerning here, and select a distinct list of aircraft id's
                        // that actually appear in the Daily Logs, so we aren't presenting filter options
                        // which don't exist in the table, but in my case we know they are all used
                        return Provincephil::all()->pluck('name', 'id')->toArray();
                    })
                    ->query(function (Builder $query, array $data) {
                        
                        if (!empty($data['value']))
                        {
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
                    })

            ])->persistFiltersInSession()
            ->actions([
                Tables\Actions\ViewAction::make()
                // ->disabled(function (Model $record){
                //     return EmailStatus::where('booking_id', $record->id)->count() == 0 ? true : false;
                // })
                ->color('primary')
                ->label('Email History'),
                Tables\Actions\Action::make('sendmail')
                        ->disabled(function (Model $record){
                            return $record->is_deliver == true ? true : false;
                        })
                        ->label('Send Mail')
                        ->color('info')
                        ->icon('heroicon-o-envelope')
                        ->form([
                            Select::make('recipient')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->label('Email')
                                ->options(EmailSetting::all()->pluck('email', 'email'))
                                ->required(),
                            TextInput::make('subject')
                                ->label('Subject')
                                ->required()
                                ->maxLength(255),
                            MarkdownEditor::make('message')
                                ->label('Message')
                                ->required()
                        ])
                        ->action(function (Model $record, array $data): void {
                            
                            EmailStatus::create([
                                'subject' => $data['subject'],
                                'message' => $data['message'],
                                'booking_id' => $record->id,
                                'user_id' => auth()->user()->id,
                            ]);

                            $recipients = $data['recipient'];
                            foreach ($recipients as $recipient) {
                                Mail::to($recipient)->send(new ShipmentMail($data,$record));
                            }
                            Notification::make()
                             ->title('Email Successfully Send')
                             ->success()
                             ->send();

                            
                        })
                        // ->url(fn (Model $record) => route('shipmentmail', $record))
                        // ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                    
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('booking_invoice'),
                TextEntry::make('sender.full_name'),
                TextEntry::make('receiver.full_name'),
            ])->columns(3);
    }
    public static function getRelations(): array
    {
        return [
            InvoicestatusesRelationManager::class,
            EmailstatusRelationManager::class,
            
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShipmentstatuses::route('/'),
            'view' => Pages\ViewShipmentstatus::route('/{record}'),
            // 'create' => Pages\CreateShipmentstatus::route('/create'),
            // 'edit' => Pages\EditShipmentstatus::route('/{record}/edit'),
        ];
    }
}
