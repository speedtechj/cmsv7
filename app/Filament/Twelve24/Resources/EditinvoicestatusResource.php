<?php

namespace App\Filament\Twelve24\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Trackstatus;
use App\Models\Provincephil;
use App\Models\Invoicestatus;
use Filament\Resources\Resource;
use App\Models\Editinvoicestatus;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Twelve24\Resources\EditinvoicestatusResource\Pages;
use App\Filament\Twelve24\Resources\EditinvoicestatusResource\RelationManagers;

class EditinvoicestatusResource extends Resource
{
    protected static ?string $model = Editinvoicestatus::class;

    protected static ?string $navigationGroup = 'Invoice Status';
    protected static ?string $navigationLabel = 'Edit/Delete Invoice Status';
    public static ?string $label = 'Edit/Delete Invoice Status';

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
            ->filters(
                [
                    SelectFilter::make('trackstatus_id')
                        ->multiple()
                        ->label('Status')
                        ->options(Trackstatus::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                        ->searchable(),
                    // ->default(array('Select Status')),
                    SelectFilter::make('provincephil_id')
                        ->label('Province')
                        ->options(Provincephil::all()->pluck('name', 'id'))
                        ->searchable(),
                ],
                layout: FiltersLayout::AboveContent
            )->filtersFormColumns(3)

            ->actions([
                Tables\Actions\Action::make('Edit Invoice Status')
                    ->icon('heroicon-o-pencil')
                    ->label('Edit')
                    ->mountUsing(fn(Forms\ComponentContainer $form, Model $record) => $form->fill([
                        'remarks' => $record->remarks,
                        'date_update' => $record->date_update,
                        'location' => $record->location,
                        'waybill' => $record->waybill,
                    ]))
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
                    ->action(function (Model $record, array $data): void {
                       
                        $record->update([
                            'date_update' => $data['date_update'],
                            'remarks' => $data['remarks'],
                            'location' => $data['location'],
                            'waybill' => $data['waybill'],
                        ]);
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('Delete'),
            ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        BulkAction::make('delete')
                            ->color('danger')
                            ->icon('heroicon-o-trash')
                            ->action(fn(Collection $records) => $records->each->delete()),
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
