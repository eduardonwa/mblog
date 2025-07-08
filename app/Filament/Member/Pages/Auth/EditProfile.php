<?php

namespace App\Filament\Member\Pages\Auth;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Bio')
                    ->maxLength(255),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}