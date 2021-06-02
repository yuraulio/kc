<ul class="dont-boost" id="sidebar">

    <li class="{{ Request::is('myaccount') ? 'active' : '' }}">
        <a href="/myaccount" title="My profile">My profile</a>
    </li>

    <li class="{{ Request::is('myaccount/billing') ? 'active' : '' }}">
        <a href="/myaccount/billing" title="My billing info">My billing info</a>
    </li>

    <li class="{{ Request::is('myaccount/events') ? 'active' : '' }}">
        <a href="/myaccount/events" title="My events">My events</a>
    </li>

    <li class="{{ Request::is('myaccount/events/exams') ? 'active' : '' }}">

        <a href="myaccount/events/exams" title="My events">My exams</a>

    </li>

    <li class="{{ Request::is('myaccount/presentations') ? 'active' : '' }}">
        <a href="/myaccount/presentations" title="My presentations">My files</a>
    </li>
    {{-- <li class="disabled">
        <a href="/myaccount/assessments" title="My assessments">My assessments</a>
    </li>
    <li class="disabled">
        <a href="/myaccount/exams" title="My exams">My exams</a>
    </li> --}}

</ul>
