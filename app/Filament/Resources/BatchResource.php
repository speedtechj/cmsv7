<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\BatchResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BatchResource\RelationManagers;

class BatchResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Batch Informaton')->schema([
                    Forms\Components\TextInput::make('batchno')
                    ->numeric()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('batch_year')
                        ->required()
                        ->default(now()->year),
                    Toggle::make('is_active'),
                    Forms\Components\MarkdownEditor::make('note')
                        ->maxLength(65535)->columnSpan('full'),
                    Toggle::make('is_lock'),
                ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistSortInSession()
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('batchno')
                ->label('Batch No')
                ->searchable(),
                Tables\Columns\TextColumn::make('batch_year')
                ->label('Year')
                ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_lock')
                    ->boolean()
                    ->label('Lock')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('note'),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Encoder')
                    ->getStateUsing(function (Model $record) {
                        return $record->user->first_name . " " . $record->user->last_name;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Filter::make('is_active')->label('Is Active')->query(fn (Builder $query): Builder => $query->where('is_active', true))->default(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('Lock')
                        ->color('primary')
                        ->icon('heroicon-o-lock-closed')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_lock' => true,
                                ]);
                            }
                        }),
                    BulkAction::make('Unlock')
                        ->color('primary')
                        ->icon('heroicon-o-lock-open')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_lock' => false,
                                ]);
                            }
                        }),
                    BulkAction::make('Activate')
                        ->color('success')
                        ->icon('heroicon-o-eye')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_active' => true,
                                ]);
                            }
                        }),
                    BulkAction::make('Deactivate')
                        ->color('success')
                        ->icon('heroicon-o-eye-slash')
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'is_active' => false,
                                ]);
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
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
        ];
    }
}
