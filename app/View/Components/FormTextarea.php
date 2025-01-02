<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormTextarea extends Component
{
    /**
     * Create a new component instance.
     */
    public $label;
    public $name;
    public $value;
    public $placeholder;
    public $required;
    public $rows;

    public function __construct(
        $label,
        $name,
        $value = '',
        $placeholder = '',
        $required = false,
        $rows = 3
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->rows = $rows;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-textarea');
    }
}
