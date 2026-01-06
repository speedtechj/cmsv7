<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Booking;
use Filament\Forms\Get;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Provincebox;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\ProvinceboxExporter;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProvinceboxResource\Pages;
use App\Filament\Resources\ProvinceboxResource\RelationManagers;

class ProvinceboxResource extends Resource
{
    protected static ?string $model = Provincebox::class;
    protected static ?string $navigationLabel = 'Province Box';
    public static ?string $label = 'Total Box Per Province';
    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
      ->modifyQueryUsing(function (Builder $query, $livewire) {
            // Get the selected batch ID from the filter state
            $batchId = $livewire->getTableFilterState('batch');
        
            // 1. If no batch is selected, show zero records
        //    if (blank($batchId)) {
        //        return $query->whereRaw('1 = 0');
        //    }

            // 2. Count ONLY the bookings for the selected batch
            // This creates the 'bookings_count' attribute dynamically
            return $query->withCount(['bookings' => function (Builder $q) use ($batchId) {
                $q->where('batch_id', $batchId);
            }]);
        })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Province')
                    ->weight('FontWeight::Bold')
                     ->size('TextColumnSize::Large')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('bookings_count')
                ->label('Total Boxes')
                ->badge()
                 ->weight('FontWeight::Bold')
                 ->size('TextColumnSize::Large')
                ->color('danger')
                ->sortable(),
        //         ->getStateUsing(function (Model $record, $livewire){
        //             $batchId = $livewire->getTableFilterState('batch');
                
        // return $record->bookings;
                    

        //         }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
               Tables\Filters\SelectFilter::make('batch')
                ->label('Filter by Batch')
                ->searchable()
                ->getOptionLabelFromRecordUsing(function (Model $record) {
                    return "{$record->batchno} {$record->batch_year}";
                })
               // ->preload()
                // 1. THIS IS THE FIX: stop Filament from adding "and batch = X" to the SQL
                ->attribute(null) 
                ->relationship('bookings.batch', 'batchno', fn (Builder $query) => $query->where('is_active', '1'))
                ->query(function (Builder $query, array $data) {
                    $batchId = $data['value'];

                    if (blank($batchId)) {
                        return $query;
                    }

                    // 2. Apply the relationship filter manually
                    return $query->whereHas('bookings', function (Builder $q) use ($batchId) {
                        $q->where('batch_id', $batchId);
                    });
                })
            ])
            ->actions([
             
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                ->label('Export')
                ->exporter(ProvinceboxExporter::class)
                ->modifyQueryUsing(function (Builder $query, $livewire) {
            // Get the selected batch ID from the filter state
            $batchId = $livewire->getTableFilterState('batch');
        
            // 1. If no batch is selected, show zero records
        //    if (blank($batchId)) {
        //        return $query->whereRaw('1 = 0');
        //    }

            // 2. Count ONLY the bookings for the selected batch
            // This creates the 'bookings_count' attribute dynamically
            return $query->withCount(['bookings' => function (Builder $q) use ($batchId) {
                $q->where('batch_id', $batchId);
            }]);
        }),
            //        Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProvinceboxes::route('/'),
            'create' => Pages\CreateProvincebox::route('/create'),
        //    'edit' => Pages\EditProvincebox::route('/{record}/edit'),
        ];
    }
}
