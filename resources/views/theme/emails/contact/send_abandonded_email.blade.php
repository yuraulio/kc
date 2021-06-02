<p>
    <h2>New entries from abandoned cart</h2>
    @if(isset($mail_data['list']))
        @foreach($mail_data['list'] as $user_id => $ucart)
        <?php
             $evdate = 'No Date';
             $city = 'No City';
                if(isset($mail_data['events'][$ucart->options['event']]['customFields'])) {
                    foreach ($mail_data['events'][$ucart->options['event']]['customFields'] as $ckey => $cvalue) {
                        if ($cvalue->name == 'simple_text' && $cvalue->priority == 0) {
                            $evdate = $cvalue->value;
                            if($mail_data['events'][$ucart->options['event']]->categories->where('parent_id',9)->first()!=null){
                            $city = $mail_data['events'][$ucart->options['event']]->categories->where('parent_id',9)->first()->name;
                            }
                            break;
                        }
                    }
                }
        ?>

            <a href="mailto:{{$mail_data['abcart'][$user_id]->user->email}}">{{$mail_data['abcart'][$user_id]->user->email}}</a> {{$mail_data['abcart'][$user_id]->user->first_name}} {{$mail_data['abcart'][$user_id]->user->last_name}}
            <br />
            {{$mail_data['events'][$ucart->options['event']]->title}} - {{$evdate}}<br />

            <br />
                City: {{$city}}
            <br/>
            {{$ucart->qty}} x {{$mail_data['tickets'][$ucart->id]->title}} = &euro;{{$ucart->qty*$ucart->price}}
            <br />

            @if(isset($mail_data['abcart'][$user_id]->created_at) && $mail_data['abcart'][$user_id]->created_at != '') Created Date:{{$mail_data['abcart'][$user_id]->created_at->format('d/m/Y H:i')}} @endif

            <hr />


        @endforeach
    @endif

</p>
