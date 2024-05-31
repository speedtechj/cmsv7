<?php

namespace App\Filament\Appuser\Resources\SenderResource\RelationManagers;


use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Cityphil;
use App\Models\Receiver;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Barangayphil;
use App\Models\Provincephil;
use App\Models\Receiveraddress;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\ReceiverResource;
use Filament\Resources\RelationManagers\RelationManager;

class ReceiverRelationManager extends RelationManager
{
    protected static string $relationship = 'receiver';
    public static ?string $title = 'Receiver Information';
    protected static ?string $recordTitleAttribute = 'full_name';
    public function form(Form $form): Form
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
                    ->mask('+63(999)999-9999')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('home_no')
                    ->mask('+63(999)999-9999')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                MarkdownEditor::make('remark')
                    ->label('Note')
                    ->columnSpan('full')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->url(fn(Receiver $record) => ReceiverResource::getUrl('edit', ['record' => $record])),
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Create New Receiver')
                    ->label('New Receiver')
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
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->requiresConfirmation(),
                    Tables\Actions\Action::make('newaddress')->label('New Address')
                    ->color('success')
                    ->icon('heroicon-o-map-pin')
                    ->slideOver()
                    ->form([  
                    Section::make('Create New Address')
                    ->columns(2)
                    ->schema([
                    Forms\Components\TextInput::make('address')
                    ->columnSpan('full')
                        ->required()
                        ->maxLength(255),
                        Forms\Components\Select::make('provincephil_id')
                        ->label('Province')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->options(Provincephil::all()->pluck('name', 'id')->toArray())
                        ->reactive()
                        ->afterStateUpdated(function (callable $set) {
                            $set('cityphil_id', null);
                            $set('barangayphil_id', null);
                        }),
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
                        ->afterStateUpdated(function (callable $set) {
                            $set('barangayphil_id', null);
                        }),
                    Forms\Components\Select::make('barangayphil_id')
                        ->label('Barangay')
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->required()
                        // ->relationship('cityphil', 'id')
                        ->options(function (callable $get) {
                            $city = Cityphil::find($get('cityphil_id'));
    
                             if (!$city) {
                                 // return $province->cityphil->pluck('city', 'id');
                                 return null;
                             }
                             return $city->barangayphil->pluck('name', 'id');
    
                         })->createOptionForm([

                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                         ])->createOptionUsing(function (array $data, Get $get, Set $set) {
                            Barangayphil::create([
                                'cityphil_id' => $get('cityphil_id'),
                                'name' => $data['name'],
                            ]);
                        })->createOptionModalHeading('Create Barangay'),
                    Forms\Components\TextInput::make('zip_code')
                        ->maxLength(255),
                    ])
                    ])->action(function (Receiver $record, array $data) {
                            $zoneid = Cityphil::find($data['cityphil_id'])->zone_id;
                           Receiveraddress::create([
                                'receiver_id' => $record->id,
                                'address' => $data['address'],
                                'provincephil_id' => $data['provincephil_id'],
                                'cityphil_id' => $data['cityphil_id'],
                                'barangayphil_id' => $data['barangayphil_id'],
                                'zip_code' => $data['zip_code'],
                                'loczone' => $zoneid,
                                
                            ]);
                    }),
                    
                    
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
