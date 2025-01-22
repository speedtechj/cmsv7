<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Receiver;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use App\Filament\Appuser\Resources\ReceiverResource\Pages;
use App\Filament\Appuser\Resources\ReceiverResource\RelationManagers;
use App\Filament\Appuser\Resources\ReceiverResource\RelationManagers\ReceiveraddressRelationManager;

class ReceiverResource extends Resource
{
    protected static ?string $model = Receiver::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'Receiver Information';
    public static ?string $label = 'Receiver Information';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Reciver Information')->schema([
                    Forms\Components\Select::make('sender_id')
                        ->required()
                        ->relationship('sender', 'full_name')
                        ->label('Sender Name')
                        ->searchable(),
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->maxLength(255),
                        PhoneInput::make('mobile_no')
                        ->autoPlaceholder('aggressive')
                        ->required()
                        ->initialCountry('ph')
                        ->defaultCountry('PH'),
                    PhoneInput::make('home_no')
                    ->autoPlaceholder('aggressive')
                        ->initialCountry('ph')
                    ->defaultCountry('PH'),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->maxLength(255),
                        MarkdownEditor::make('remark')
                        ->label('Note')
                        ->columnSpan('full')
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                ->searchable()
                ->toggleable()
                ->sortable()
                ->weight('bold'),
                Tables\Columns\TextColumn::make('last_name')
                ->searchable()
                ->toggleable()
                ->sortable()
                ->weight('bold'),
                Tables\Columns\TextColumn::make('mobile_no')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('home_no')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('remark')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ReceiveraddressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReceivers::route('/'),
            'create' => Pages\CreateReceiver::route('/create'),
            'edit' => Pages\EditReceiver::route('/{record}/edit'),
        ];
    }
}
