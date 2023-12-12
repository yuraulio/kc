@extends('layouts.masterv2')

@section('content')

<style type="text/css">
    .chosen-select.cs-full + .chosen-container { max-width: 300px !important; width: 100% !important; }
</style>

<div id="content_list_cont">
	<!-- <a href="/admin1/transaction/create">New</a> -->
    <div class="dp_filters_cont">


    <select id ="pag"  class="selectpicker" data-width="fit" onchange="filter()" name="pagination" data-dp-filter-scope="transactions" data-dp-filter="take">
            @foreach ($limit_arr as $take_limit)
            <option value="{!! $take_limit !!}" <?php if ($take_limit == $filters['take']) { echo 'selected="selected"'; }?>>{{ $take_limit }}</option>
            @endforeach
        </select>

        <select id ="trans" class="selectpicker" onchange="filter()" data-width="fit" name="transaction" data-dp-filter-scope="transactions" data-dp-filter="status">
            <option value="any" <?php if ("any" == $filters['status']) { echo 'selected="selected"'; }?>>Select Status</option>
            @foreach ($transaction as $sts => $name)
            <option value="{!! $sts !!}" <?php if ($sts === $filters['status']) { echo 'selected="selected"'; }?>>{{ $name }}</option>
            @endforeach
        </select>

        <select id="myEvent" onchange="filter()" class="chosen-select cs-full" data-width="fit" name="event" data-dp-filter-scope="transactions" data-dp-filter="group_by_event">
            <option value="any" <?php if ("any" == $filters['group_by_event']) { echo 'selected="selected"'; }?>>Select Event</option>
            @foreach ($events as $ttype => $event)
            <option value="{{ $event->id }}" <?php if ($ttype === $filters['group_by_event']) { echo 'selected="selected"'; }?>>{{ $event->title }}</option>
            @endforeach
        </select>



        <input id="searchTerm" type="text" class="filter_search custom_filter" data-dp-filter-scope="transactions" data-dp-filter="search_term" value="{{ $filters['search_term'] }}" placeholder="search (2 chars or more)" />
    </div>

    <div class="content_list_tbl_cont">
    	<h3 id="eventselected" style="text-align:left; margin-top: -10px;"></h3>
        <div id="eventstats" class="event-stats" style="text-align:left; margin-top: 10px;"></div>
        <table id="transaction_list" class="results shadow-z-1 content_list" data-dp-filter-scope="transactions" data-dp-filter-page="1">
            <thead>
                <tr>
                   <!--  <td><input type="checkbox" class="chk_all ignore_cbox" name="chk_all" value="all" /></td> -->
                    <td>ID</td>
                    <td class="th_author">Student</td>
                    <!-- <td class="th_author">Tx Details</td> -->
                    <td class="th_title">Event</td>

                    <td class="th_title">Total Installments</td>

                    <td class="th_title">Remaing Installments</td>

                    <td class="th_title">Next Payment</td>



                </tr>
            </thead>
            <tbody id="invoice-body">
                @foreach($invoices as $invoice)
                    <tr>
                        <td >{{$invoice->id}}</td>
                        <td >{{$invoice->name}}</td>
                        <td >{{$invoice->event->title}}</td>
                        <td >{{$invoice->instalments}}</td>
                        <td >{{$invoice->instalments_remaining}}</td>
                        <td >{{$invoice->date}}</td>

                        <td ><a href="admin/elearnigInvoice/{{$invoice->id}}">Download</a</td>
                    </tr>
                @endforeach

        </tbody>

        </table>
    </div>

    <div id="filter_paginator" data-dp-filter-scope="transactions">{{$invoices->links()}}</div>
</div>


<script>

var invoices;

function filter(page=0){

    var filterData = {};

    filterData['event'] = document.getElementById("myEvent").value;
    filterData['pagination'] = document.getElementById("pag").value;
    filterData['transaction'] = document.getElementById("trans").value;
    filterData['search_term'] = document.getElementById("searchTerm").value;
    filterData['page'] = (page > 0 ? page : 1);
    filterData = JSON.stringify(filterData);


    NProgress.configure({ parent: '#dp-header-progress' });
    NProgress.start();
    $.ajax({ url: "admin/elearnigInvoice/filter", type: "post",
        data: {'filters':filterData},
        success: function(data) {

            if(data == 'false'){

                filterScopeTransactionNoResults()

            }else{
                data = JSON.parse(data)
                tbody(data,1)
            }


            NProgress.done();
        }
    })


}


function filterScopeTransactionNoResults() {
    $("#filter_paginator").empty();
    var html = '<tr>';
    html += '<td colspan="9">Nothing found!</td>';
    html += '</tr>';
    $("#invoice-body").html(html);
}

function tbody(data = false){

    if(data){
        this.invoices = data;
    }
    $("#invoice-body").empty();

    html = ''
    $.each(this.invoices['data'], function (idx, row) {

        html +=
        `<tr>`

        + `<td >` + row.id + ` </td>`

        + `<td >` + row.name + ` </td>`

        + `<td >` + row.title + ` </td>`

        + `<td >` + row.installments + ` </td>`

        + `<td >` + row.installmentsRemaining + ` </td>`

        + `<td >` + row.nextPayment + ` </td>`

        + `<td ><a href="admin/elearnigInvoice/` + row.id + `">Download</a> </td>`

        + `</tr>`

    })

    document.getElementById('invoice-body').innerHTML = html
    html = ''

    $("#filter_paginator").empty();
    document.getElementById('filter_paginator').innerHTML =this.invoices['links']

}


$("body").on("click",  "#filter_paginator a", function (event) {


        event.preventDefault();
        var pagi_page = $(this).attr("href").split("?page=");
        var page = Number(pagi_page[1]);

        $("#transaction_list").attr('data-dp-filter-page', page);
        filter(page)

    });


    $('#searchTerm').keyup(function(){

        filter()

    })

</script>


@endsection
