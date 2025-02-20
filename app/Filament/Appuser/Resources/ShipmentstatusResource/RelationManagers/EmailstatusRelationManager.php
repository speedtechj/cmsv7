<?php

namespace App\Filament\Appuser\Resources\ShipmentstatusResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Appuser\Resources\ShipmentstatusResource;

class EmailstatusRelationManager extends RelationManager
{
    protected static string $relationship = 'emailstatus';
    public static ?string $title = 'Email History';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
               
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('subject'),
                Tables\Columns\TextColumn::make('message')
                ->wrap(),
                Tables\Columns\TextColumn::make('user.full_name')
                ->label('Created By'),
                Tables\Columns\TextColumn::make('created_at')
                ->since()
                ->dateTimeTooltip(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('Back')
                ->url(fn($livewire) => ShipmentstatusResource::getUrl('index'))
                ->icon('heroicon-o-arrow-left')
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
