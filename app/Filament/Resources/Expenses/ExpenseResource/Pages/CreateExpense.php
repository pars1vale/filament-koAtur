<?php

namespace App\Filament\Resources\Expenses\ExpenseResource\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;
}
