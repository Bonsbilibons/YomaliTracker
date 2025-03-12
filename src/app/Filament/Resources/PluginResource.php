<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PluginResource\Pages;
use App\Filament\Resources\PluginResource\RelationManagers;
use App\Models\Plugin;
use App\Models\TrackingInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PluginResource extends Resource
{
    protected static ?string $model = Plugin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $tabs = [
            Forms\Components\Tabs\Tab::make(__('plugin.tabs.details.label'))->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('host')
                        ->required()
                        ->columnSpan(3),
                    Forms\Components\TimePicker::make('period')
                        ->nullable()
                        ->columnSpan(1),
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('identifier')
                            ->columnSpan(2)
                            ->length(16)
                            ->unique('plugins', 'identifier', ignoreRecord: true)
                            ->hintAction(
                                Action::make('generateSlug')
                                    ->icon('heroicon-m-tag')
                                    ->label(__('plugin.form.identifier.generate'))
                                    ->action(function (Forms\Get $get, Forms\Set $set) {
                                        $identifier = Str::random(16);

                                        $set('identifier', $identifier);
                                        $set('plugin_script', self::getPluginInstallationScript($identifier));
                                    })
                            ),
                        Forms\Components\Textarea::make('plugin_script')
                            ->label(__('plugin.form.identifier.script.label'))
                            ->columnSpan(4)
                            ->formatStateUsing(fn (Forms\Get $get) => self::getPluginInstallationScript($get('identifier')))
                            ->disabled()
                            ->autosize(),
                    ])->columns(6)
                ])->columns(6),
            ])
        ];

        if ($form->getRecord()) {
            array_unshift($tabs,
                Forms\Components\Tabs\Tab::make(__('plugin.tabs.statistics.label'))->schema([
                    Forms\Components\Section::make([
                        Forms\Components\Tabs::make()->schema([
                            Forms\Components\Tabs\Tab::make(__('plugin.tabs.statistics.all'))->schema([
                                Forms\Components\TextInput::make('day_statistics')
                                    ->formatStateUsing(fn($record) =>
                                    self::getTrackingInfoNumberByPeriod(
                                        $record,
                                        Carbon::now()->startOfDay(),
                                        Carbon::now()->endOfDay()
                                    )
                                    )
                                    ->numeric()
                                    ->disabled()
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('week_statistics')
                                    ->formatStateUsing(fn($record) =>
                                    self::getTrackingInfoNumberByPeriod(
                                        $record,
                                        Carbon::now()->startOfWeek(),
                                        Carbon::now()->endOfWeek()
                                    )
                                    )
                                    ->numeric()
                                    ->disabled()
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('month_statistics')
                                    ->formatStateUsing(fn($record) =>
                                    self::getTrackingInfoNumberByPeriod(
                                        $record,
                                        Carbon::now()->startOfMonth(),
                                        Carbon::now()->endOfMonth()
                                    )
                                    )
                                    ->numeric()
                                    ->disabled()
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('all_time_statistics')
                                    ->formatStateUsing(fn($record) =>
                                    self::getTrackingInfoNumberByPeriod(
                                        $record,
                                        Carbon::now()->startOfCentury(),
                                        Carbon::now()->endOfDay()
                                    )
                                    )
                                    ->numeric()
                                    ->disabled()
                                    ->columnSpan(3),
                            ])->columns(6),
                            Forms\Components\Tabs\Tab::make(__('plugin.tabs.statistics.unique'))->schema([
                                Forms\Components\TextInput::make('unique_day_statistics')
                                    ->formatStateUsing(fn($record) =>
                                    self::getTrackingInfoNumberByPeriod(
                                        $record,
                                        Carbon::now()->startOfDay(),
                                        Carbon::now()->endOfDay(),
                                        true
                                    )
                                    )
                                    ->numeric()
                                    ->disabled()
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('unique_week_statistics')
                                    ->formatStateUsing(fn($record) =>
                                    self::getTrackingInfoNumberByPeriod(
                                        $record,
                                        Carbon::now()->startOfWeek(),
                                        Carbon::now()->endOfWeek(),
                                        true
                                    )
                                    )
                                    ->numeric()
                                    ->disabled()
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('unique_month_statistics')
                                    ->formatStateUsing(fn($record) =>
                                    self::getTrackingInfoNumberByPeriod(
                                        $record,
                                        Carbon::now()->startOfMonth(),
                                        Carbon::now()->endOfMonth(),true
                                    )
                                    )
                                    ->numeric()
                                    ->disabled()
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('unique_all_time_statistics')
                                    ->formatStateUsing(fn($record) =>
                                    self::getTrackingInfoNumberByPeriod(
                                        $record,
                                        Carbon::now()->startOfCentury(),
                                        Carbon::now()->endOfDay(),
                                        true
                                    )
                                    )
                                    ->numeric()
                                    ->disabled()
                                    ->columnSpan(3),
                            ])->columns(6)
                        ])->columnSpanFull(),
                    ])->columns(6),
                    Forms\Components\Section::make([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->columnSpan(3),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->columnSpan(3)
                    ])->columns(6),
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('statistics')
                            ->numeric()
                            ->disabled()
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('unique_statistics')
                            ->numeric()
                            ->disabled()
                            ->columnSpan(3),
                        Forms\Components\Actions\ActionContainer::make(
                            Forms\Components\Actions\Action::make('get_statistic')
                                ->label(__('plugin.form.identifier.get_statistic'))
                                ->action(function (Forms\Get $get, Forms\Set $set) use ($form) {
                                    $set(
                                        'statistics',
                                        self::getTrackingInfoNumberByPeriod(
                                            $form->getRecord(),
                                            $get('start_date') ?? Carbon::now()->startOfDay(),
                                            $get('end_date') ?? Carbon::now()->endOfDay(),
                                        )
                                    );
                                    $set(
                                        'unique_statistics',
                                        self::getTrackingInfoNumberByPeriod(
                                            $form->getRecord(),
                                            $get('start_date') ?? Carbon::now()->startOfDay(),
                                            $get('end_date') ?? Carbon::now()->endOfDay(),
                                            true
                                        )
                                    );
                                })
                        )->columnSpan(2)
                    ])->columns(6)
                ])->columns(6),
            );
        }

        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->tabs($tabs)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('plugin.table.name.label'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('host')
                    ->label(__('plugin.table.host.label'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tracking_info_count')
                    ->label(__('plugin.table.total_visits.label'))
                    ->sortable()
                    ->counts('trackingInfo'),
                Tables\Columns\TextColumn::make('unique_tracking_info_count')
                    ->label(__('plugin.table.unique_visits.label'))
                    ->getStateUsing(fn($record) =>
                        $record->trackingInfo()->distinct('fingerprint')->count()
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('plugin.table.created_at.label'))
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            \App\Filament\Resources\PluginResource\RelationManager\TrackingInfoRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlugins::route('/'),
            'create' => Pages\CreatePlugin::route('/create'),
            'edit' => Pages\EditPlugin::route('/{record}/edit'),
        ];
    }

    private static function getPluginInstallationScript(string|null $identifier): string
    {
        return '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.1.4/fingerprint2.min.js"></script>
<script src="'. config('app.url') .'/js/tracker.js" id="yomali-tracker" x-identifier="' . $identifier .'"></script>';
    }

    private static function getTrackingInfoNumberByPeriod(Model $plugin, string $startDate, string $endDate, bool $distinct = false): int
    {
        return $plugin->trackingInfo()
            ->whereBetween('created_at', [Carbon::parse($startDate), Carbon::parse($endDate)])
            ->when($distinct, fn(Builder $query) => $query->distinct('fingerprint'))
            ->count();
    }
}
