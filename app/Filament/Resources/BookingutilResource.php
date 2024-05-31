<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Bookingutil;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingutilResource\Pages;
use App\Filament\Resources\BookingutilResource\RelationManagers;

class BookingutilResource extends Resource
{
    protected static ?string $model = Bookingutil::class;

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
                Tables\Columns\TextColumn::make('booking_invoice')
                    ->label('Generated Invoice'),
                Tables\Columns\TextColumn::make('manual_invoice')
                    ->label('Manual Invoice'),
                ToggleColumn::make('is_deliver')
                    ->label('Deliver'),
                ToggleColumn::make('is_active')
                    ->label('Active')
            ])
            ->filters([
                SelectFilter::make('batch_id')
                    ->multiple()
                    ->options(Batch::all()->pluck('batchno', 'id'))
                    ->label('Batch Number')
                    ->default(array('Select Batch Number')),

            ], layout: FiltersLayout::AboveContent)
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Deliver')
                        ->color('primary')
                        ->icon('heroicon-o-chevron-double-right')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_deliver' => true,
                                ]);
                            }
                        }),
                    Tables\Actions\BulkAction::make('Deactive')
                        ->color('success')
                        ->label('Deactivate')
                        ->icon('heroicon-o-chevron-right')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_active' => false,
                                ]);
                            }
                        }),
                        Tables\Actions\BulkAction::make('Undeliver')
                        ->color('primary')
                        ->label('Undeliver')
                        ->icon('heroicon-o-chevron-right')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_deliver' => false,
                                ]);
                            }
                        }),
                        Tables\Actions\BulkAction::make('Active')
                        ->color('success')
                        ->label('Activate')
                        ->icon('heroicon-o-chevron-right')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_active' => true,
                                ]);
                            }
                        }),
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
            'index' => Pages\ListBookingutils::route('/'),
            'create' => Pages\CreateBookingutil::route('/create'),
            // 'edit' => Pages\EditBookingutil::route('/{record}/edit'),
        ];
    }
}
