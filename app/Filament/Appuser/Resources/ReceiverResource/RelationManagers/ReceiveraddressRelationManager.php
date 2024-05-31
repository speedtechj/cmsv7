<?php

namespace App\Filament\Appuser\Resources\ReceiverResource\RelationManagers;


use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use App\Models\Cityphil;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Provincephil;
use App\Models\Receiveraddress;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Appuser\Resources\SenderResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ReceiveraddressRelationManager extends RelationManager
{
    protected static string $relationship = 'receiveraddress';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\Select::make('provincephil_id')
                    ->relationship('provincephil', 'name')
                    ->label('Province')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(Provincephil::all()->pluck('name', 'id')->toArray())
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('cityphil_id', null)),
                    Forms\Components\Select::make('cityphil_id')
                    ->label('City')
                    // ->relationship('cityphil', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->options(function (callable $get) {
                       $province = Provincephil::find($get('provincephil_id'));

                        if (!$province) {
                            // return $province->cityphil->pluck('city', 'id');
                            return null;
                        }
                        return $province->cityphil->pluck('name', 'id');

                    })
                    ->afterStateUpdated(function (Set $set){
                                $set('barangayphil_id', null);
                    }),
                Forms\Components\Select::make('barangayphil_id')
                    ->label('Barangay')
                    ->required()
                    // ->searchable()
                    ->preload()
                    ->options(function (callable $get) {
                        $city = Cityphil::find($get('cityphil_id'));

                         if ($city) {
                             return $city->barangayphil->pluck('name', 'id');
                            
                            //  return Barangayphil::all()->pluck('name', 'id')->toArray();
                         }
                        //  return $city->barangayphil->pluck('name', 'id');

                     }),
                Forms\Components\TextInput::make('zip_code')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('address')
            ->columns([
                Tables\Columns\TextColumn::make('receiver.sender.full_name')
                ->url(fn (Receiveraddress $record) => SenderResource::getUrl('edit', ['record' => $record->receiver->sender])),
                Tables\Columns\TextColumn::make('address')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('provincephil.name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('cityphil.name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('barangayphil.name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('zip_code')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('loczone')
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->slideOver()
                ->mutateFormDataUsing(function (array $data): array {
                    $zone = Cityphil::find($data['cityphil_id']);
                    // $data['user_id'] = auth()->id();
                    // $data['branch_id'] = 1;
                    $data['loczone'] = $zone->zone_id;
                   
                    return $data;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->slideOver(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
