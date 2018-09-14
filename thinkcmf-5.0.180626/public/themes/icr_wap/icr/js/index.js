var popup=function(){
    var school_name = $(this).data('name');
    var school_position=$(this).data('position');
    if($('body').find('#popup').length){
        //存在显示
        $(".wd-title2").html(school_name+"："+school_position);
        $('#popup').show();
    }else{
        //不存在加载

        $('body').append(reservation_wd_html);
        $(".wd-title2").html(school_name+"："+school_position);
        $("#popup").show();
    }
    $(".cease").click(function(){
        $("#popup").remove();
    });
};
var popup2=function(){
    var school_name = $(this).data('name');
    var school_position=$(this).data('position');
    var school_open_hours=$(this).data('time_content');
    if($('body').find('#popup2').length){
        //存在显示
    }else{
        //不存在加载

        $('body').append(open_time_html);

    }
    $(".wd-title3").html(school_name);
    $(".wd-title4").html(school_position);
    $(".time_content").html(school_open_hours);
    $("#popup2").show();
    $(".cease").click(function(){
        $("#popup2").remove();
    });
};
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
    //轮播
    var page = 1;
    var slide= function(){
        var num = $(".slide ul").find("li").length;
        if(num<=1){
            clearInterval(slideInterval);
            return ;
        }
        if(page<=num){
            $(".slide ul").animate({
                marginLeft:(-(page-1)*7.5)+"rem"
            })
            page++;
        }else{
            $('.slide ul').css("margin-left",0);
            page=1;
        }

    }
    var slideInterval =setInterval(slide,"2000");

    //预约弹窗定义
    $("[name='yy']").click(popup);
    $("[name='open_time']").click(popup2);
    $("[name='submit_reservation']").click(function(){
        //在这绑定预约请求
    });
    //绑定城市选择
    $(".head-title,.head-right").click(function(){
        $(".head-position").toggle();
    })

});