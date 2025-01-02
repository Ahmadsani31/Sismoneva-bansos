<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormInputGroup extends Component
{
    /**
     * Create a new component instance.
     */
    public $label;
    public $name;
    public $type;
    public $value;
    public $placeholder;
    public $required;
    public $prepend;
    public $append;

    public function __construct(
        $label,
        $name,
        $type = 'text',
        $value = '',
        $placeholder = '',
        $required = false,
        $prepend = null,
        $append = null
    ) {
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->prepend = $prepend;
        $this->append = $append;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-input-group');
    }
}
