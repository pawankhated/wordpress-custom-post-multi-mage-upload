jQuery(document).ready(function(){
  jQuery('.slider-for').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  fade: false,
   infinite: true,
});
jQuery('.slider-nav').slick({
  slidesToShow: 5,
  slidesToScroll: 1,
  asNavFor: '.slider-for',
  dots: false,
  arrows: false,
  infinite: true,
  focusOnSelect: true
});
    });
