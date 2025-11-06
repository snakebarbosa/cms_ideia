@if(Session::has('success'))

  <div class="alert alert-success" role="alert">
    <strong>{{Session::get('success')}}</strong>
  </div>
@endif

@if(Session::has('warning'))

  <div class="alert alert-warning" role="alert">
    <strong>{{Session::get('warning')}}</strong>
  </div>
@endif

@if(Session::has('error'))
  <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">×</button>	
    <strong>{{Session::get('error')}}</strong>
  </div>
@endif

@if(count($errors) > 0)

  <div class="alert alert-danger" role="alert">
   {{--  <strong>Error:</strong> --}}
    <ul>
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
   </ul>
  </div>

@endif