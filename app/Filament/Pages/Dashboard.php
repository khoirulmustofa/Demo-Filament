<?php

namespace App\Filament\Pages;

use App\Models\Shop\Order;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('businessCustomersOnly')
                            ->boolean(),
                        DatePicker::make('startDate')
                            ->minDate(Order::min('created_at')) // Mengambil tanggal paling awal
                            ->maxDate(fn(Get $get) => $get('endDate') ?: now()), // Maksimal hingga endDate atau hari ini

                        DatePicker::make('endDate')
                            ->minDate(fn(Get $get) => $get('startDate') ?: now())
                            ->maxDate(now()),
                    ])
                    ->columns(3),
            ]);
    }
}
