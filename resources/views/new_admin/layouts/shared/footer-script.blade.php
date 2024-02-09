<!-- bundle -->
<!-- Vendor js -->
<script src="{{asset('admin_assets/js/vendor.min.js')}}"></script>
@yield('script')
<!-- App js -->
<script src="{{asset(mix('js/app.js'))}}"></script>
<script src="{{asset('admin_assets/js/theme_app.min.js')}}"></script>
@yield('script-bottom')
