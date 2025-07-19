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
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Placeholder;
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
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('reportable')
                                            ->label('Content')
                                            ->formatStateUsing(function ($record) {
                                                if (! $record->reportable) return '-';

                                                return match (class_basename($record->reportable_type)) {
                                                    'Post' => $record->reportable->title,
                                                    'CustomComment' => $record->reportable->comment,
                                                    default => 'Unknown',
                                                };
                                            }),
                                        TextEntry::make('content_published')
                                            ->label('Published content date')
                                            ->getStateUsing(function ($record) {
                                                    $related = $record->reportable;
                                                    if (! $related) {
                                                        return null;
                                                    }
                                                    // Intenta usar published_at, si no existe o es null, usa created_at
                                                    $date = $related->published_at ?? $related->created_at;
                                                    return $date?->format('d M, Y H:i:s');
                                                }),
                                    ])
                                    ->extraAttributes(['class' => 'border-b py-2'])
                                    ->columnSpanFull(),
                                TextEntry::make('reason'),
                                TextEntry::make('message'),
                                TextEntry::make('reportable_type')
                                    ->label('Type')
                                    ->formatStateUsing(fn ($state) => [
                                        'Post' => 'Post',
                                        'CustomComment' => 'Comment',
                                    ][class_basename($state)] ?? 'Unknown'),
                                TextEntry::make('reportable_id')
                                    ->label('Reported ID'),
                                TextEntry::make('created_at')
                                    ->label('Time of report')
                                    ->dateTime('d M, Y H:i:s'),
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
