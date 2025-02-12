<!-- latest jquery-->
<script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>

<!-- Bootstrap js-->
<script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

<!-- Simple bar js-->
<script src="{{ asset('assets/vendor/simplebar/simplebar.js') }}"></script>

<!-- phosphor js -->
<script src="{{ asset('assets/vendor/phosphor/phosphor.js') }}"></script>

<!-- Customizer js-->
<script src="{{ asset('assets/js/customizer.js') }}"></script>

<!-- prism js-->
<script src="{{ asset('assets/vendor/prism/prism.min.js') }}"></script>

<!-- App js-->
<script src="{{ asset('assets/js/script.js') }}"></script>
<script src="{{ asset('/assets/js/tooltips_popovers.js') }}"></script>


<!-- latest jquery-->
<script src="{{ asset('assets/vendor/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/select/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select.js') }}"></script>


<!-- toatify js-->
<script src="{{ asset('assets/vendor/notifications/toastify-js.js') }}"></script>
<script src="{{ asset('assets/vendor/toastify/toastify.js') }}"></script>


<!-- sweetalert js-->
<script src="{{ asset('assets/vendor/sweetalert/sweetalert.js') }}"></script>
@yield('script')
@stack('scripts')
