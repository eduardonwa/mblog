<?php

namespace App\Filament\Resources;

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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PostResource\Pages;
use Filament\Forms\Components\SpatieTagsInput;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
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
                                            ->helperText('Limited to 255 characters allowed')
                                            ->maxLength(255),
                                        Select::make('language')
                                            ->options(config('languages'))
                                            ->searchable(),
                                        SpatieTagsInput::make('tags')
                                            ->separator(','),
                                        Hidden::make('author_id')
                                            ->default(Auth::id()),
                                    ]),
                                Tab::make('Meta')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('thumbnail')
                                            ->collection('thumbnails')
                                            ->columnSpanFull()
                                            ->image()
                                            ->maxSize(3000)
                                            ->required(),
                                        TextInput::make('meta_title')
                                            ->required(),
                                        TextArea::make('meta_description')
                                            ->required(), 
                                    ]),
                                Tab::make('Publish')
                                    ->schema([
                                        DateTimePicker::make('created_at'),
                                        Radio::make('status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Published',
                                                //->disableOptionWhen(fn (string $value): bool => $value === 'published')
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
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->collection('thumbnails')
                    ->size(60),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('featured')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    protected function beforeCreate(array $data): array
    {
        $data['author_id'] = Auth::id();
        return $data;
    }
}
