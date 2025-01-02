<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormSelect extends Component
{
    /**
     * Create a new component instance.
     */
    public $label;
    public $name;
    public $options;
    public $selected;
    public $placeholder;
    public $required;

    public function __construct(
        $label,
        $name,
        $options = [],
        $selected = null,
        $placeholder = 'Select an option',
        $required = false
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->options = $options;
        $this->selected = $selected;
        $this->placeholder = $placeholder;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-select');
    }
}
