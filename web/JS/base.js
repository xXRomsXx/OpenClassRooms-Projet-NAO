$(function() {

    $('#flash-message').hide();

    $('#icon').on('click', function(){

       $('#icon').toggleClass('isActive');
       $('#menu-xs').fadeToggle(300);

   });

   $('#menu-xs li').on('click', function() {

      $('#icon').toggleClass('isActive');
      $('#menu-xs').fadeToggle(300);

   });

});
