$jq(document).ready(function(){ 
 // top cart
 (function($jq){
  //show subnav on hover
  $jq('.top-cart-contain').mouseenter(function() {
   $jq(this).find(".top-cart-content").stop(true, true).slideDown();
  });
  //hide submenus on exit
  $jq('.top-cart-contain').mouseleave(function() {
   $jq(this).find(".top-cart-content").stop(true, true).slideUp();
  });
 })($jq);
 // form search
 (function($jq){
  //show subnav on hover
  $jq('.search-contain').mouseenter(function() {
   $jq(this).find(".search-content").stop(true, true).slideDown();
  });
  //hide submenus on exit
  $jq('.search-contain').mouseleave(function() {
   $jq(this).find(".search-content").stop(true, true).slideUp();
  });
 })($jq);
 
});