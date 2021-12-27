<div style='' class='search-form-outer maralign marbot'>
    <form method='get' action='{{route("binshopsblog.search", app('request')->get('locale'))}}' class='text-center'>
        <div class="form__group field">
            <input type="input" class="form__field" placeholder="Search our blog ..." name="s" id='name' value="{{ \Request::get('s') }}"/>
          </div>
    </form>
</div>
