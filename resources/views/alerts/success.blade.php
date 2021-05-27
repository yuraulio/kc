@if (session($key ?? 'status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session($key ?? 'status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div id="success-message" class="alert alert-success alert-dismissible success-message" style="display:none;" role="alert">
        <p> </p>
        <button type="button" class="close-message close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
</div>


@push('js')
    <script>
        $(document).on('click','.close-message',function(){
            $("#success-message").hide();
        })
    </script>
@endpush