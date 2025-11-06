<div class="clearfix" style="margin-bottom: 21px;">
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12" style="">
      <ul class="nav nav-tabs">




         @foreach($items as $item)
         		@if($item['ativado']== 1)
                <li class="nav-item"><a href="{{ URL::to('/') }}{{ $item['url'] }}" class="nav-link" style="color: #123b64;text-transform: uppercase;">{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</span></a></li>
                @endif
          @endforeach


      </ul>
    </div>
  </div>
</div>

