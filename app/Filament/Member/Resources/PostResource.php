<?php

namespace App\Filament\Member\Resources;

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
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use App\TiptapExtensions\BandcampIframe;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Member\Resources\PostResource\Pages;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        Placeholder::make('')
                            ->content('Create a draft or publish it today. To preview a post, you first need to save it to your library.')
                            ->columnSpanFull(),
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
                                        Radio::make('status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'scheduled' => 'Scheduled',
                                                'published' => 'Published',
                                            ])
                                            ->descriptions([
                                                'draft' => 'Is not visible.',
                                                'published' => 'Is visible.',
                                                'scheduled' => 'Will be visible.',
                                            ])
                                            ->default('draft')
                                            ->reactive()
                                            ->required()
                                            ->afterStateUpdated(function (Set $set, $state) {
                                                if ($state === 'published') {
                                                    $set('published_at', now());
                                                } elseif ($state === 'draft') {
                                                    $set('published_at', null);
                                                } elseif ($state === 'scheduled') {
                                                    $set('published_at', null);
                                                }
                                            }),
                                        DateTimePicker::make('published_at')
                                            ->label('Schedule for later')
                                            ->visible(fn (Get $get) => $get('status') === 'scheduled')
                                            ->required(fn (Get $get) => $get('status') === 'scheduled')
                                            ->minDate(now()->addMinutes(5))
                                            ->helperText('Select the date and time to publish this post.'),
                                    ]),
                            ]),
                        ]),
                Placeholder::make('body_description')
                    ->view('filament.member.guidelines')
                    ->columnSpanFull(),  
                Radio::make('post_template')
                    ->label('Template')
                    ->options([
                        'post' => 'Post',
                        'list' => 'List',
                    ])
                    ->default('post')
                    ->reactive()
                    ->afterStateHydrated(function (Set $set, $state) {
                        if (blank($state)) {
                            $set('post_template', 'post');
                        }
                    }),
                    Grid::make(1)
                        ->schema([
                            Textarea::make('intro')
                                ->label('Introduction')
                                ->statePath('list_data_json.intro')
                                ->rows(4)
                                ->visible(fn (Get $get) => $get('post_template') === 'list'),

                            Repeater::make('items')
                                ->label('Songs')
                                ->hint('You may post up to 20 songs, with a minimum of 3.')
                                ->hintColor('primary')
                                ->statePath('list_data_json.items')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TiptapEditor::make('resource')
                                                ->label('Resource (URL)')
                                                ->hint('One resource per slot')
                                                ->profile('list')
                                                ->extraInputAttributes(fn (Component $component) => [
                                                    // obtiene el ID por cada repeater
                                                    'data-state-path' => str_replace('.', '_', $component->getStatePath()),
                                                    'style' => 'min-height: 25vh;',
                                                ])
                                                ->columnSpan(1)
                                                ->floatingMenuTools([
                                                    'oembed',
                                                    'bandcampIframe'
                                                ])
                                                ->bubbleMenuTools(['link'])
                                                ->required(),
                                            Grid::make(1)
                                                ->columnStart(2)
                                                ->schema([
                                                    TextInput::make('title')
                                                        ->required(),
                                                    Textarea::make('description')
                                                        ->rows(5)
                                                        ->required(),
                                                ]),
                                        ]),
                                    ])
                                ->required()
                                ->minItems(3)
                                ->maxItems(20)
                                ->addActionLabel('Add song')
                                ->visible(fn (Get $get) => $get('post_template') === 'list'),

                            Textarea::make('outro')
                                ->label('Outro')
                                ->statePath('list_data_json.outro')
                                ->rows(4)
                                ->visible(fn (Get $get) => $get('post_template') === 'list'),
                        ]),
                Grid::make(1)
                    ->schema([
                        TipTapEditor::make('body')
                            ->profile('simple')
                            ->extraInputAttributes(['style' => 'min-height: 50vh;'])
                            ->columnSpan(1)
                            ->required()
                            ->visible(fn (Get $get) => $get('post_template') === 'post'),
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
