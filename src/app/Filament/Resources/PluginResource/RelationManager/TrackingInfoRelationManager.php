<?php

namespace App\Filament\Resources\PluginResource\RelationManager;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Builder;

class TrackingInfoRelationManager extends RelationManager
{
    protected static string $relationship = 'trackingInfo';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ip')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('url')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('fingerprint')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('client')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }
}
