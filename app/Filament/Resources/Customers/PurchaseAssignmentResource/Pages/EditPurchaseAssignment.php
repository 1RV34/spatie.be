<?php

namespace App\Filament\Resources\Customers\PurchaseAssignmentResource\Pages;

use App\Filament\Resources\Customers\PurchaseAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseAssignment extends EditRecord
{
    protected static string $resource = PurchaseAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
