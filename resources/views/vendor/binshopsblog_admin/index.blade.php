@extends("binshopsblog_admin::layouts.admin_layout_integrational")

@section('crumbs')
    <li class="breadcrumb-item"><a href="#">{{ __('Blog') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Posts') }}</li>
@endsection

@section('cardy')
    <h5 class="card-title text-uppercase text-muted mb-0">Total Posts</h5>
    <span class="h2 font-weight-bold mb-0">{{$post_translations->total()}}</span>
@endsection

@section("content")
<div class="card mb-0">
    <div class="card-header" style="border-bottom: 0px">
       <div class="row align-items-center">
          <div class="col-8">
             <a href="{{ route('binshopsblog.admin.comments.index') }}" class="btn btn-sm btn-info"> <i class="fas fa-comments"></i> {{ __('Comments') }}</a>
             <a href="{{ route('binshopsblog.admin.categories.index') }}" class="btn btn-sm btn-success"><i class="fas fa-stream"></i> {{ __('Categories') }}</a>
             <a href="{{ route('binshopsblog.admin.images.all') }}" class="btn btn-sm btn-warning"><i class="fas fa-images"></i> {{ __('Images') }}</a>
             <a href="{{ route('binshopsblog.admin.languages.index') }}" class="btn btn-sm btn-danger"><i class="fas fa-globe"></i> {{ __('Languages') }}</a>
            </div>
          <div class="col-4 text-right">
            <a href="{{ route('binshopsblog.admin.create_post') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-circle"></i> {{ __('Create Post') }}</a>
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
                         <th scope="col" class="sorting_asc" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending" >Title</th>
                         <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-label="Created: activate to sort column ascending" >Author</th>
                         <th scope="col" class="sorting text-center" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-label="Created: activate to sort column ascending" >Is Published</th>
                         <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-label="Created: activate to sort column ascending" >Categories</th>
                         <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-label="Created: activate to sort column ascending" >Posted At</th>
                         <th scope="col" class="sorting" tabindex="0" aria-controls="datatable-basic34" rowspan="1" colspan="1" aria-label=": activate to sort column ascending" style="width: 117.562px;"></th>
                      </tr>
                   </thead>
                   <tbody>
                    @forelse($post_translations as $post)
                      <tr role="row" class="odd">
                          <td class="imgtagsm" style=""><?=$post->image_tag("thumbnail", false, "");?></td>
                         <td class="sorting_1 blogxtit" style=""><a  href='{{$post->url(app('request')->get('locale'))}}'>{{$post->title}}</a></td>
                         <td>{{$post->post->author_string()}}</td>
                         <td class="text-center">{!!($post->post->is_published ? "Yes" : '<span class="border border-danger rounded p-1">No</span>')!!}</td>
                         <td>
                            @if(count($post->post->categories))
                            @foreach($post->post->categories as $category)
                                <a class='btn btn-outline-secondary btn-sm m-1' href='{{$category->categoryTranslations->where('lang_id' , $language_id)->first()->edit_url()}}'>
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>

                                    {{$category->categoryTranslations->where('lang_id' , $language_id)->first()->category_name}}
                                </a>
                            @endforeach
                        @else No Categories
                        @endif
                         </td>
                         <td>{{$post->post->posted_at}}</td>
                         <td class="text-right">
                            <div class="dropdown">
                               <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fas fa-ellipsis-v"></i>
                               </a>
                               <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                  <a href="{{$post->url(app('request')->get('locale'))}}" class="dropdown-item "><i class="fa fa-file-text-o"
                                    ></i>
                                    View Post</a>
                                    <a href="{{$post->edit_url()}}" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    Edit Post</a>
                                    <form onsubmit="return confirm('Are you sure you want to delete this blog post?\n You cannot undo this action!');"
                                    method='post' style="padding: 0px; cursor: pointer !important" action='{{route("binshopsblog.admin.destroy_post", $post->post_id)}}' class='float-right dropdown-item'>
                                    @csrf
                                    <input name="_method" type="hidden" value="DELETE"/>
                                    <button type='submit' class='dropdown-item'>
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    Delete
                                    </button>
                                    </form>
                               </div>
                            </div>
                         </td>
                      </tr>
                      @empty
                        <div class='alert alert-warning'>No posts to show you. Why don't you add one?</div>
                      @endforelse
                   </tbody>
                </table>
             </div>
          </div>
          <div class="row">
             <div class="col-sm-12 col-md-5 mb-4">
                <div class="dataTables_info" id="datatable-basic34_info" role="status" aria-live="polite">Showing {{($post_translations->perPage() * ($post_translations->currentPage() - 1)) + 1 }} to {{$post_translations->total() < ($post_translations->perPage() * $post_translations->currentPage() ) ? $post_translations->total() : ($post_translations->perPage() * $post_translations->currentPage())}} of {{$post_translations->total()}} entries</div>
             </div>
             <div class="col-sm-12 col-md-7 ml-xs-4">
                <div class="dataTables_paginate paging_simple_numbers" id="datatable-basic34_paginate">
                {{ $post_translations->links('vendor.binshopsblog_admin.custom') }}
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>
@endsection
