<?php

namespace App\Filament\Member\Pages\Auth;

use Filament\Forms\Form;
use App\Rules\ValidateSocialUrl;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('user_avatar')
                    ->label('')
                    ->collection('user_avatar')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->disk('public')
                    ->maxSize(2048)
                    ->extraAttributes([
                        'class' => 'custom-avatar-uploader',
                        'data-style-panel-layout' => 'compact circle',
                    ])
                    ->columnSpanFull(),
                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->maxLength(255),
                Textarea::make('bio')
                    ->label('Bio')
                    ->helperText('180 characters allowed')
                    ->maxLength(180),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                Section::make('Links')
                    ->label('Your social networks')
                    ->schema([
                        TextInput::make('social_links.youtube')
                            ->label('Youtube')
                            ->url()
                            ->rules([ValidateSocialUrl::for('youtube')])
                            ->nullable(),
                        TextInput::make('social_links.instagram')
                            ->label('Instagram')
                            ->url()
                            ->rules([ValidateSocialUrl::for('instagram')])
                            ->nullable(),
                        TextInput::make('social_links.bandcamp')
                            ->label('Bandcamp')
                            ->url()
                            ->rules([ValidateSocialUrl::for('bandcamp')])
                            ->nullable(),
                    ])
            ]);
    }

    public function getMaxWidth(): string
    {
        return 'max-w-2xl';
    }
}