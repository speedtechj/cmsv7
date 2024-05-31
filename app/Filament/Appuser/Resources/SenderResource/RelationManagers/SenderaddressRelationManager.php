<?php

namespace App\Filament\Appuser\Resources\SenderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Provincecan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SenderaddressRelationManager extends RelationManager
{
    protected static string $relationship = 'senderaddress';
    
    public static ?string $title = 'Sender Address';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('address')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('provincecan_id')
                ->relationship('provincecan', 'name')
                ->label('Province')
                ->searchable()
                ->preload()
                ->required()
                ->options(Provincecan::all()->pluck('name', 'id')->toArray())
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('citycan_id', null)),
            Forms\Components\Select::make('citycan_id')
                ->label('City')
                ->relationship('citycan', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->options(function (callable $get) {
                    $province = Provincecan::find($get('provincecan_id'));
                    if (!$province) {
                        // return Citycan::all()->pluck('name', 'id');
                        return null;
                    }
                    return $province->citycan->pluck('name', 'id');
                }),
            Forms\Components\Select::make('quadrant')
            ->options([
                'NW' => 'North West',
                'SW' => 'South West',
                'NE' => 'North East',
                'SE' => 'South East',
            ]),
            Forms\Components\TextInput::make('postal_code')
            ->mask('a9a 9a9')
                ->required()
                ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('address')
            ->columns([
                Tables\Columns\TextColumn::make('address')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('provincecan.name')
                ->label('Province Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('citycan.name')
                ->label('City Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('quadrant')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('postal_code')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('New Sender Address')
                ->slideOver()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
                    return $data;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->slideOver(),
                Tables\Actions\DeleteAction::make()
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
