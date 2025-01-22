<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Batchpackinglist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\BatchpackinglistResource\Pages;
use App\Filament\Appuser\Resources\BatchpackinglistResource\RelationManagers;

class BatchpackinglistResource extends Resource
{
    protected static ?string $model = Batchpackinglist::class;
    protected static ?string $navigationLabel = 'Batch Packing List';
    public static ?string $label = 'Batch Packing List';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Batch Packing List Information')
                ->schema([ Forms\Components\Select::make('batch_id')
                    ->label('Batch')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Batch Packing List already exists.',
                    ])
                    ->relationship('batch', 'batchno',
                    modifyQueryUsing: fn (Builder $query) => $query->where('is_lock',false))
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->batchno} - {$record->batch_year}"),
                    Forms\Components\FileUpload::make('packinglist_attachment')
                    ->label('Packing List Attachment')
                    ->multiple()
                    ->panelLayout('grid')
                    ->uploadingMessage('Uploading attachment...')
                    ->openable()
                    ->disk('public')
                    ->directory('bachpackinglist')
                    ->visibility('private')
                    ->required()
                    ->removeUploadedFileButtonPosition('right'),
                Forms\Components\MarkdownEditor::make('remarks')
                    ->columnSpanFull(),]),
               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batch_id')
                    ->label('Batch')
                    ->sortable()
                    ->formatStateUsing(function ( $record) {
                        return $record->batch->batchno .'-' .$record->batch->batch_year;
                    }),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Created By')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('remarks')
                    ->label('Note/Remarks')
                    ->wrap(),
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
            'index' => Pages\ListBatchpackinglists::route('/'),
            'create' => Pages\CreateBatchpackinglist::route('/create'),
            'edit' => Pages\EditBatchpackinglist::route('/{record}/edit'),
        ];
    }
}
