

<!-- Jquery Core Js -->
<script src="{{ URL::to('/') }}/plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap Core Js -->
<script src="{{ URL::to('/') }}/plugins/bootstrap/js/bootstrap.js"></script>



<!-- Slimscroll Plugin Js -->
<script src="{{ URL::to('/') }}/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

<!-- Waves Effect Plugin Js -->
<script src="{{ URL::to('/') }}/plugins/node-waves/waves.js"></script>

<!-- Custom Js -->
<script src="{{ URL::to('/') }}/js/material/admin.js"></script>

<!-- Demo Js -->
<script src="{{ URL::to('/') }}/js/material/demo.js"></script>

<script src="{{URL('/')}}/plugins/sweetalert/sweetalert.min.js"></script>
<!-- Jquery Validation Plugin Css -->
<script src="{{URL('/')}}/plugins/jquery-validation/jquery.validate.js"></script>
<script src="{{URL('/')}}/js/validate.js"></script>
<script src="{{URL('/')}}/js/validateFiles.js"></script>
<script src="{{URL('/')}}/js/cms.js"></script>

<script src="{{ URL::to('/') }}/plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="{{ URL::to('/') }}/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
<script src="{{ URL::to('/') }}/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="{{ URL::to('/') }}/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>

@yield('script')

{{-- <script src="{{ URL::to('/') }}/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="{{ URL::to('/') }}/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="{{ URL::to('/') }}/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="{{ URL::to('/') }}/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="{{ URL::to('/') }}/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script> --}}



<script>
    $(document).ready(function(){
      var table = $('.tableCMP').DataTable({
               'language': {
                  'url': '//cdn.datatables.net/plug-ins/1.10.9/i18n/Portuguese.json'
              },
               stateSave: true
     });
});
</script>