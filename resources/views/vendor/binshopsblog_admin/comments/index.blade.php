@extends("binshopsblog_admin::layouts.admin_layout_integrational")

@section('crumbs')
    <li class="breadcrumb-item"><a href="#">{{ __('Blog') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Comments') }}</li>
@endsection

@section('cardy')
    <h5 class="card-title text-uppercase text-muted mb-0">Total Comments</h5>
    <span class="h2 font-weight-bold mb-0">{{$comments->total()}}</span>
@endsection

@section("content")
<div class="card mb-0">
    <div class="card-header" style="border-bottom: 0px">
       <div class="row align-items-center">
          <div class="col-8">
             <a href="{{ route('binshopsblog.admin.index') }}" class="btn btn-sm btn-info"> <i class="fas fa-arrow-left"></i> {{ __('Back to Posts') }}</a>
            </div>
          <div class="col-4 text-right">
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
                         <th scope="col" class="sorting_asc" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" >Message</th>
                         <th scope="col" class="sorting_asc" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" >Post</th>
                         <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-label="Created: activate to sort column ascending" >Author</th>
                         <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-label="Created: activate to sort column ascending" >Posted At</th>
                         <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-label=": activate to sort column ascending" style="width: 117.562px;"></th>
                      </tr>
                   </thead>
                   <tbody>
                    @forelse ($comments as $index => $comment)
                      <tr role="row" class="odd">
                          <td class="imgtagsm" style="">{{ $index + 1 }}</td>
                         <td class="sorting_1 blogxtit" style=""> {!! mb_strimwidth($comment->comment, 0, 100, "...") !!} </td>
                         <td class="sorting_1 blogxtit" style="">
                            @if($comment->post)
                            <a class="a-link-cart-color" href='{{$comment->post->postTranslations()->get()[0]->url(app('request')->get('locale'))}}'>{{$comment->post->postTranslations()->get()[0]->title}}</a>
                            @else
                                Unknown blog post
                            @endif
                         </td>
                         <td>{{$comment->author()}}</td>
                         <td>{{$comment->created_at}}</td>
                         <td class="text-right">
                            <div class="dropdown">
                               <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fas fa-ellipsis-v"></i>
                               </a>
                               <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                  <a href="{{$comment->post->postTranslations()->get()[0]->url(app('request')->get('locale'))}}" class="dropdown-item ">
                                    View Post
                                </a>
                                <form onsubmit="return confirm('Are you sure you want to delete this blog post comment?\n You cannot undo this action!');"
                                        method='post' action='{{route("binshopsblog.admin.comments.delete", $comment->id)}}'>
                                    @csrf
                                    <input name="_method" type="hidden" value="DELETE"/>
                                    <button type='submit' class='dropdown-item text-danger'>
                                    Delete
                                    </button>
                                </form>
                               </div>
                            </div>
                         </td>
                      </tr>
                      @empty
                        <div class='alert alert-warning'>No comments to show you. Why don't you add one?</div>
                      @endforelse
                   </tbody>
                </table>
             </div>
          </div>
          <div class="row">
             <div class="col-sm-12 col-md-5 mb-4">
                <div class="dataTables_info" id="datatable-basic34_info" role="status" aria-live="polite">Showing {{($comments->perPage() * ($comments->currentPage() - 1)) + 1 }} to {{$comments->total() < ($comments->perPage() * $comments->currentPage() ) ? $comments->total() : ($comments->perPage() * $comments->currentPage())}} of {{$comments->total()}} entries</div>
             </div>
             <div class="col-sm-12 col-md-7 ml-xs-4">
                <div class="dataTables_paginate paging_simple_numbers" id="datatable-basic34_paginate">
                {{ $comments->links('vendor.binshopsblog_admin.custom') }}
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>
@endsection

