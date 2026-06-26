<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class VendedorLayout extends Component
{
    public function __construct(
        public string $title = 'Vendedor',
        public string $back  = '',
    ) {}

    public function render(): View
    {
        return view('layouts.vendedor');
    }
}
