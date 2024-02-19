<?php

namespace App\DataTables;

use Illuminate\Support\Facades\URL;
use Yajra\DataTables\Services\DataTable;

abstract class AppDataTable extends DataTable
{
    protected $tableId = 'datatable';

    protected $searching = false;

    protected $lengthChange = false;

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
                'lengthChange' => $this->lengthChange,
                'pageLength' => 10,
                'paging' => true,
                'searching' => $this->searching,
                'pagingType' => 'simple_numbers',
                'oLanguage' => [
                    'sInfo' => 'Showing page _PAGE_ of _PAGES_',
                    'sSearchPlaceholder' => 'Search...',
                    'sLengthMenu' => 'Results :  _MENU_',
                    'oPaginate' => [
                        'sNext' => '&#187;',
                        'sPrevious'  => '&#171;',
                    ],
                ],
                'buttons' => $this->buttons(),
            ])
            ->setTableId($this->tableId)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->selectStyleSingle()
            ->postAjax([
                'url' => $this->getUrl(),
                'data' => "function ( d ) {
                    return $.extend( {}, d, getFormData($('.filter_form')) );
              }",
            ])
            /*
             * l - length changing input control
             * f - filtering input
             * t - The table!
             * i - Table information summary
             * p - pagination control
             * r - processing display element
             * B - buttons
             */
            ->dom("<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'fB>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>");
    }

    protected function buttons()
    {
        return [];
    }

    protected function getUrl()
    {
        return URL::current();
    }
}
