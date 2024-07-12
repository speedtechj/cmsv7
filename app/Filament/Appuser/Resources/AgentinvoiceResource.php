<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Agentinvoice;



use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\AgentinvoiceResource\Pages;
use App\Filament\Appuser\Resources\AgentinvoiceResource\RelationManagers;

class AgentinvoiceResource extends Resource
{
    protected static ?string $model = Agentinvoice::class;

    protected static ?string $navigationLabel = 'Agent Invoice';
    public static ?string $label = 'Agent Invoice';
    protected static ?string $navigationGroup = 'App Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultGroup('agent.full_name')
            ->columns([
                Tables\Columns\TextColumn::make('agent.full_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('manual_invoice')
                    ->label('Manual Invoice')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_issued')
                    ->label('Date Issued')
                    ->date()
                    ->sortable(),
                // Tables\Columns\IconColumn::make('is_used')
                //     ->label('Is Used')
                //     ->boolean(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->numeric()
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
            ->headerActions([
                Tables\Actions\Action::make('createinvoice')
                ->label('Create Manual Invoice')
                ->slideOver()
                ->form([
                        Forms\Components\Select::make('agent_id')
                        ->relationship('agent','full_name')
                        ->searchable()
                        ->preload()
                        ->required(),
                        Forms\Components\DatePicker::make('date_issued')
                        ->native(false)
                        ->closeOnDateSelection()
                        ->required(),
                        Forms\Components\TextInput::make('start_invoice')
                        ->label('Starting Invoice')
                        ->required()
                        ->numeric(),
                        Forms\Components\TextInput::make('end_invoice')
                        ->label('Ending Invoice')
                        ->required()
                        ->numeric(),
                ])
                ->action(function (array $data): void {
                   for($i = $data['start_invoice']; $i <= $data['end_invoice']; $i++){
                        Agentinvoice::create([
                            'agent_id' => $data['agent_id'],
                            'manual_invoice' => $i,
                            'date_issued' => $data['date_issued'],
                            'user_id' => auth()->id(),
                        ]);
                   }
                })
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAgentinvoices::route('/'),
            'create' => Pages\CreateAgentinvoice::route('/create'),
            // 'edit' => Pages\EditAgentinvoice::route('/{record}/edit'),
        ];
    }
    
}
