<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Trackstatus;
use App\Models\Provincephil;
use App\Models\Invoicestatus;
use Filament\Resources\Resource;
use App\Models\Updatebatchstatus;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UpdatebatchstatusResource\Pages;
use App\Filament\Resources\UpdatebatchstatusResource\RelationManagers;

class UpdatebatchstatusResource extends Resource
{
    protected static ?string $model = Updatebatchstatus::class;

    protected static ?string $navigationGroup = 'Batch Status';
    protected static ?string $navigationLabel = 'Update/Delete Batch Status';
    public static ?string $label = 'Update/Delete Batch Status';

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
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('waybill')
                ->label('Waybill')
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                SelectFilter::make('batch_id')
                ->multiple()
                ->options(Batch::Currentyear())
                ->placeholder('Select Batch Number')
                ->label('Batch Number')
                ->default(array('Select Batch Number')),
            SelectFilter::make('trackstatus_id')
                ->multiple()
                ->label('Status')
                ->options(Trackstatus::all()->pluck('description', 'id'))
                ->searchable(),
                // ->default(array('Select Status')),
            SelectFilter::make('provincephil_id')
                ->label('Province')
                ->options(Provincephil::all()->pluck('name', 'id'))
                ->searchable(),
            ],layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\DeleteAction::make()
                ->label('Delete'),
            Tables\Actions\Action::make('Edit Invoice Status')
                ->label('Edit')
                ->mountUsing(fn (Forms\ComponentContainer $form, Model $record) => $form->fill([
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\BulkAction::make('Assign Batch')
                //     ->label('Assign Batch')
                //     ->icon('heroicon-o-clipboard-document-list')
                //     ->color('primary')
                //     ->form([
                //         Select::make('batch_id')
                //             ->label('Batch Number')
                //             ->options(Batch::all()->pluck('batchno', 'id'))
                //             ->required(),
                //     ])
                // ->action(function (Collection $records, array $data): void {
                  
                //     foreach ($records as $record) {
                       
                //         $record->booking->update([

                //             'batch_id' => $data['batch_id'],
                       
                //         ]);
                //     }
                // }),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Update Status')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('primary')
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        $record->update([

                            'date_update' => $data['date_updated'],
                            'remarks' => $data['remarks'],
                        ]);
                    }
                })
                ->form([

                    DatePicker::make('date_updated')
                        ->label('Date Stats Updated')
                        ->default(now())
                        ->closeOnDateSelection()
                        ->required(),
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
            'index' => Pages\ListUpdatebatchstatuses::route('/'),
            'create' => Pages\CreateUpdatebatchstatus::route('/create'),
            'edit' => Pages\EditUpdatebatchstatus::route('/{record}/edit'),
        ];
    }
}
