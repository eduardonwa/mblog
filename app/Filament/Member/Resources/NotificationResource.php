<?php

namespace App\Filament\Member\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Member\Resources\NotificationResource\Pages;

class NotificationResource extends Resource
{
    protected static ?string $model = \Illuminate\Notifications\DatabaseNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationLabel = 'Notifications';

    public static function getModelLabel(): string
    {
        return 'notification';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('data.message')
                    ->label('Message')
                    ->wrap()
                    ->url(fn ($record) => $record->data['url'] ?? null, true) // true = abrir en nueva pestaña
                    ->openUrlInNewTab()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d M, Y')
                    ->label('Date'),
                TextColumn::make('read_at')
                    ->label('Read')
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('markAsRead')
                    ->label('Mark as read')
                    ->visible(fn ($record) => is_null($record->read_at))
                    ->action(fn ($record) => $record->markAsRead()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListNotifications::route('/'),
            // 'create' => Pages\CreateNotification::route('/create'),
            // 'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Auth::user()?->unreadNotifications()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
