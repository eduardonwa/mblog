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
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use FilamentTiptapEditor\Enums\TiptapOutput;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Member\Resources\PostResource\Pages;
use Filament\Notifications\Notification;

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
                                                    ->columnStart(2)
                                                    ->required()
                                                    ->reactive()
                                                    ->options(function (Get $get) {
                                                        if ($get('post_template') === 'lists') {
                                                            $listChannel = \App\Models\Channel::where('name', 'lists')->first();
                                                            return $listChannel ? [$listChannel->id => $listChannel->name] : [];
                                                        }
                                                        return \App\Models\Channel::where('name', '!=', 'lists')->pluck('name', 'id')->toArray();
                                                    })
                                                    ->extraAttributes(fn (Get $get) =>
                                                        $get('post_template') === 'lists'
                                                            ? ['class' => 'select-locked']
                                                            : []
                                                    )
                                                    ->default(function (Get $get) {
                                                        if ($get('post_template') === 'lists') {
                                                            $listChannel = \App\Models\Channel::where('name', 'lists')->first();
                                                            return $listChannel?->id;
                                                        }
                                                        return null;
                                                    }),
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
                        'lists' => 'Lists',
                    ])
                    ->default('post')
                    ->reactive()
                    ->afterStateHydrated(function (callable $set, $state) {
                        if (blank($state)) { // opcion default
                            $set('post_template', 'post');
                        }
                        if ($state === 'lists') { // busca el channel "lists" si se elige como opción
                            $listChannel = \App\Models\Channel::where('name', 'lists')->first();
                            $set('channel_id', $listChannel?->id);
                        }
                    })
                    ->afterStateUpdated(function (callable $set, $state) {
                        // se busca y se asigna "lists" a channel
                        if ($state === 'lists') {
                            $listChannel = \App\Models\Channel::where('name', 'lists')->first();
                            $set('channel_id', $listChannel?->id);
                            $set('body', null); // limpia el contenido de "body"
                            Notification::make()
                                ->title('Template changed to "Lists"')
                                ->body('The post text will be cleared and you\'ll only be able to edit the list fields. If this was a mistake and the post was already created, you can reload the page before saving to recover your original text.')
                                ->warning()
                                ->persistent()
                                ->send();
                        } else {
                            // si se elige "post", desde "lists", se limpia el contenido de "lists"
                            $set('list_data_json', [
                                'intro' => null,
                                'items' => [],
                                'outro' => null,
                            ]);
                            Notification::make()
                                ->title('Template changed to "Post"')
                                ->body('All list items will be removed. If this was a mistake and the post was already created, you can reload the page before saving to recover your list progress.')
                                ->warning()
                                ->persistent()
                                ->send();
                        }
                    }),
                    Grid::make(1)
                        ->schema([
                            Textarea::make('intro')
                                ->label('Introduction')
                                ->statePath('list_data_json.intro')
                                ->rows(4)
                                ->visible(fn (Get $get) => $get('post_template') === 'lists'),

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
                                                ->profile('lists')
                                                ->output(TiptapOutput::Json)
                                                ->columnSpan(1)
                                                ->floatingMenuTools([
                                                    'link', 'oembed',
                                                ])
                                                ->bubbleMenuTools(['link', 'oembed'])
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
                                ->visible(fn (Get $get) => $get('post_template') === 'lists'),

                            Textarea::make('outro')
                                ->label('Outro')
                                ->statePath('list_data_json.outro')
                                ->rows(4)
                                ->visible(fn (Get $get) => $get('post_template') === 'lists'),
                        ]),
                Grid::make(1)
                    ->schema([
                        TipTapEditor::make('body')
                            ->profile('simple')
                            ->floatingMenuTools(['link', 'oembed'])
                            ->extraInputAttributes(['style' => 'min-height: 50vh;'])
                            ->columnSpan(1)
                            ->required(fn (Get $get) => $get('post_template') === 'post')
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
                TextColumn::make('likes_count')
                    ->label('Uphails')
                    ->sortable(),
                TextColumn::make('views')
                    ->sortable()
                    ->toggleable(),
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
