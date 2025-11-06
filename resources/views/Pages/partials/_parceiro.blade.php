<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<style>
h2{
  text-align:center;
  padding: 20px;
}
/* Slider */

.slick-slide {
    margin: 0px 20px;
}

.slick-slide img {
    width: 100%;
}

.slick-slider
{
    position: relative;
    display: block;
    box-sizing: border-box;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
            user-select: none;
    -webkit-touch-callout: none;
    -khtml-user-select: none;
    -ms-touch-action: pan-y;
        touch-action: pan-y;
    -webkit-tap-highlight-color: transparent;
}

.slick-list
{
    position: relative;
    display: block;
    overflow: hidden;
    margin: 0;
    padding: 0;
}
.slick-list:focus
{
    outline: none;
}
.slick-list.dragging
{
    cursor: pointer;
    cursor: hand;
}

.slick-slider .slick-track,
.slick-slider .slick-list
{
    -webkit-transform: translate3d(0, 0, 0);
       -moz-transform: translate3d(0, 0, 0);
        -ms-transform: translate3d(0, 0, 0);
         -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
}

.slick-track
{
    position: relative;
    top: 0;
    left: 0;
    display: block;
}
.slick-track:before,
.slick-track:after
{
    display: table;
    content: '';
}
.slick-track:after
{
    clear: both;
}
.slick-loading .slick-track
{
    visibility: hidden;
}

.slick-slide
{
    display: none;
    float: left;
    height: 100%;
    min-height: 1px;
}
[dir='rtl'] .slick-slide
{
    float: right;
}
.slick-slide img
{
    display: block;
}
.slick-slide.slick-loading img
{
    display: none;
}
.slick-slide.dragging img
{
    pointer-events: none;
}
.slick-initialized .slick-slide
{
    display: block;
}
.slick-loading .slick-slide
{
    visibility: hidden;
}
.slick-vertical .slick-slide
{
    display: block;
    height: auto;
    border: 1px solid transparent;
}
.slick-arrow.slick-hidden {
    display: none;
}

.slick-prev {
    position: absolute;
    top: 50%;
    left: -4%;
}

.slick-next {
    position: absolute;
    top: 50%;
    right: -4%;
}
</style>

</head>
<body>
   



<!------ Include the above in your HEAD tag ---------->

<section id="aac-parceiro ">
 
    <div class="container top50 bottom50">
      <div class="row top30 bottom30">
          <div class="col-md-12 top30">
            {{-- <div class="container"> --}}
                <section class="customer-logos slider"> 
                    @foreach($parceiros as $item)
                        <div class="slide">
                            <a href="{{$item->url}}" target="_blank"><img src="{{URL::to('/') }}/files/images/{{$item->imagems->url}}" alt="{{$item->titulo}}"></a>
                        </div>
                    @endforeach
                </section>
            {{-- </div> --}}
          </div>
      </div><!--.container-->
    </div><!--.container-->
  </section>



  

<script>
$(document).ready(function(){
    $('.customer-logos').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2500,
        arrows: true,
        nextArrow: '<div class="slick-prev"><span class="fa fa-angle-left"></span><span class="sr-only">Prev</span></div>',
        prevArrow: '<div class="slick-next"><span class="fa fa-angle-right"></span><span class="sr-only">Next</span></div>',
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });
});

</script>

</body>
</html>
