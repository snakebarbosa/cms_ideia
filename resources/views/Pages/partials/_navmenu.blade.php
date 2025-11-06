 <header class="borderBottom bgLogo">
    <div class="container">
       <div class="col-sm-12 col-md-9 col-lg-9">
            <ul class="info_top">   <!-- languages -->
           
             <li  style="padding: 4px;">
               Notifique-nos em caso de acidente:
               <li style="">
              <li style="">
                <a href="mailto:notification@ipiaam.gov.cv"><i class="glyphicon glyphicon-envelope"></i> {{$emailnot}}</a>
              </li>
              <li style="">
                {{-- <a href="#"><i class="glyphicon glyphicon-phone"></i> {{$phone}}</a> --}}
                <a href="#"><i class="glyphicon glyphicon-phone"></i> {{$phone_notification}}</a>
              </li>
            
            <!-- login -->
           
        </ul>
      </div>
    
   <!--    <div class="col-sm-12 col-md-3 col-lg-3">
        
      </div>

      <div class="col-sm-12 col-md-3 col-lg-3">
         
      </div> -->

      <div class="col-sm-12 col-md-3 col-lg-3" >
         <ul class="language">   <!-- languages -->
           
             @foreach($language as $item)
              <li style="">
                <a href="{{ URL::to('/') }}/setLanguage/{{$item->id}}">{{$item->tag}}</a>
              </li>
             @endforeach
            
            <!-- login -->
           
        </ul>
      </div>

        
    </div>
  </header>