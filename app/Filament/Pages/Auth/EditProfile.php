<?php

namespace App\Filament\Pages\Auth;


 
use Filament\Forms\Form;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;



class EditProfile extends BaseEditProfile
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.auth.edit-profile';
    protected static string $layout = 'filament-panels::components.layout.index';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Information')
                ->aside()
                ->schema([
                    TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                    TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                ]),
                
            ]);
    }
    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            // $this->getCancelFormAction(),
        ];
    }
    public function getFormActionsAlignment(): string | Alignment
    {
        return Alignment::End;
    }
}
