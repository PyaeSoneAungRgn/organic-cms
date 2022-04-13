<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget\Card;

class DemoInformation extends Widget
{
    protected static string $view = 'filament.widgets.demo-information';

    protected int | string | array $columnSpan = 2;

    protected function getCards(): array
    {
        return [
            Card::make('Unique views', '192.1k'),
            Card::make('Bounce rate', '21%'),
            Card::make('Average time on page', '3:12'),
        ];
    }
}
