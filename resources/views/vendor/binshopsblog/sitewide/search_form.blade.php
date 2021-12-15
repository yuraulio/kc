<div style=';margin:30px auto;' class='search-form-outer'>
    <form method='get' action='{{route("binshopsblog.search", app('request')->get('locale'))}}' class='text-center'>
        <div class="form__group field">
            <input type="input" class="form__field" placeholder="Search Our Blog ..." name="s" id='name' value="{{ \Request::get('s') }}"/>
          </div>
    </form>
</div>
