<?php

namespace App\Filament\Resources\MemberPostResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $modelLabel = 'Comment';

    protected static ?string $pluralModelLabel = 'Comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('comment')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                TextColumn::make('commentator.slug')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                    // ->url(fn ($record) => route('filament.resources.users.edit', $record->commentator_id)),
                TextColumn::make('comment'),
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

                Tables\Actions\Action::make('approve')
                    ->hidden(fn ($record) => $record->is_approved)
                    ->action(fn ($record) => $record->approve()),

                Tables\Actions\Action::make('disapprove')
                    ->visible(fn ($record) => $record->is_approved)
                    ->action(fn ($record) => $record->disapprove()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
