<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CodingChallengeResource\Pages;
use App\Filament\Resources\CodingChallengeResource\RelationManagers;
use App\Models\CodingChallenge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class CodingChallengeResource extends Resource
{
    protected static ?string $model = CodingChallenge::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required()->maxLength(255),
                Textarea::make('description')->required(),
                DateTimePicker::make('startdatetime')->required(),
                DateTimePicker::make('enddatetime')->required(),
                Textarea::make('testcase')->required(),
                Textarea::make('tc_answer')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('startdatetime')->sortable()->dateTime(),
                TextColumn::make('enddatetime')->sortable()->dateTime(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCodingChallenges::route('/'),
            'create' => Pages\CreateCodingChallenge::route('/create'),
            'edit' => Pages\EditCodingChallenge::route('/{record}/edit'),
        ];
    }
}
