<?php

namespace App\Filament\Twelve24\Pages;

use App\Models\Booking;
use Filament\Forms\Components\Concerns\CanBeAutofocused;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Pages\Page;

use Filament\Tables\Table;
use App\Models\Trackstatus;
use App\Models\Invoicestatus;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Set;
use livewire;





class Searchinv extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Search Invoice';
    protected static ?string $navigationGroup = 'Invoice Status';
    // protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Search Invoice';


    protected static string $view = 'filament.twelve24.pages.searchinv';


    public ?array $data = [];
    public $invoiceid = " ";
    public $remarks;

    public function mount(): void
    {
       
        $this->form->fill();
       

    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Select::make('invoice_status')
                                    ->live()
                                    ->label('Invoice Status')
                                    ->searchable()
                                    ->options(Trackstatus::all()->where('branch_id', auth()->user()->branch_id)->pluck('description', 'id'))
                                    ->required()
                                    ->afterStateUpdated(fn() => $this->dispatch('focusSecondField')),
                                TextInput::make('invoice')
                                    ->label(' Search Invoice Number')
                                    ->autofocus()
                                    ->placeholder('Search Invoice Number')
                                    ->numeric()
                                    ->maxLength(7)
                                    ->required()
                                    ->prefixIcon('heroicon-o-magnifying-glass')
                                    ->columnSpan('full')
                                    ->prefixIconColor('success')
                                    ->suffixActions([
                                        Action::make('reset')
                                            ->label('Reset')
                                            ->icon('heroicon-o-arrow-path')
                                            ->action(function ( Component $livewire) {
                                                $this->invoiceid = " ";
                                                $this->resetTable();
                                                $this->data['invoice'] = " ";
                                                $this->resetValidation();
                                                

                                            })

                                    ])
                            ])->columns(1)
                    ]),
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                DatePicker::make('Date_update')
                                    ->default(now())
                                    ->native(false)
                                    ->closeOnDateSelection()
                                    ->label('Dated Updated')
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('location')
                                    ->label('Location'),
                                TextInput::make('way_bill')
                                    ->label('Waybill'),
                                Textarea::make('remarks')
                                    ->rows(3)
                                    ->label('Remarks')
                            ])->columns(2)
                    ])

            ])->columns(2)

            ->statePath('data');
    }

    public function search(): void
    {

        $this->validate();
        $book_result = Booking::where('manual_invoice', $this->data['invoice'])
            ->orWhere('booking_invoice', $this->data['invoice'])
            ->first();
       
        $invoicestatus = Invoicestatus::where('generated_invoice', $this->data['invoice'])
            ->orWhere('manual_invoice', $this->data['invoice'])->get();
        // $invoice_result = $invoicestatus->where('trackstatus_id', $this->data['invoice_status'])->first();
        $invoice_result = $invoicestatus->where('trackstatus_id', $this->data['invoice_status'])->first();
        // dd($invoice_result);
        if ($book_result) {
            $this->invoiceid = $this->data['invoice'];
            if (!$invoice_result) {
                
                Invoicestatus::create([
                    'generated_invoice' => $book_result->booking_invoice,
                    'manual_invoice' => $book_result->manual_invoice,
                    'provincephil_id' => $book_result->receiveraddress->provincephil_id,
                    'cityphil_id' => $book_result->receiveraddress->cityphil_id,
                    'booking_id' => $book_result->id,
                    'trackstatus_id' => $this->data['invoice_status'],
                    'date_update' => $this->data['Date_update'],
                    'remarks' => $this->data['remarks'],
                    'user_id' => auth()->user()->id,
                    'batch_id' => $book_result->batch_id,
                    'receiver_id' => $book_result->receiver_id,
                    'sender_id' => $book_result->sender_id,
                    'boxtype_id' => $book_result->boxtype_id,
                    'waybill' => $this->data['way_bill'],
                    'location' => $this->data['location'],

                ]);

                Notification::make()
                    ->title('Saved successfully')
                    ->success()
                    ->send();
                 $this->data['invoice'] = " ";
            } else {
                $this->data['invoice'] = " ";
                Notification::make()
                    ->title('Status Already  Exist')
                    ->info()
                    ->send();
            }

        } else {
            $this->data['invoice'] = " ";
            $this->invoiceid = " ";
            $this->resetTable();
            Notification::make()
                ->title('Invoice is not  exist')
                ->danger()
                ->send();
        }
        // $this->data['invoice'] = " ";
        $this->resetTable();
        
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(Invoicestatus::query()->where('generated_invoice', $this->invoiceid)->orWhere('manual_invoice', $this->invoiceid))
            ->deferLoading()
            ->columns([
                TextColumn::make('invoice')
                    ->label('Invoice')
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        if ($record->manual_invoice != null) {
                            return $record->manual_invoice;
                        } else {
                            return $record->generated_invoice;
                        }
                    }),
                TextColumn::make('boxtype.description')
                    ->label('Box Type')
                    ->sortable(),
                TextColumn::make('sender.full_name')
                    ->label('Sender Name'),
                TextColumn::make('receiver.full_name')
                    ->label('Receiver Name'),
                TextColumn::make('trackstatus.description')
                    ->label('Status')
                    ->sortable(),
                TextColumn::make('provincephil.name')->label('Province'),
                TextColumn::make('cityphil.name')->label('City'),
                TextColumn::make('date_update')->label('Date Updated'),
                TextColumn::make('user.full_name')->label('Update By')
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('remarks')->label('Remarks')
                ->label('Remarks')
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('waybill')->label('Waybill')
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location')->label('Location')
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Created At')
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Updated At')
                ->toggleable(isToggledHiddenByDefault: true),
                
            ])->defaultSort('date_update', 'desc')
            ->filters([
                // ...
            ])
            ->actions([
                DeleteAction::make()
                 ->requiresConfirmation()
    ->action(function (Model $record): void{
            $record->delete();
            Notification::make()
            ->title('Delete successfully')
            ->success()
            ->send();
    }),
                EditAction::make()
                ->label('Edit')
                
                ->mutateRecordDataUsing(function (Model $record, array $data): array {
                    $data['date_update'] = $record->date_update;
                    $data['remarks'] = $record->remarks;
                    $data['location'] = $record->location;
                    $data['waybill'] = $record->waybill;
                    return $data;
                })
                // ->slideOver()
                ->form([
                    DatePicker::make('date_update')
                        ->label('Date Updated')
                        ->native(false)
                        ->closeOnDateSelection()
                        ->required(),
                    TextInput::make('location'),
                    TextInput::make('waybill'),
                    MarkdownEditor::make('remarks'),
                ])
                ->action(function (Model $record, array $data): void {
                  
                    $record->update([
                        'date_update' => $data['date_update'],
                        'remarks' => $data['remarks'],
                        'location' => $data['location'],
                        'waybill' => $data['waybill'],
                    ]);
                    Notification::make()
                    ->title('Update successfully')
                    ->success()
                    ->send();
                }),
                
            ])
            ->bulkActions([
               
                // ...
            ]);
    }
}
