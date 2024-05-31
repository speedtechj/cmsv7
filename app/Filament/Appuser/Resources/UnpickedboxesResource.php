<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Agent;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Unpickedboxes;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\UnpickedboxesResource\Pages;
use App\Filament\Appuser\Resources\UnpickedboxesResource\RelationManagers;

class UnpickedboxesResource extends Resource
{
    protected static ?string $model = Unpickedboxes::class;
    protected static ?string $navigationLabel = 'Unpicked Boxes';
    public static ?string $label = 'List Unpicked Boxes';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}
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
                Tables\Columns\TextColumn::make('booking_invoice')
                    ->label('Invoice')
                    ->sortable()
                    ->searchable()
                    ->color('primary')
                    ->url(fn (Model $record) => SearchinvoiceResource::getUrl('view', ['record' => $record->id])),
                Tables\Columns\TextColumn::make('manual_invoice')
                    ->label('Manual Invoice')
                    ->sortable()
                    ->searchable(),
                    Tables\Columns\TextColumn::make('sender.full_name')->label('Sender')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('receiver.full_name')->label('Receiver')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('servicetype.description')->label('Type of Service')
                    ->color(static function ($state): string {
                        if ($state === 'Pickup') {
                            return 'success';
                        }

                        return 'info';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('boxtype.description'),
                
                Tables\Columns\IconColumn::make('is_pickup')
                    ->label('Is Pickup / Drop off')
                    ->boolean(),
                    Tables\Columns\IconColumn::make('is_paid')
                    ->label('Is Paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('zone.description')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('booking_date')->label('Pickup/Dropoff Date')->sortable(),
                Tables\Columns\TextColumn::make('start_time')->label('Pickup Time')
                    ->getStateUsing(function (Model $record) {
                        return $record->start_time . " - " . $record->end_time;
                    })->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_balance')->label('Balance')->money('USD') ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('agent.full_name')->label('Agent')
                // ->toggleable(isToggledHiddenByDefault: true)
                ->color('danger')
                ->url(fn (Model $record) => AgentResource::getUrl('edit', ['record' => $record->agent_id ?? 0])),
                // ->url(fn (Model $record) => AgentResource::getUrl('edit', $record->agent)),
                Tables\Columns\IconColumn::make('agent.agent_type')->label('In-House Agent')->boolean()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('note')->label('Notes')
                ->lineClamp(2)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.id')
                    ->label('Encoder')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        return $record->user->first_name . " " . $record->user->last_name;
                    })
                    
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('agent_id')
                ->multiple()
                ->label('Agent')
                ->options(Agent::all()->pluck('full_name', 'id'))
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('ispickup')
                    ->label('pickup/Dropoff')
                ->color('primary')
    ->icon('heroicon-o-clipboard-document-check')
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        $record->update([
                         'is_pickup' => true,
                        ]);
                    }
                }),
                BulkAction::make('ispaid')
                    ->label('Is Paid')
                ->color('success')
    ->icon('heroicon-o-currency-dollar')
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        $record->update([
                         'is_paid' => true,
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
            'index' => Pages\ListUnpickedboxes::route('/'),
            'create' => Pages\CreateUnpickedboxes::route('/create'),
            'edit' => Pages\EditUnpickedboxes::route('/{record}/edit'),
        ];
    }
//     public static function getEloquentQuery(): Builder
// {
//     return parent::getEloquentQuery()->Unpickedbox();

   
// }
}
