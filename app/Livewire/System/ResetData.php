<?php

namespace App\Livewire\System;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Expense;
use App\Models\Budget;

class ResetData extends Component
{
    public function resetData()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Expense::truncate();
        Budget::truncate();

        DB::statement('ALTER TABLE expenses AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE budgets AUTO_INCREMENT = 1');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        session()->flash('message', 'Les données ont été réinitialisées avec succès.');
    }

    public function render()
    {
        return view('livewire.system.reset-data')->layout('layouts.app');
    }
}


