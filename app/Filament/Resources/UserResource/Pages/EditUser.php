<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use App\Filament\Resources\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\Rules\Password;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('changePassword')
            ->label('Change Password')
            ->form([
                Section::make('User Change Password')
                ->schema([
                    TextInput::make('new_password')
                    ->label('New Password')
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required()
                    ->rule(Password::default()),
                    TextInput::make('new_password_confirmation')
                    ->label('Confirmation Password')
                    ->password()
                    ->required()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->same('new_password')
                    ->rule(Password::default()),
                ])
               
            ])->action(function(array $data){
                $this->record->update([
                    'password' => Hash::make($data['new_password']),
                ]);
                Notification::make()
                ->title('Change successfully')
                ->success()
                ->send();
            }),
        ];
    }
}
