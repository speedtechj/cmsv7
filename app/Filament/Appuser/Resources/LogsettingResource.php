<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Logsetting;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\LogsettingResource\Pages;
use App\Filament\Appuser\Resources\LogsettingResource\RelationManagers;

class LogsettingResource extends Resource
{
    protected static ?string $model = Logsetting::class;

    protected static ?string $navigationLabel = 'Log Setting';
    public static ?string $label = 'Log Setting';
    protected static ?string $navigationGroup = 'Call Log';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Log Setting')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->native(false)
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('total_call')
                            ->numeric()
                            ->label('Number of Call')
                            ->default(5)
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Is Active')
                            ->default(true),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date'),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date'),
                    Tables\Columns\TextColumn::make('total_call')
                    ->label('Total Number of Call'),
                    Tables\Columns\ToggleColumn::make('is_active'),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Created By')

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListLogsettings::route('/'),
            'create' => Pages\CreateLogsetting::route('/create'),
            'edit' => Pages\EditLogsetting::route('/{record}/edit'),
        ];
    }
}
