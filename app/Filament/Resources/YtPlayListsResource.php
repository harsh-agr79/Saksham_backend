<?php

namespace App\Filament\Resources;

use App\Filament\Resources\YtPlayListsResource\Pages;
use App\Filament\Resources\YtPlayListsResource\RelationManagers;
use App\Models\ytPlayLists;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class YtPlayListsResource extends Resource
{
    protected static ?string $model = ytPlayLists::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                ->required()
                ->maxLength(255),
            TextInput::make('url')
                ->required()
                ->url()
                ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('url')->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListYtPlayLists::route('/'),
            'create' => Pages\CreateYtPlayLists::route('/create'),
            'edit' => Pages\EditYtPlayLists::route('/{record}/edit'),
        ];
    }
}
