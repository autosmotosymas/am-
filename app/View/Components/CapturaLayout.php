<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CapturaLayout extends Component
{
    public function __construct(
        public string $title = 'Captura',
        public string $back = '',
    ) {}

    public function render(): View
    {
        return view('layouts.captura');
    }
}
