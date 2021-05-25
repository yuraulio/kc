<form method="post" action="{{ route('metas.update',$metas->id) }}" autocomplete="off" enctype="multipart/form-data">
@csrf
<div class="form-group{{ $errors->has('metas') ? ' has-danger' : '' }}">
   <label class="form-control-label" for="input-title">{{ __('Metas') }}</label>
   
   <div class="pl-lg-4">
                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-title">{{ __('Title') }}</label>
                           <input type="text" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title',$metas->meta_title) }}"  required autofocus>
                           
                        </div>

                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-title">{{ __('Keywords') }}</label>
                           <input type="text" name="keywords" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Keywords') }}" value="{{ old('title',$metas->meta_keywords) }}"  required autofocus>
                           
                        </div>


                        <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                           <label class="form-control-label" for="input-title">{{ __('Description') }}</label>
                           <input type="text" name="description"  class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('title',$metas->meta_description) }}"  required autofocus>
                           
                        </div>
                  
                        <div class="text-right">
                           <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                        </div>
                     </div>

   @include('alerts.feedback', ['field' => 'metas'])
</div>
</form>