<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Calltype;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\CalltypeResource\Pages;
use App\Filament\Appuser\Resources\CalltypeResource\RelationManagers;

class CalltypeResource extends Resource
{
    protected static ?string $model = Calltype::class;

    protected static ?string $navigationLabel = 'Call Type';
    public static ?string $label = 'Call Type';
    protected static ?string $navigationGroup = 'Call Log';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Call Type Information')
                ->schema([
                    Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\MarkdownEditor::make('notes')
                ->label('Notes/Script'),
                ])
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Encoder')
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                //]),
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
            'index' => Pages\ListCalltypes::route('/'),
            'create' => Pages\CreateCalltype::route('/create'),
            'edit' => Pages\EditCalltype::route('/{record}/edit'),
        ];
    }
}
