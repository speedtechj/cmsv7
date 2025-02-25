<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ZoneSummary;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ZoneSummaryResource\Pages;
use App\Filament\Resources\ZoneSummaryResource\RelationManagers;

class ZoneSummaryResource extends Resource
{
    protected static ?string $model = ZoneSummary::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Zone Box Summary';
    public static ?string $label = 'Zone Box Summary';
   
    public static function getNavigationBadge(): ?string
{
    return "New";
}
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
                Tables\Columns\TextColumn::make('batch.batchno')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manual_invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver.full_name')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('boxtype.description')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('zone.description')
                    ->label('Zone')
                    ->sortable(),
               
            ])
            ->filters([
                SelectFilter::make('batch_id')
                    ->searchable()
                    ->preload()
                    ->relationship('batch', 'batchno', fn (Builder $query) => $query->where('is_active', '1'))
                    ->label('Batch Number')
                    ->getOptionLabelFromRecordUsing(function (Model $record) {
                        return "{$record->batchno} {$record->batch_year}";
                    })
                    ->default(Batch::where('is_current',1)->first()->id),
                    // SelectFilter::make('boxtype_id')
                    // ->searchable()
                    // ->preload()
                    // ->relationship('boxtype', 'description')
                    // ->label('Box Type'),
                    
            ])
            ->actions([
               
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                   
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
            'index' => Pages\ListZoneSummaries::route('/'),
            // 'create' => Pages\CreateZoneSummary::route('/create'),
            // 'edit' => Pages\EditZoneSummary::route('/{record}/edit'),
        ];
    }
}
