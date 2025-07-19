<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Report;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ReportResource\Pages;

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
                                Grid::make(10)
                                    ->schema([
                                        TextEntry::make('reportable_id')
                                            ->label('ID')
                                            ->columnSpan(1),
                                        TextEntry::make('reportable')
                                            ->label('Content')
                                            ->formatStateUsing(function ($record) {
                                                if (! $record->reportable) return '-';

                                                return match (class_basename($record->reportable_type)) {
                                                    'Post' => $record->reportable->title,
                                                    'CustomComment' => $record->reportable->comment,
                                                    default => 'Unknown',
                                                };
                                            })->columnSpan(4),
                                        TextEntry::make('reason')->columnSpan(2),
                                        TextEntry::make('message')->columnSpan(3),
                                    ])
                                    ->extraAttributes(['class' => 'border-b py-2'])
                                    ->columnSpanFull(),
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('reportable_type')
                                            ->label('Type')
                                            ->formatStateUsing(fn ($state) => [
                                                'Post' => 'Post',
                                                'CustomComment' => 'Comment',
                                            ][class_basename($state)] ?? 'Unknown'),
                                        TextEntry::make('user.username')
                                            ->label('Reported by'),
                                        TextEntry::make('posted_by')
                                            ->label('Posted by')
                                            ->getStateUsing(function ($record) {
                                                $related = $record->reportable;
                                                if (! $related || ! method_exists($related, 'user')) {
                                                    return null;
                                                }
                                                return optional($related->user)->username;
                                            }),
                                        TextEntry::make('created_at')
                                            ->label('Report time')
                                            ->dateTime('d M, Y H:i:s'),
                                        TextEntry::make('content_published')
                                            ->label('Posted on')
                                            ->getStateUsing(function ($record) {
                                                $related = $record->reportable;
                                                if (! $related) {
                                                    return null;
                                                }
                                                // Intenta usar published_at, si no existe o es null, usa created_at
                                                $date = $related->published_at ?? $related->created_at;
                                                return $date?->format('d M, Y H:i:s');
                                        }),
                                    ]),
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
                            'CustomComment' => $record->reportable->comment,
                            default => 'Unknown',
                        };
                    })
                    ->sortable(),
                TextColumn::make('reason')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('reportable_type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => [
                        'Post' => 'Post',
                        'CustomComment' => 'Comment',
                    ][class_basename($state)] ?? 'Unknown')
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('reportable');
    }
}
