$(document).ready(function(){

    $('.dropdown-items').hide();
    $('.dropdown').click(function(){
        $('.dropdown-items').slideToggle(300);
    });
});