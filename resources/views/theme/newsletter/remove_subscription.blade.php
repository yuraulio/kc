<h4>Ακύρωση συνδρομής</h4>
<p>
	Για να απεγγραφείτε από το {!! config('app.SITE_NAME', 'Knowcrunch')  !!} newsletter κάντε κλικ
	<a href="{{ \URL::to('/newsletter/verify-unsubscription').'/'.$mail_data['code'] }}" title="Follow this link to deactivate your subscription">
        εδώ
    </a>.
</p>
<h4>Ευχαριστούμε,</h4>
<p>Η ομάδα του {!! config('app.THE_TEAM', 'Knowcrunch')  !!}</p>
