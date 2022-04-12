<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $total = 0;
        foreach ($data['order_products'] as $item) {
            $total += $item['unit_price'] * $item['quantity'];
        }

        $data['total_price'] = $total;

        return $data;
    }
}
