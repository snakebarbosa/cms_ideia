<div id="MainMenu" >
        <div class="list-group panel">

           @foreach($left as $key=>$item)
                 @if(isset($item['childreen'][0]))
                     @if(strpos($item['url'], 'http') !== false)
                               <!-- <a href="{{ $item['url'] }}" target="__blank"> -->
                            <a href="#demo{{ $key }}" class="list-group-item text-uppercase bgBlue first" data-toggle="collapse" data-parent="#MainMenu" style="width: 250px;color: #fff;border: none;">
                             
                            @else
                               <!-- <a href="{{ URL::to('/') }}{{ $item['url'] }}"> -->
                              <a href="#demo{{ $key }}" class="list-group-item text-uppercase bgBlue first" data-toggle="collapse" data-parent="#MainMenu" style="width: 250px;color: #fff;border: none;">
                            @endif

                              {{--@if(isset($item['imagems']))
                                <img src="{{ URL::to('/') }}/images/{{ $item['imagems']['url'] }}" class="img_menu_left"/>
                              @else
                                 <img src="{{ URL::to('/') }}/images/icons/passageiros.png"/>
                              @endif--}}

                              <span>{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</span>
                              <!-- <i class="glyphicon glyphicon-chevron-right"></i> -->
                              <i class="fa fa-caret-down"></i>
                            </a>
                            <div class="collapse" id="demo{{$key}}">
                              @foreach($item['childreen'] as $childreen)
                                    
                                     <a href="{{ URL::to('/') }}{{ $childreen['url'] }}"  class="list-group-item font12 " style="padding-left:30px;">
                                          {{  $childreen['conteudos'][Session::get('lan')]['titulo'] }}
                                      </a>

                                    <!-- <li role="separator" class="divider"></li> -->

                               @endforeach
                             </div>


                  @else
                     @if(strpos($item['url'], 'http') !== false)
                               <!-- <a href="{{ $item['url'] }}" target="__blank"> -->
                                <a href="{{ $item['url'] }}" class="list-group-item" target="__blank" style="width: 250px;">
                            @else
                               <!-- <a href="{{ URL::to('/') }}{{ $item['url'] }}"> -->
                                <a href="{{ URL::to('/') }}{{ $item['url'] }}" class="list-group-item" style="width: 250px;">
                            @endif

                             {{-- @if(isset($item['imagems']))
                                <img src="{{ URL::to('/') }}/images/{{ $item['imagems']['url'] }}" class="img_menu_left"/>
                              @else
                                <img src="{{ URL::to('/') }}/images/icons/passageiros.png"/>
                              @endif--}}
                                <span>{{ $item['conteudos'][Session::get('lan')]['titulo'] }}</span>
                              <!-- <i class="glyphicon glyphicon-chevron-right"></i>
                                <i class="fa fa-caret-right"></i>-->
                            </a>
                  @endif
         @endforeach


        </div>
      </div>