<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataTable extends Component
{
     public $data;
    public $columns;
    public $id;

    public function __construct($data, $columns, $id = null)
    {
        $this->data = $data;
        $this->columns = $columns;
        $this->id = $id ?? 'datatable-' . uniqid();
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
       return view('components.datatable', [
            'data' => $this->data,
            'columns' => $this->columns,
            'id' => $this->id,
        ]);
    }
}
