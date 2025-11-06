@foreach($tagrel as $item)
<div class="clearfix" style="margin-bottom: 21px;">
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12" style="">
      <!-- <span style="color:#23527c;">Tags Relacionados: </span> -->
       <h3 class="aac-block-title" style="margin:5px;margin-top:10px;margin-bottom:10px;">Tags Relacionados</h3>
        <div class="col-sm-12 col-lg-12" >
      @foreach($item['tags'] as $tag)
      <a href="{{ URL::to('/') }}/nav/{{ $tag['id']}}"><span class="tags shadow" >{{ $tag['name']}}</span></a>
      @endforeach
    </div>
    </div>
  </div>
</div>
 @endforeach