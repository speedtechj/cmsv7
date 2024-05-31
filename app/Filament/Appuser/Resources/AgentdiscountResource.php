<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use App\Models\Zone;
use Filament\Tables;
use App\Models\Agent;
use App\Models\Branch;
use App\Models\Boxtype;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Servicetype;
use App\Models\Agentdiscount;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\AgentdiscountResource\Pages;
use App\Filament\Appuser\Resources\AgentdiscountResource\RelationManagers;

class AgentdiscountResource extends Resource
{
    protected static ?string $model = Agentdiscount::class;
    protected static ?string $navigationLabel = 'Agent Discount';
    public static ?string $label = 'Agent Discounts';
    protected static ?string $navigationGroup = 'App Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('agent_id')
                    ->label('Agent Name')
                    ->options(Agent::where('agent_type', '0')->pluck('full_name', 'id'))
                    ->searchable()
                    ->preload(),
                    Select::make('servicetype_id')
                    ->label('Servicetype')
                    ->options(Servicetype::where('id', 1)->pluck('description', 'id'))
                    ->searchable(),
                    Forms\Components\Select::make('zone_id')
                    ->label('Location')
                    ->options(Zone::all()->pluck('description', 'id'))
                    ->required(),
                    Forms\Components\Select::make('boxtype_id')
                    ->label('Box Type')
                    ->options(Boxtype::all()->pluck('description', 'id'))
                    ->required(),
                    Forms\Components\Select::make('branch_id')
                    ->label('Branch')
                    ->options(Branch::all()->pluck('business_name', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('discount_amount')
                    ->prefix('$')
                    ->required(),
                    Toggle::make('is_active')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('agent.full_name')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('servicetype.description')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('zone.description')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('boxtype.description')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Is Active')
                    ->boolean()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('user.id')
                ->label('Created By')
                ->getStateUsing(function (Model $record) {
                    return $record->user->first_name . " " . $record->user->last_name;
                })
                ->searchable()
                ->sortable()
                ->toggleable(),
                
                Tables\Columns\TextColumn::make('branch.business_name')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('code')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('description')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('discount_amount')->money('USD')
                ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable()
                ->sortable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->searchable()
                ->sortable()
                ->toggleable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgentdiscounts::route('/'),
            'create' => Pages\CreateAgentdiscount::route('/create'),
            'edit' => Pages\EditAgentdiscount::route('/{record}/edit'),
        ];
    }
}
