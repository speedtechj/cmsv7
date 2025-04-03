<?php

namespace App\Filament\Resources;

use App\Models\Log;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Logactivity;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LogResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// use Rmsramos\Activitylog\Resources\ActivityLogResource;
use App\Filament\Resources\LogResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class LogResource extends ActivityLogResource implements HasShieldPermissions
{
    

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
        ];
    }
    
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject_type')
                ->label('Model'),
                Tables\Columns\TextColumn::make('name')
                ->label('User')
                ->getStateUsing(function ($record) {
                    return $record->causer->full_name;
                }),
                 tables\Columns\TextColumn::make('event'),
                 Tables\Columns\TextColumn::make('description')
                ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable(),
            ])
           
            ->defaultSort(config('filament-activitylog.resources.default_sort_column', 'created_at'), config('filament-activitylog.resources.default_sort_direction', 'asc'))
            ->filters([
                static::getDateFilterComponent(),
                static::getEventFilterComponent(),
                
               
            ])
            
            ->actions([
                Tables\Actions\DeleteAction::make(),
                // ActivityLogTimelineTableAction::make('Activities'),
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
            'index' => Pages\ListLogs::route('/'),
            // 'create' => Pages\CreateLog::route('/create'),
            'view' => Pages\ViewLog::route('/{record}'),
            // 'edit' => Pages\EditLog::route('/{record}/edit'),
        ];
    }
}
