<?php

namespace App\Filament\Appuser\Resources;

use App\Filament\Appuser\Resources\SenderResource\RelationManagers\CustomerhistoryRelationManager;
use Filament\Forms;
use Filament\Tables;
use App\Models\Sender;
use Filament\Forms\Get;
use Filament\Forms\Set;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\SenderResource\Pages;
use App\Filament\Appuser\Resources\SenderResource\RelationManagers;
use App\Filament\Appuser\Resources\SenderResource\RelationManagers\BookingRelationManager;
use App\Filament\Appuser\Resources\SenderResource\RelationManagers\ReceiverRelationManager;
use App\Filament\Appuser\Resources\SenderResource\RelationManagers\PackinglistRelationManager;
use App\Filament\Appuser\Resources\SenderResource\RelationManagers\SenderaddressRelationManager;
use App\Filament\Appuser\Resources\SenderResource\RelationManagers\BookingpaymentRelationManager;

class SenderResource extends Resource
{
    protected static ?string $model = Sender::class;

    protected static ?string $navigationLabel = 'Sender Information';
    public static ?string $label = 'Sender Information';
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mobile_no')
                ->live(onBlur:true)
                    ->unique(ignorable: fn($record) => $record)
                    ->mask('(999)999-9999')
                    ->stripCharacters(['(',')','-'])
                    ->required(),
                    
                Forms\Components\TextInput::make('home_no')
                    ->mask('(999)999-9999')
                    ->stripCharacters(['(',')','-']),
                Forms\Components\TextInput::make('email')
                    ->unique(ignorable: fn($record) => $record)
                    ->email()
                    ->required()
                    ->maxLength(255),
                MarkdownEditor::make('remark')
                    ->label('Note'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->paginated([10])
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->toggleable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('mobile_no')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('home_no')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remark')
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('branch.business_name')
                //     ->toggleable(isToggledHiddenByDefault: true)
                //     ->sortable(),
                Tables\Columns\TextColumn::make('user_id')->label('Created By')

                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        return $record->user->first_name . " " . $record->user->last_name;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->dateTime(),
            ])->searchOnBlur()
            ->persistSearchInSession()
        ->persistColumnSearchesInSession()
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BookingRelationManager::class,
            SenderaddressRelationManager::class,
            ReceiverRelationManager::class,
            BookingpaymentRelationManager::class,
            PackinglistRelationManager::class,
            CustomerhistoryRelationManager::class,
           
           
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSenders::route('/'),
            'create' => Pages\CreateSender::route('/create'),
            'edit' => Pages\EditSender::route('/{record}/edit'),
        ];
    }
    
}
