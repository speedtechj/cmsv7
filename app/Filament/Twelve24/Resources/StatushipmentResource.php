<?php

namespace App\Filament\Twelve24\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Trackstatus;
use App\Models\Invoicestatus;
use App\Models\Statushipment;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Twelve24\Resources\StatushipmentResource\Pages;
use App\Filament\Twelve24\Resources\StatushipmentResource\RelationManagers;

class StatushipmentResource extends Resource
{
    protected static ?string $model = Statushipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            
            ->columns([
                Tables\Columns\TextColumn::make('booking_invoice')
                ->label('Invoice')
                ->searchable(isIndividual: true, isGlobal: false)
                ->label('Generated Invoice')
                ->sortable(),
            Tables\Columns\TextColumn::make('manual_invoice')
                ->label('Manual Invoice')
                ->searchable(isIndividual: true, isGlobal: false)
                ->sortable(),
            // Tables\Columns\TextColumn::make('trackstatus.description')
            //     ->label('Status')
            //     ->sortable(),
            Tables\Columns\TextColumn::make('batch.batchno')
                ->label('Batch Number')
                ->sortable(),

           Tables\Columns\TextColumn::make('Status')
                    ->badge()
                    ->label('Status')
                    ->color(fn(string $state): string => match ($state) {
                        
                        'Not Delivered' => 'warning',
                        'Delivered' => 'success',
                    })
                    ->getStateUsing(function (Model $record) {
                        $statuscode = Trackstatus::where('code', 'ed')->first()->id;
                     
                    
                        $status = Invoicestatus::where('generated_invoice',$record->booking_invoice)
                        ->where('manual_invoice', $record->manual_invoice)
                        ->where('trackstatus_id', $statuscode)->first();
                       if($status == null){
                        return 'Not Delivered';
                       }else{
                        return 'Delivered';
                       }
                        

                    }
                    ),
                    
            Tables\Columns\TextColumn::make('boxtype.description')
                ->label('Box Type')

                ->sortable(),

            Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender Name')

                ->sortable(),
            // ->url(fn (Booking $record) => route('filament.resources.senders.edit', $record->sender)),
            Tables\Columns\TextColumn::make('receiver.full_name')
                ->label('Receiver')

                ->sortable(),
            // ->url(fn (Booking $record) => route('filament.resources.receivers.edit', $record->receiver)),
            Tables\Columns\TextColumn::make('provincephil.name')
                ->label('Province')

                ->sortable(),
            Tables\Columns\TextColumn::make('cityphil.name')
                ->label('City')

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
            ->filters([
                SelectFilter::make('batch_id')
                ->multiple()
                ->options(Batch::Currentyear())
                ->placeholder('Select Batch Number')
                ->label('Batch Number')
                ->default(array('Select Batch Number')),
               
        ])
        ->defaultSort('id', 'desc')
        
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
            'index' => Pages\ListStatushipments::route('/'),
            'create' => Pages\CreateStatushipment::route('/create'),
            'edit' => Pages\EditStatushipment::route('/{record}/edit'),
        ];
    }
}
