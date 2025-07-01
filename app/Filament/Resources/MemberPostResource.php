<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
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
use Filament\Forms\Components\Section;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\SpatieTagsInput;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MemberPostResource\Pages;
use App\Filament\Resources\MemberPostResource\RelationManagers;
use App\Filament\Resources\MemberPostResource\RelationManagers\CommentsRelationManager;

class MemberPostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-2-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        Section::make("Content")
                            ->schema([
                                TextInput::make('title')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->maxLength(255)
                                    ->columnStart(1)
                                    ->required(),
                                TipTapEditor::make('body')
                                    ->profile('simple')
                                    ->extraInputAttributes(['style' => 'min-height: 50vh;'])
                                    ->required(),
                            ]),
                    ])->columnSpan([
                        'default' => 1,
                        'sm' => 12,
                        'md' => 8,
                    ]),

                Grid::make(1)
                    ->schema([
                        Tabs::make('Tabs')
                            ->tabs([
                                Tab::make('Info')
                                    ->schema([
                                        TextInput::make('slug')
                                            ->maxLength(255)
                                            ->columnStart(1)
                                            ->required(),
                                        Select::make('channel_id')
                                            ->relationship('channel', 'name')
                                            ->columnStart(1)
                                            ->required(),
                                        TextArea::make('extract')
                                            ->maxLength(25)
                                            ->extraInputAttributes(['style' => 'min-height: 10vh;']),
                                        Select::make('language')
                                            ->options(config('languages'))
                                            ->searchable(),
                                        SpatieTagsInput::make('tags')
                                            ->separator(','),
                                        Hidden::make('user_id')
                                            ->default(Auth::id()),
                                    ]),
                                Tab::make('Meta')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->required(),
                                        TextArea::make('meta_description')
                                            ->extraInputAttributes(['style' => 'min-height: 10vh;'])
                                            ->required(), 
                                    ]),
                                Tab::make('Publish')
                                    ->schema([
                                        DateTimePicker::make('created_at')
                                            ->label('Publish date'),
                                        Radio::make('status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Published',
                                            ]),
                                    ]),
                            ]),
                        ])->columnSpan([
                            'default' => 1,
                            'sm' => 12,
                            'md' => 8,
                            'lg' => 4,
                    ])
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title'),
                TextColumn::make('created_at')
                    ->dateTime('d M, Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('d M, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published'
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberPosts::route('/'),
            'create' => Pages\CreateMemberPost::route('/create'),
            'edit' => Pages\EditMemberPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Members';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('user', function($q) {
                $q->role(['member']);
            });
    }
}
