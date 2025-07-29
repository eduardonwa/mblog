<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Moderation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('username'),
                SpatieMediaLibraryFileUpload::make('user_avatar')
                    ->collection('user_avatar')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->disk('public')
                    ->maxSize(2048)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('user_avatar')
                    ->conversion('thumb')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://www.gravatar.com/avatar/'.md5($record->email).'?d=identicon'),
                TextColumn::make('username')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d M, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                    SelectFilter::make('user_role')
                        ->options([
                            'admin' => 'Admin',
                            'staff' => 'Staff',
                            'member' => 'Member',
                        ])
                        ->query(function (Builder $query, array $data) {
                            if (!empty($data['value'])) {
                                $query->role($data['value']); // Usa el método role() directamente
                            }
                        }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                    Action::make('restore')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->action(fn ($record) => $record->restore())
                        ->visible(fn ($record) => $record->trashed())
                        ->requiresConfirmation()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Reactivated')
                                ->body('The user has been reactivated with their posts.')
                        ),
                    
                    DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Deleted')
                                ->body('The user has been deleted.')
                        ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
