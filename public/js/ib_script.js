window.setTimeout(function() {
    CKEDITOR.replace('editor1')
    CKEDITOR.replace('examIntro')
    CKEDITOR.replace('examEndText')
	CKEDITOR.replace('examSuccess')
	CKEDITOR.replace('examFailure')	
}, 1000);

/*
$(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#examList thead tr').clone(true).appendTo( '#examList thead' );
     $('#examList thead tr:eq(1) th').each( function (i) {
         var title = $(this).text(); 
         $(this).html( '<input type="text" placeholder="Filter By '+title+'" />' );
 
         $( 'input', this ).on( 'keyup change', function () {
             if ( table.column(i).search() !== this.value ) {
                 table
                     .column(i)
                     .search( this.value )
                     .draw();
             }
         } );
     } );
 
     var table = $('#examList').DataTable( {
         orderCellsTop: true,
         fixedHeader: true
     } );
 } );
*/

$('#resultTable').DataTable({
	'paging'      : true,
	'lengthChange': false,
	'searching'   : true,
	'ordering'    : true,
	'info'        : true,
    'autoWidth'   : false,
    'pageLength'  : 50
  })

  $('#liveResultTable').DataTable({
	'paging'      : true,
	'lengthChange': false,
	'searching'   : true,
	'ordering'    : true,
	'info'        : true,
    'autoWidth'   : false,
    'pageLength'  : 50
  })

/*let example = $('#examList').DataTable({
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
    }],
    select: {
        style: 'os',
        selector: 'td:first-child'
    },
    order: [
        [1, 'asc']
    ]
});
example.on("click", "th.select-checkbox", function() {
    if ($("th.select-checkbox").hasClass("selected")) {
        example.rows().deselect();
        $("th.select-checkbox").removeClass("selected");
    } else {
        example.rows().select();
        $("th.select-checkbox").addClass("selected");
    }
}).on("select deselect", function() {
    ("Some selection or deselection going on")
    if (example.rows({
            selected: true
        }).count() !== example.rows().count()) {
        $("th.select-checkbox").removeClass("selected");
    } else {
        $("th.select-checkbox").addClass("selected");
    }
});*/
 



$('#quistionList').DataTable({
    'paging'      : true,
    'lengthChange': true,
    'searching'   : true,
    'ordering'    : true,
    'info'        : true,
    'autoWidth'   : false
})

$('#quistionType').change(function(){
    $('.answer-block').hide();
    $('#' + $(this).val()).show();
});

$('#save-add').on('click', function() {
    var furl = $('#question-form').attr('action');
    var nurl = furl.replace("excontent_store", "save-add");
    $('#question-form').attr('action', nurl);
});
window.setTimeout(function() {
    $(".succ_message").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
    $(".err_message").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);