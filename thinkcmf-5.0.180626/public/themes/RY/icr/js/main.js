/*
公共js
 */

var content = '<div style="margin:0;line-height:20px;padding:2px;">' +
    '<img src="../img/baidu.jpg" alt="" style="float:right;zoom:1;overflow:hidden;width:100px;height:100px;margin-left:3px;"/>' +
    '' +
    '</div>';
function itf(obj){
$(obj).parent().find(".teacher_detail").show();
}
function itfs(obj){
    $(obj).parent().find(".teacher_detail").hide();
}
var popup=function(){
    var id  = 'popup';
    if(!$('#'+id).html()){
        $('body').prepend(popup_html);
        $(".cease").click(function(){
            $("#popup").remove();
        });
        $(".course-level i").click(function(){
            $('.course-level select').click();
        });
        $(".wd-title2").html($(this).data("name")+"："+$(this).data("location"))
    }
};
var popup2=function(data){
    var id = 'popup2';
    if(!$('#'+id).html()){
        $('body').prepend(popup2_html);
        $('#popup2').find(".wd-title2").html($(this).data("location"));
        $('#popup2').find('.time_content').html($(this).data('time_content'));
        $(".cease").click(function(){
            $("#popup2").remove();
        });

    }
};
$(function(){
    $("[name='yy']").click();
    $(".agreement i").click(function(){
        if($(this).hasClass("fa-square-o")){
            $(this).removeClass('fa-square-o')
            $(this).addClass('fa-check-square-o')
        }else{
            $(this).removeClass('fa-check-square-o')
            $(this).addClass('fa-square-o')
        }
    });
    $(".radios-item").mouseover(function(){
        $(this).find('.click').css('display','block');
    });
    $(".radios-item").mouseleave(function(){
        $(this).find('.click').css('display','none');
    });
    $(".comment-item").mouseover(function(){
        $(this).find('.click').css('display','block');
        $(this).find('.title').css('display','none');
    });
    $(".comment-item").mouseleave(function(){
        $(this).find('.click').css('display','none');
        $(this).find('.title').css('display','block');
    });
    var timeoutId = null;
    $("[name='back']").click(function(){
        var obj = $(this).parents('.point-item');
        var self = $(this);
        var isup = $(this).hasClass('fa-chevron-up');
        clearTimeout(timeoutId);
        if(isup)
        {
            //升
            timeoutId = window.setInterval(function(){
                if(obj.height()>40){
                    obj.height(obj.height()-1)
                }else{
                    self.removeClass('fa-chevron-up');
                    self.addClass('fa-chevron-down');
                    clearInterval(timeoutId)
                }
            },1)
        }else{
            //降
            timeoutId = window.setInterval(function(){
                if(obj.height()<177){
                    obj.height(obj.height()+1)
                }else{
                    self.removeClass('fa-chevron-down');
                    self.addClass('fa-chevron-up');
                    clearInterval(timeoutId)
                }
            },1)
        }


    })

    $(".cease").click(function(){
        $(".more-country").hide();
    })
    $(".more").click(function(){
        $(".more-country").show();
    })


    //登录注册弹窗事件
    $(".cease").click(function(){
        $(".l_r_window").hide();
    });
    $(".window-tab div").click(function(){
        var id = $(this).data('id');
        $(".window-tab div").removeClass('active');
        $(this).addClass('active');
        $(".window-tab-item").removeClass('active');
        $("#"+id).addClass('active');
    })
    $("#login").click(function(){
        $(".l_r_window").show();
        $("#login_click").click();
    })
    $("#register").click(function(){
        $(".l_r_window").show();
        $("#register_click").click();
    })
    $(".position").click(function(){
        $(".city_list").toggle();
    })
    //轮播
    if($(".main-img .img_list").length>0){
        var page_index = 1;
        xgdiv(page_index);
        var count = $(".img_list img").length;
        $(".img_list").width(count*1440);
        $(".an-margin").width(count*20-10);
        var sif=function(){
            page_index++;
            if(page_index<=count){
                $(".img_list").animate({
                    marginLeft:-(page_index-1)*1440
                });
            }else{
                $(".img_list").css("margin-left",0);
                page_index=1;
            }
            xgdiv(page_index);
        };
        var si = setInterval(sif,5000);
        function xgdiv(page_index){
            $(".an-margin div").css('background-color',"#bdbdbd");
            $("#an-"+page_index).css('background-color',"#ffffff");
        }
        $(".an-margin div").click(function(){
            page_index = $(this).data("id");
            if(page_index==0){
                $(".img_list").css("margin-left",0)
            }else{
                $(".img_list").animate({
                    marginLeft:-(page_index-1)*1440
                });
            }
            xgdiv(page_index);
        })
    }

    $(".video").click(function(){
        $(this).hide();
        $(".video video").trigger("pause")
    })
    $(".video video").click(function(){
        return false;
    })
});