@if (!\Schema::hasTable((new \App\Model\User)->getTable()))
    <div class="alert alert-danger fade show" role="alert">
        {{ __('You did not run the migrations and seeders! The login information will not be available!') }}
    </div>
@endif