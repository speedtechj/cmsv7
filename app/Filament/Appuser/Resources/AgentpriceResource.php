<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use App\Models\Zone;
use Filament\Tables;
use App\Models\Agent;
use Filament\Forms\Form;
use App\Models\Agentprice;
use Filament\Tables\Table;
use App\Models\Servicetype;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\AgentpriceResource\Pages;
use App\Filament\Appuser\Resources\AgentpriceResource\RelationManagers;

class AgentpriceResource extends Resource
{
    protected static ?string $model = Agentprice::class;
    protected static ?string $navigationLabel = 'Agent Price';
    public static ?string $label = 'Agent Prices';
    protected static ?string $navigationGroup = 'App Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('agent_id')
                    ->label('Agent Name')
                    ->options(Agent::where('agent_type', '0')->pluck('full_name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('servicetype_id')
                    ->label('Servicetype')
                    ->required()
                    ->options(Servicetype::where('id', 1)->pluck('description', 'id'))
                    ->searchable(),
                Select::make('boxtype_id')
                    ->required()
                    ->relationship('boxtype', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->description} {$record->dimension}"),
                Select::make('zone_id')
                    ->label('Zone')
                    ->required()
                    ->options(Zone::all()->pluck('description', 'id'))
                    ->searchable(),

                Forms\Components\TextInput::make('price')
                    ->prefix('$')
                    ->numeric()
                    ->maxValue(42949672.95)
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('agent.full_name')
                ->label('Agent Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('servicetype.description')
                ->label('Service Type')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('boxtype.description')
                ->label('Box Type')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('zone.description')
                ->label('Location')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('price')->money('USD')
                ->label('Price')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('note')
                ->label('Note')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->searchable()
                ->sortable()
                ->dateTime(),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                SelectFilter::make('agent_id')->label('Agent Name')
                ->searchable()
                    ->relationship('agent', 'full_name', fn (Builder $query) => $query->where('agent_type', '0')),
                SelectFilter::make('zone_id')->relationship('zone', 'description')->label('Area'),
                SelectFilter::make('boxtype_id')->relationship('boxtype', 'description')->label('Boxtype'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgentprices::route('/'),
            'create' => Pages\CreateAgentprice::route('/create'),
            'edit' => Pages\EditAgentprice::route('/{record}/edit'),
        ];
    }
}
