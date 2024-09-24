<?php

namespace App\Filament\Appuser\Resources\CustomerloginfoResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Calltype;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CalllogsRelationManager extends RelationManager
{
    protected static string $relationship = 'calllogs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Call Log Information')
                    ->schema([
                        Forms\Components\Select::make('calltype_id')
                            ->label('Call Type')
                            ->live()
                            ->options(Calltype::all()->pluck('description', 'id'))
                            ->required()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $script_note = Calltype::find($get('calltype_id'))->notes;
                                $set('scriptnote', $script_note);
                            }),
                        Forms\Components\Textarea::make('scriptnote')
                            ->hiddenOn('edit')
                            ->rows(10)
                            ->readOnly()
                            ->dehydrated(false)
                            ->label('Script Note'),
                        Forms\Components\MarkdownEditor::make('callnotes')
                            ->label('Call Notes'),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                Tables\Columns\TextColumn::make('calltype.description')
                    ->label('Call Type'),
                Tables\Columns\TextColumn::make('callnotes')
                    ->label('Call Notes')
                    ->limit(10),
                Tables\Columns\TextColumn::make('calldate')
                    ->label('Call Date'),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Sender'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->createAnother(false)
                    ->slideOver()
                    ->mutateFormDataUsing(function ($data) {

                        $data['user_id'] = auth()->user()->id;
                        $data['sender_id'] = $this->getOwnerRecord()->id;
                        $data['calldate'] = now();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
