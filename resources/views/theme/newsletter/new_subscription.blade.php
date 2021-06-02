<h4>Προσθήκη συνδρομής</h4>
<p>
	Το email σας έχει προστεθεί στο {!! env('SITE_NAME', 'Knowcrunch')  !!} newsletter. Για να ενεργοποιήσετε τη συνδρομή σας κάντε κλικ
	<a href="{{ \URL::to('/newsletter/verify').'/'.$mail_data['code'] }}" title="Follow this link to activate your subscription">
        εδώ
    </a>.
</p>
<h4>Ευχαριστούμε,</h4>
<p>Η ομάδα της {!! env('THE_TEAM', 'Knowcrunch')  !!}</p>
