<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\logsetting;
use Filament\Tables\Table;
use App\Models\Customerloginfo;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\CustomerloginfoResource\Pages;
use App\Filament\Appuser\Resources\CustomerloginfoResource\RelationManagers;
use App\Filament\Appuser\Resources\CustomerloginfoResource\RelationManagers\CalllogsRelationManager;

class CustomerloginfoResource extends Resource
{
    protected static ?string $model = Customerloginfo::class;

    protected static ?string $navigationLabel = 'Customer info';
    public static ?string $label = 'Customer info';
    protected static ?string $navigationGroup = 'Call Log';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10,25,50])
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('mobile_no')
                    ->label('Mobile Number'),
                Tables\Columns\TextColumn::make('home_no'),
                Tables\Columns\TextColumn::make('Total_box')
                    ->label('Total Box Send')
                    ->getStateUsing(function (Model $record) {
                        $test = $record->bookings->count();

                        return $test;
                    }),
            ])
            ->filters([
                Filter::make('no_bookings')
                    ->label('No Bookings')
                    ->query(function (Builder $query) {
                        return $query->has('bookings', '=', 0);
                    }),
                    Filter::make('no_call_logs')
                    ->label('No Call Logs')
                    ->query(function (Builder $query) {
                        return $query->doesntHave('callLogs');
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Call Log')
                    ->icon('heroicon-o-phone')
                    ->color('primary')
                    ->visible(function (Model $record) {
                        // $date_range = logsetting::where('is_active', 1)->first();
                        // return $result = $record->callLogs->whereBetween('calldate', [$date_range->start_date, $date_range->end_date])->isEmpty();
                      
                        // 30';Post::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])->get();
                        // dd($test);
                        // $test = $record->callLogs->isEmpty();
                        // return $test;
                        // return $record->callLogs->isEmpty();
                        // dd($record->id);
                        return true;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Customer Details')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('first_name')
                            ->label('First Name'),
                        TextEntry::make('last_name')
                            ->label('Last Name'),
                        // TextEntry::make('sender.full_name')
                        //     ->label('Name')
                        //     ->helperText('Click to Edit')
                        //     ->url(fn(Model $record) => SenderResource::getUrl('edit', ['record' => $record->sender])),
                        // TextEntry::make('senderaddress.address')
                        //     ->label('Address')
                        //     ->url(fn(Model $record) => SenderaddressResource::getUrl('edit', ['record' => $record->senderaddress])),

                        TextEntry::make('mobile_no')
                            ->label('Mobile Number'),
                        TextEntry::make('home_no')
                            ->label('Home Number'),
                        TextEntry::make('email')
                            ->label('Email'),
                    ]),


            ]);
    }

    public static function getRelations(): array
    {
        return [
            CalllogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerloginfos::route('/'),
            'create' => Pages\CreateCustomerloginfo::route('/create'),
            'view' => Pages\ViewCustomerinfolog::route('/{record}'),
            // 'edit' => Pages\EditCustomerloginfo::route('/{record}/edit'),
        ];
    }
}
