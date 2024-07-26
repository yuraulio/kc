@extends('layouts.app', [
    'title' => __('Skill Management'),
    'parentSection' => 'laravel',
    'elementName' => 'skill-management'
])

@section('content')
  @component('layouts.headers.auth')
    @component('layouts.headers.breadcrumbs')
      @slot('title')
        {{ __('') }}
      @endslot

      <li class="breadcrumb-item"><a href="{{ route('skill.index') }}">{{ __('Skill Management') }}</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ __('List') }}</li>
    @endcomponent
  @endcomponent

  <div class="container-fluid mt--6">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h3 class="mb-0">{{ __('Skills') }}</h3>
              </div>
              @can('create', App\Model\User::class)
                <div class="col-4 text-right">
                  <a href="{{ route('skill.create') }}" class="btn btn-sm btn-primary">{{ __('Add Skill') }}</a>
                </div>
              @endcan
            </div>
          </div>

          <div class="col-12 mt-2">
            @include('alerts.success')
            @include('alerts.errors')
          </div>

          <div class="table-responsive py-4 dataTables_wrapper">

            <div class="row">
              <div class="col-sm-12 col-md-6">
                <div class="dataTables_length" id="datatable-basic99_length">
                  <label>Show <select onchange="updatePerPage(this)"  class="custom-select custom-select-sm form-control form-control-sm">
                      @foreach([10, 25, 50, 100] as $perPage)
                        <option value="{{ $perPage }}" {{ $perPage == request()->perPage ? 'selected' : '' }}>{{ $perPage }}</option>
                      @endforeach
                    </select> entries</label>
                </div>
              </div>
              <div class="col-sm-12 col-md-6">
                <div id="datatable-basic99_filter" class="dataTables_filter">
                  <form method="GET">
                    <input type="hidden" name="perPage" value="{{ request()->perPage }}"/>
                    <label>Search:<input type="search" name="search" value="{{ request()->search }}" class="form-control form-control-sm" placeholder="" aria-controls="datatable-basic99"></label>
                  </form>
                </div>
              </div>
            </div>

            <table class="table align-items-center table-flush"  id="datatable-basic2">
              <thead class="thead-light">
              <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Created at') }}</th>
                <th scope="col"></th>
              </tr>
              </thead>
              <tbody>
              @foreach ($skills as $skill)
                <tr>
                  <td><a href="{{ route('skill.edit', $skill) }}">{{ $skill->name }}</a></td>
                  <td>{{ date_format($skill->created_at, 'Y-m-d' ) }}</td>
                  <td class="text-right">
                    <div class="dropdown">
                      <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        <a class="dropdown-item" href="{{ route('skill.edit', $skill) }}">{{ __('Edit') }}</a>
                        <form action="{{ route('skill.destroy', $skill) }}" method="post">
                          @csrf
                          @method('delete')

                          <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this skill?") }}') ? this.parentElement.submit() : ''">
                            {{ __('Delete') }}
                          </button>
                        </form>
                      </div>
                    </div>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>

          </div>
          <!-- Simple Pagination -->
          <div class="row py-2">
            <div class="col-sm-12 col-md-5">
              <div class="text-sm pl-4" id="" role="status" aria-live="polite">Showing {{ $skills->firstItem()  }} to
                {{ $skills->lastItem() }} of {{ $skills->total() }} entries</div>
            </div>
            <div class="col-sm-12 col-md-7">
              <div class="dataTables_paginate paging_simple_numbers" id="datatable-basic99_paginate">
                {{ $skills->withQueryString()->links() }}
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>




    @include('layouts.footers.auth')
  </div>
@endsection

@push('css')
  <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
@endpush

@push('js')
  <script>
    function updatePerPage(selectElement) {
      const perPage = selectElement.value;
      const currentUrl = new URL(window.location.href);
      currentUrl.searchParams.set('perPage', perPage);
      window.location.href = currentUrl.toString();
    }
  </script>
@endpush
