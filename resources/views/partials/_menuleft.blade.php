<div class="col-sm-3 col-md-3">
    <div class="panel-group" id="accordion">
         
           @foreach($menu['pages'] as $parent)
            <div class="panel panel-default">  
                 <div class="panel-heading">   
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $parent['id'] }}"><span class="glyphicon glyphicon-{{ $parent['icon'] }}">
                        </span> {{ $parent['pagename'] }}</a>
                    </h4>
                 </div>
                <div id="collapse{{ $parent['id'] }}" class="panel-collapse collapse">
                    <div class="panel-body">
                       <table class="table">
                       
                        @if ($parent['submenu']!="NULL")
                            @foreach($parent['submenu'] as $item)
                           {{--  <li class="menu-item-has-children">
                                <a href="{{ $pages->pagelink }}">{{$pages['pagename']}}</a>
                            </li> --}}
                            <tr>
                                <td>
                                    <span class="glyphicon glyphicon-{{ $item['icon'] }} text-primary"></span><a href="{{ URL::to('/') }}/{{$item['pagelink']}}">{{$item['pagename']}}</a>
                                </td>
                            </tr>
                          @endforeach
                         @endif
                    </table>
                    </div>
                </div>
             </div> 
            @endforeach
           
       
    </div>
</div>