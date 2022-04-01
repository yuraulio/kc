<?php $eventCity = isset($event) && $event->city->first() ? $event->city->first()->id : -1 ?>

<div class="form-group{{ $errors->has('city_id') ? ' has-danger' : '' }}">
    <label class="form-control-label" for="input-city_id">{{ __('City') }}</label>
    <select name="city_id" id="input-city_id" class="form-control" placeholder="{{ __('City') }}" >
        <option>-</option>
        @foreach ($cities as $city)
            
            <option value="{{$city->id}}" @if($city->id == $eventCity) selected @endif> {{ $city->name }} </option>
        
                          
        @endforeach
    </select>
</div>
        


