<?php

namespace App\Filament\Member\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Member\Resources\PostResource\Pages;
use App\Filament\Member\Resources\PostResource\RelationManagers;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        Tabs::make('Tabs')
                            ->tabs([
                                Tab::make('Info')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('title')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                                    ->maxLength(255)
                                                    ->columnStart(1)
                                                    ->required(),
                                                Select::make('channel_id')
                                                    ->relationship('channel', 'name')
                                                    ->columnStart(2),
                                                TextInput::make('slug')
                                                    ->label('Link')
                                                    ->maxLength(255)
                                                    ->columnStart(1)
                                                    ->required(),
                                                Select::make('language')
                                                    ->options(config('languages'))
                                                    ->searchable()
                                                    ->helperText('Optional field'),
                                                Hidden::make('user_id')
                                                    ->default(Auth::id()),
                                            ]),
                                    ]),
                                Tab::make('Publish')
                                    ->schema([
                                        Toggle::make('status_toggle') // Campo temporal NO guardado en BD
                                            ->label('Publish now')
                                            ->default(true)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, $state) {
                                                // Convertir el estado del toggle a los valores string
                                                $set('status', $state ? 'published' : 'draft');
                                                // Manejar la fecha automáticamente
                                                $set('published_at', $state ? now() : null);
                                            })
                                            ->dehydrated(false), // No guardar este campo en la BD
                                        // Mantén tu campo status real como Hidden
                                        Hidden::make('status')
                                            ->default('published')
                                    ]),
                            ]),
                        ]),
                Grid::make(1)
                    ->schema([
                        TipTapEditor::make('body')
                            ->profile('simple')
                            ->extraInputAttributes(['style' => 'min-height: 50vh;'])
                            ->columnSpan(1)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('channel.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('likes_count')
                    ->label('Uphails')
                    ->sortable(),
                TextColumn::make('comments_count')
                    ->label('Comments')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->withCount(['likes', 'comments']);
    }
}
