<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public static function getModelLabel(): string
    {
        return 'Comment';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextArea::make('comment')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                TextColumn::make('comment'),
                TextColumn::make('commentator.username')
                    ->label('Commentator')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-arrow-uturn-up')
                    ->action(fn ($record) => $record->approve())
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => $record->is_approved),
                Action::make('disapprove')
                    ->label('Disapprove')
                    ->color('warning')
                    ->icon('heroicon-o-arrow-uturn-down')
                    ->action(fn ($record) => $record->disapprove())
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => !$record->is_approved),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
