<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Companyinfo;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;

class Editcompanyinfo extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Company Information';
    protected static ?string $title = 'Company Information';
    protected static string $view = 'filament.pages.editcompanyinfo';
    public ?array $data = [];

    public function mount(Companyinfo $companyinfo): void
    {
        $this->form->fill($companyinfo->first()->toArray());

    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Company Information')
                    ->schema([
                        FileUpload::make('company_logo')
                            ->label('Company Logo')
                            ->preserveFilenames()
                            ->image()
                            ->imageEditor()
                            ->enableDownload()
                            ->disk('public')
                            ->directory('logo')
                            ->visibility('private')
                            ->enableOpen(),
                        TextInput::make('company_name')
                            ->required(),
                        TextInput::make('company_address')
                            ->required(),
                        TextInput::make('company_phone')
                            ->required(),
                        TextInput::make('company_email')
                            ->required(),
                        TextInput::make('company_website')
                            ->required(),
                        TextInput::make('company_tracking')
                            ->required(),
                        TextInput::make('barcode_label')
                            ->required(),
                        TextInput::make('company_email')
                            ->required(),
                        TextInput::make('company_slogan')
                            ->required(),
                    ])->columns(2)

            ])->statePath('data');
    }
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
    }
    public function save(Companyinfo $companyinfo)
    {
        try {
            $data = $this->form->getState();
        //    dd( $companyinfo->first());
             $companyinfo->first()->update($data);
             Notification::make()
             ->title('Update successfully')
             ->success()
             ->send();
        } catch (Halt $exception) {
            return;
        }
    }

}

