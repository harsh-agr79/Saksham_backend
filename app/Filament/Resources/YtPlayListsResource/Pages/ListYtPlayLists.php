<?php

namespace App\Filament\Resources\YtPlayListsResource\Pages;

use App\Filament\Resources\YtPlayListsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListYtPlayLists extends ListRecords
{
    protected static string $resource = YtPlayListsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
