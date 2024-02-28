<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

abstract class BaseGridController extends Controller
{
    protected $datatable;
    protected $views;
    protected $authorize;

    public function __invoke()
    {
        if ($this->authorize) {
            $this->authorize('view', $this->authorize);
        }

        $datatable = app($this->datatable);

        $indexView = $this->views['index'];

        return $datatable->render($indexView);
    }
}
