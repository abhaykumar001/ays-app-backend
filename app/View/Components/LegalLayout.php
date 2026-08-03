<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class LegalLayout extends Component
{
    public function __construct(
        public string $title,
        public string $lastUpdated,
    ) {}

    public function render(): View
    {
        return view('dashboard.layouts.legal');
    }
}
