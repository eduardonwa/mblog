<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Report;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\Placeholder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ReportResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationGroup = 'Moderation';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(2)
                    ->schema([
                        Section::make('Report information')
                            ->schema([
                                TextEntry::make('reportable')
                                    ->label('Content')
                                    ->formatStateUsing(function ($record) {
                                        if (! $record->reportable) return '-';

                                        return match (class_basename($record->reportable_type)) {
                                            'Post' => $record->reportable->title,
                                            'Comment' => Str::limit($record->reportable->body, 50),
                                            default => 'Unknown',
                                        };
                                    })
                                    ->columnSpanFull()
                                    ->extraAttributes(['class' => 'border-b py-4']),
                                TextEntry::make('reason'),
                                TextEntry::make('message'),
                                TextEntry::make('reportable_type')
                                    ->label('Type')
                                    ->formatStateUsing(fn ($state) => class_basename($state)),
                                TextEntry::make('reportable_id')
                                    ->label('Reported ID'),
                            ])->columns([
                                'sm' => 2,
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                static::getEloquentQuery()->with(['user', 'reportable']) // 👈 aquí cargas la relación
            )
            ->columns([
                TextColumn::make('reportable')
                    ->label('Content')
                    ->formatStateUsing(function ($record) {
                        if (! $record->reportable) return '-';

                        return match (class_basename($record->reportable_type)) {
                            'Post' => $record->reportable->title,
                            'Comment' => Str::limit($record->reportable->body, 50),
                            default => 'Unknown',
                        };
                    }),
                TextColumn::make('reason')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('message')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListReports::route('/'),
        ];
    }

    public function openGuidelinesModal()
    {
        $this->dispatch('open-modal', id: 'guidelines');
    }
}
