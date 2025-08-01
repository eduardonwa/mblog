<?php

namespace App\Filament\Resources;

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
use Filament\Forms\Components\Section;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PostResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Posts';

    protected static ?string $navigationLabel = 'S.O.M.';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        Section::make("Content")
                            ->schema([
                                Radio::make('post_template')
                                    ->options([
                                        'story' => 'Story',
                                        'standard' => 'Standard',
                                    ])->default('standard'),
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
                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->columnStart(1)
                                            ->required(),
                                        Toggle::make('featured'),
                                        TextArea::make('extract')
                                            ->helperText('Limited to 255 characters')
                                            ->extraInputAttributes(['style' => 'min-height: 10vh;'])
                                            ->maxLength(255),
                                        Select::make('language')
                                            ->options(config('languages'))
                                            ->searchable(),
                                        Hidden::make('user_id')
                                            ->default(Auth::id()),
                                    ]),
                                Tab::make('Meta')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('thumbnail')
                                            ->collection('thumbnails')
                                            ->columnSpanFull()
                                            ->image()
                                            ->maxSize(5000)
                                            ->rules([
                                                'image',
                                                'mimes:png,jpg,jpeg,webp',
                                                'max:5000'
                                            ])
                                            ->required(),
                                        TextInput::make('meta_title')
                                            ->required(),
                                        TextArea::make('meta_description')
                                            ->extraInputAttributes(['style' => 'min-height: 10vh;'])
                                            ->required(), 
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
                                Tab::make('Metrics')
                                    ->schema([
                                    TextInput::make('views')
                                        ->disabled(),
                                    TextInput::make('likes_count')
                                        ->label('Uphails')
                                        ->disabled(),
                                    TextInput::make('comments_count')
                                        ->label('Comments')
                                        ->disabled(),
                                    ]),
                            ]),
                        ])->columnSpan([
                            'default' => 1,
                            'sm' => 12,
                            'md' => 8,
                            'lg' => 4,
                    ])
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->collection('thumbnails')
                    ->size(60),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.username')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('status')
                    ->boolean()
                    ->searchable()
                    ->sortable(),
                IconColumn::make('featured')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user_role')
                    ->options([
                        'admin' => 'Admin',
                        'staff' => 'Staff',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('user', function($q) use ($data) {
                                $q->role($data['value']);
                            });
                        }
                    }),
                SelectFilter::make('featured')
                    ->options([
                        '1' => 'Featured',
                        '0' => 'Non-featured',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    protected function beforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            // obtener posts por usuario autenticado
            ->where(function($query) {
                $query->whereNotNull('user_id')
                    ->orWhereNotNull('original_user_id');
            })
            // obtener posts por rol (admin y staff)
            ->whereHas('user', function($q) {
                $q->whereHas('roles', function($r) { 
                    $r->where('name', 'admin')
                        ->orWhere('name', 'staff');
                });
            })->withCount('comments');
    }
}