<?php

namespace App\Filament\Resources\PlanejamentoGinecologicoResource\Pages;

use App\Filament\Resources\PlanejamentoGinecologicoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanejamentoGinecologicos extends ListRecords
{
    protected static string $resource = PlanejamentoGinecologicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
