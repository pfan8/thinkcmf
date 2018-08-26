$(function(){
    $(".head-left").click(function(){
        $("#left_menu").toggle();
    });
    $("#foot_menu_more").click(function(){
        $("#footer .foot-more").toggle()
    });
    $(".slide ul").width($(".slide ul li").length*7.5+"rem");
    $(".agreement i").click(function(){
        if($(this).hasClass("fa-square-o")){
            $(this).removeClass('fa-square-o')
            $(this).addClass('fa-check-square-o')
        }else{
            $(this).removeClass('fa-check-square-o')
            $(this).addClass('fa-square-o')
        }
    });
});