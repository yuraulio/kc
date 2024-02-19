<?php

namespace App\DataTables;

use Illuminate\Support\Facades\URL;
use Yajra\DataTables\Services\DataTable;

abstract class AppDataTable extends DataTable
{
    protected $tableId = 'datatable';

    public function getTableId()
    {
        return $this->tableId;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->parameters([
                'lengthChange' => false,
                'pageLength' => 10,
                'paging' => true,
                'searching' => false,
                'pagingType' => 'simple_numbers',
                'oLanguage' => [
                    'sInfo' => 'Showing page _PAGE_ of _PAGES_',
                    'sSearchPlaceholder' => 'Search...',
                    'sLengthMenu' => 'Results :  _MENU_',
                ],
            ])
            ->setTableId($this->tableId)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->postAjax([
                'url' => $this->getUrl(),
                'data' => "function ( d ) {
                return $.extend( {}, d, getFormData($('.filter_form')) );
          }",
            ])
            ->dom('Bfrtip');
    }

    protected function getUrl()
    {
        return URL::current();
    }
}
