<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Channel;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ChannelResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\ChannelResource\RelationManagers;

class ChannelResource extends Resource
{
    protected static ?string $model = Channel::class;

    protected static ?string $navigationIcon = 'heroicon-o-wifi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    
                    Grid::make()->schema([
                        TextInput::make('name')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('slug')->required(),
                        TextArea::make('description')
                            ->maxLength(255)
                            ->columnSpan(2)
                            ->extraInputAttributes(['style' => 'min-height: 10vh;'])
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                    ])->columnSpan(1),

                    Grid::make()->schema([
                        SpatieMediaLibraryFileUpload::make('channel_sticker')
                            ->collection('channel_sticker')
                            ->label('Sticker')
                            ->image()
                            ->rules([
                                'image',
                                'mimes:png,jpg,jpeg,webp',
                                'max:2048'
                            ])
                            ->columnSpan(2)
                            ->required(),
                    ])->columnSpan(1),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('channel_sticker')
                    ->collection('channel_sticker')
                    ->size(60),
                TextColumn::make('name'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d M, Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->dateTime('d M, Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListChannels::route('/'),
            'create' => Pages\CreateChannel::route('/create'),
            'edit' => Pages\EditChannel::route('/{record}/edit'),
        ];
    }
}
