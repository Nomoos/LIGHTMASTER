// zjisti deltu kolecka a zobrazi subnav pokud bude smer nahoru, tedy 1
var kolecko = function(e){
   e = e.originalEvent;
   var delta = e.wheelDelta>0||e.detail<0?1:-1; 

   $("#subnav").toggleClass('active', delta === 1, 250);
}

$(function(){

// nabinguje funkci na kolečko mysi
   $("body").bind("mousewheel DOMMouseScroll", kolecko);
   
// dosadi to #subnav-u submenu
   $(".item").on("click mouseover", function(){
      $(".item").removeClass("active");
      $(this).addClass("active");
      $("#subnav").html( $(this).find("ul").clone(true) ).addClass('active', 250);
   });

// scroll nahoru po kliku na horní listu menu
   $("#menu").on("click", function(){
      vyjedNahoru();
   });

//odstranuje zakladni efekty odkazu a zamezuje kliknat pro scrollovani nahoru
   $("#menu li, #subnav li").on("click", function(e){
      e.preventDefault();
   });

   $("#menu li, #subnav").on("click", function(e){
      e.stopPropagation();
   });

   $(document).keydown(function(e) {

      switch(e.which || e.keyCode)
      {
         case 88:         
            $("#subnav").stop(true,true).toggleClass('active', 250);
            break;
         case 84:
            vyjedNahoru();
            break;
      }
   });

   init();
});

var init = function(){
   $("nav > ul > li:nth-of-type(1)").click();
   $("#subnav").css('display', 'block');
}

var vyjedNahoru = function(){
   $("html, body").stop().animate(
      { scrollTop: 0 },
      1000, "easeInOutExpo"
   );     
   $("#subnav").stop(true, true).removeClass('active', 400).delay(600).addClass("active", 300);
}