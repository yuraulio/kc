<?php $eventPartner = isset($event) && $event->partners->first() ? $event->partners->first()->id : -1 ?>

<div class="form-group{{ $errors->has('partner_id') ? ' has-danger' : '' }}">
    <label class="form-control-label" for="input-partner_id">{{ __('Partner') }}</label>
    <select name="partner_id" id="input-partner_id" class="form-control" placeholder="{{ __('Partner') }}" >
        <option>-</option>
        @foreach ($partners as $partner)
            
            <option value="{{$partner->id}}" @if($partner->id == $eventPartner) selected @endif> {{ $partner->name }} </option>
        
                          
        @endforeach
    </select>
</div>


