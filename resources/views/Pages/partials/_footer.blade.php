<footer >
  <div class="footer" id="footer">
   
    <div class="container">
      <div class="row"> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 top30">
             <a class="navbar-brand cmplogo" style="padding-bottom: 30px; margin-top: 0px!important;" href="{{ URL::to('/') }}">
                            <img class="city" src="{{URL::to('/') }}/files/images/logo_white.png" style="height:80px!important;">
                         </a>
          </div>
        {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 top30 ">
              <div class="addthis_inline_follow_toolbox pull-right"></div>
          </div> --}}
      </div>
      <div class="row " style="border-bottom:1px solid rgba(255,255,255,0.4);"></div>
      
      <div class="row"> 
       
         <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 top30">
             <div class="col-12 ">
                  <h4 class="fontBold fwhite textUpper tituloFooter bottom20">Mapa do Site</h4> 
                </div>
                <div class="d-flex flex-column">
                 
                    @foreach($rodape as $item)
                                 <div class="p-2 fwhite"><a href="{{ URL::to('/') }}{{ $item['url'] }}">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</a></div>
                            @endforeach
                </div>
          </div>

         <!-- END block1 -->

        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 top30 ">
          <div class="col-12 ">
                  <h4 class="fontBold fwhite textUpper tituloFooter bottom20">Links Úteis</h4> 
                </div>
          <div class="d-flex flex-column">
             
             @foreach($linksf as $item)
                                  <div class="p-2 fwhite"><a target="_blank" href="{{ $item['url'] }}">{{ $item['titulo'] }}</a></div>
                            @endforeach
          </div>

        </div>

        <!-- END block2 -->

        
           <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 top30" >
               <div class="col-12 ">
                  <h4 class="fontBold fwhite textUpper tituloFooter bottom20">Contactos</h4> 
                </div>
              <div class="media mtop0" style="height: 40px;">
                <div class="media-left">
                  <a href="#">
                    <img class="media-object" src="{{URL::to('/') }}/files/images/phone.png" alt="...">
                  </a>
                </div>
                <div class="media-body">
                  <p class="font11 fwhite">{{$phone}}</p>
                </div>
              </div>
               <div class="media mtop0" style="height: 40px;">
                  <div class="media-left">
                    <a href="#">
                      <img class="media-object" src="{{URL::to('/') }}/files/images/mail.png" alt="...">
                    </a>
                  </div>
                  <div class="media-body">
                  
                    <p class="font11 fwhite">{{$email}} </p>
                    
                  </div>
                </div>
                 <div class="media mtop0" style="height: 40px;">
                  <div class="media-left">
                    <a href="#">
                      <img class="media-object" src="{{URL::to('/') }}/files/images/location.png" alt="...">
                    </a>
                  </div>
                  <div class="media-body">
                  
                    <p class="font11 fwhite">{{$address}}</p>
                    
                  </div>
                </div>
                
                <div class="media mtop0" style="height: 40px;">
                  <div class="media-left">
                    <a href="#">
                      <img class="media-object" src="{{URL::to('/') }}/files/images/location.png" alt="...">
                    </a>
                  </div>
                  <div class="media-body">
                    <p class="font11 fwhite">{{$address2}}</p>
                  </div>
                </div>

             
        </div>

         <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 top30 ">
           <div class="col-12 ">
              <h4 class="fontBold fwhite textUpper tituloFooter bottom20">Newsletter</h4> 
            </div>
            <form id="form_news">
              <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
              </div>
              <button type="submit" class="btn" style="background-color: #1560B0; color:aliceblue;">Subscribe</button>
            </form>
            <div id="id_response"></div>
        </div>

     </div>
    </div>
  </div>

   <div class="footer footer2" >
    <div class="container">
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" >
             <div class="copyright-tag">Copyright © {{ date("Y") }} CPIAA - Cabo Verde. All Rights Reserved.
              </div>
              
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" >
             
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" >
             
              <div class="copyright-tag">Designed and Developed by <a href="https://www.ideia.cv" target="_blank" style="font-size: 10px;">iDE!A</a>
              </div>
          </div>
        
        </div>
     </div>
   </div>

</footer>


@section('script_bottom')
    <script>
      $(document).ready(function() {

        // process the form
        $('#form_news').on('submit', function(e) {

            e.preventDefault();
            $('#id_response').empty();
            var email = $('#email').val();

            $.ajax({
                type: "POST",
                url: "/newslletter",
                data:{
                "_token": "{{ csrf_token() }}",
                email:email,
              },
              success:function(response){
                console.log(response);
                if (response.msg == "Success") {
                  $('#id_response').append('<p style="color:green;">Inscrição efectuado com Sucesso</p>');
                }else if (response.msg == "Exist"){
                  $('#id_response').append('<p style="color:yellow;">Já se encontra inscrito</p>');
                }else if (response.msg == "Error"){
                  $('#id_response').append('<p style="color:red;">Erro na inscrição</p>');
                }
              },
              
              
            });

        });
      });
    </script>
@endsection('script')
    
