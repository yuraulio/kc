@extends("binshopsblog_admin::layouts.admin_layout_integrational")

@section('crumbs')
    <li class="breadcrumb-item"><a href="#">{{ __('Blog') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Languages') }}</li>
@endsection

@section('cardy')
    <h5 class="card-title text-uppercase text-muted mb-0">Total Languages</h5>
    <span class="h2 font-weight-bold mb-0">{{$language_list->count()}}</span>
@endsection

@section("content")
<div class="card mb-0">
    <div class="card-header" style="border-bottom: 0px">
       <div class="row align-items-center">
          <div class="col-8">
             <a href="{{ route('binshopsblog.admin.index') }}" class="btn btn-sm btn-info"> <i class="fas fa-arrow-left"></i> {{ __('Back to Posts') }}</a>
            </div>
          <div class="col-4 text-right">
            <a href="{{ route('binshopsblog.admin.languages.create_language') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-circle"></i> {{ __('Create Language') }}</a>
          </div>
       </div>
    </div>
    <div class="col-12">
       <div id="success-message" class="alert alert-success alert-dismissible success-message" style="display:none;" role="alert">
          <p> </p>
          <button type="button" class="close-message close" aria-label="Close">
          <span aria-hidden="true">×</span>
          </button>
       </div>
       <div id="error-message" class="alert alert-danger alert-dismissible error-message" style="display:none;" role="alert">
          <p> </p>
          <button type="button" class="close-message close" aria-label="Close">
          <span aria-hidden="true">×</span>
          </button>
       </div>
    </div>
    <div class="table-responsive">
       <div id="datatable-basic34_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
          {{-- <div class="row">
             <div class="col-12">
                <div id="datatable-basic34_filter" class="dataTables_filter py-2">
                    <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="datatable-basic34">
                </div>
             </div>
          </div> --}}
          <div class="row">
             <div class="col-sm-12">
                <table class="table table-flush dataTable no-footer" id="datatable-basic34" role="grid" aria-describedby="datatable-basic34_info">
                   <thead class="thead-light">
                      <tr role="row">
                        <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-label="Created: activate to sort column ascending" >#</th>
                         <th scope="col" class="sorting_asc" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" >Language</th>
                         <th scope="col" class="sorting_asc" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" >Locale</th>
                         <th scope="col" class="sorting_asc" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" >Date Format</th>
                         <th scope="col" class="sorting_asc" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" >Active</th>
                         <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-label="Created: activate to sort column ascending" >Created At</th>
                         <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-label=": activate to sort column ascending" style="width: 117.562px;"></th>
                      </tr>
                   </thead>
                   <tbody>
                    @forelse ($language_list as $index => $language)
                      <tr role="row" class="odd">
                          <td class="imgtagsm" style="">{{ $index + 1 }}</td>
                         <td class="sorting_1 blogxtit" style=""> {!! mb_strimwidth($language->name, 0, 100, "...") !!} </td>
                         <td class="sorting_1 blogxtit" style=""> {{ $language->locale }} </td>
                         <td class="sorting_1 blogxtit" style=""> {{ $language->date_format }} </td>
                         <td class="sorting_1 blogxtit" style=""> @if($language->active == 1) Yes @else No @endif </td>
                         <td>{{$language->created_at}}</td>
                         <td class="text-right">
                            <div class="dropdown">
                                <form onsubmit="return confirm('Are you sure you want to do this action?');" method='post' action='{{route("binshopsblog.admin.languages.toggle_language", $language->id)}}' class='float-left'>
                                    @csrf
                                    @if($language->active == 1)
                                        <input type='submit' class='card-link btn btn-outline-secondary' value='Disable'/>
                                    @else
                                        <input type='submit' class='card-link btn btn-primary' value='Enable'/>
                                    @endif
                                </form>
                               </div>
                            </div>
                         </td>
                      </tr>
                      @empty
                        <div class='alert alert-warning'>No languages to show you. Why don't you add one?</div>
                      @endforelse
                   </tbody>
                </table>
             </div>
          </div>
       </div>
    </div>
 </div>
@endsection

