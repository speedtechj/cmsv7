<?php

namespace App\Filament\Appuser\Resources;

use App\Filament\Appuser\Resources\PosResource\RelationManagers\PospaymentsRelationManager;
use App\Filament\Appuser\Resources\PosResource\RelationManagers\PurchaseitemsRelationManager;
use App\Models\Pos;
use Filament\Forms;
use Filament\Tables;
use App\Models\Sender;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;

use Filament\Infolists\Components\Section;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\PosResource\Pages;
use App\Filament\Appuser\Resources\PosResource\RelationManagers;
use App\Filament\Appuser\Resources\PosResource\RelationManagers\PosinvoicesRelationManager;

class PosResource extends Resource
{
    protected static ?string $model = Pos::class;

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
        ->headerActions([
            CreateAction::make()
            ->mutateFormDataUsing(function (array $data): array {
                $data['user_id'] = auth()->id();
                $data['branch_id'] = auth()->user()->branch_id;
                return $data;
            })
            ->slideOver()
            ->modalHeading('Create New Customer')
            ->label('Create New Customer')
            ->form([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('last_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('mobile_no')
            ->live(onBlur:true)
                ->unique(ignorable: fn($record) => $record)
                ->mask('(999)999-9999')
                ->stripCharacters(['(',')','-'])
                ->required(),
                
            Forms\Components\TextInput::make('home_no')
                ->mask('(999)999-9999')
                ->stripCharacters(['(',')','-']),
            Forms\Components\TextInput::make('email')
                ->unique(ignorable: fn($record) => $record)
                ->email()
                ->required()
                ->maxLength(255),
                Forms\Components\MarkdownEditor::make('remark')
                ->label('Note'),
                    ])->columns(2) 
                ])
            
            ->action(function (array $data): void {
                Pos::create($data);
            })
        ])
        ->paginated([10, 25])
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('last_name')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('full_name')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('mobile_no')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('home_no')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('email')
                ->searchable(isIndividual: true, isGlobal: false),
                
            ])->defaultSort('created_at','desc')
            
            ->filters([
                //
            ])
          
            ->actions([
               
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                  
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            Section::make('Personal Information')
            ->schema([
            Infolists\Components\TextEntry::make('full_name')->label('Full Name'),
            Infolists\Components\TextEntry::make('mobile_no')->label('Mobile No'),
            Infolists\Components\TextEntry::make('home_no')->label('Home No'),
            Infolists\Components\TextEntry::make('email')->label('Email'),
            ])->columns(4)
              
        ]);
}

    public static function getRelations(): array
    {
        return [
            PosinvoicesRelationManager::class,
            PurchaseitemsRelationManager::class,
            PospaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPos::route('/'),
            // 'create' => Pages\CreatePos::route('/create'),
            // 'edit' => Pages\EditPos::route('/{record}/edit'),
            'view' => Pages\ViewPos::route('/{record}'),
        ];
    }
}
