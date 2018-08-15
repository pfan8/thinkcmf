/*
公共js
 */
var popup_html="<div id='popup' class='popup'>" +
    "<div class='wd'>" +
    "<div class='wd-title'>免费预约试听</div>" +
    "<div class='wd-title2'>思明中心：厦门市思明区莲前西路2号莲富大厦3H</div>" +
    "<form>" +
    "<div class='input-text'>" +
    "<div class='course-level'><input type='text' placeholder='预约试听课程级别'/><i class='fa fa-caret-down fa-2x'></i></div>"+
    "<input type='text' placeholder='输入手机号预约课程试听' />" +
    "<input type='submit' value='确认预约试听'>" +
    "</div>" +
    "</form>" +
    "<div class='cease'>&times;</div>" +
    "</div>" +
    "</div>";
var techthers="                <div class=\"headteacher\">\n" +
    "                    <div class=\"teacher-avatar\">\n" +
    "                        <img src =\"\"/>\n" +
    "                    </div>\n" +
    "                    <div class=\"information\">\n" +
    "                        <div class=\"name\"></div>\n" +
    "                        <div class=\"content\"></div>\n" +
    "                        <div class=\"idea\"></div>\n" +
    "                    </div>\n" +
    "                    <div class=\"clear\"></div>\n" +
    "                </div>"
function popup(){
    var id  = 'popup';
    if(!$('#'+id).html()){
        $('body').prepend(popup_html);
        $(".cease").click(function(){
            $("#popup").remove();
        });
    }

}


$(function(){
    $("[name='yy']").click(popup);
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
    $(".img-left").mousedown(function(){
        var list = $(this).parents('.img-gd').find('.img-list');
        var padding = list.css('marginLeft');
        clearTimeout(timeoutId);
        timeoutId = window.setInterval(function(){
            var newpadding = parseInt(list.css('marginLeft').replace('px','')) + 1;
            if(newpadding<=0){
                list.css('marginLeft',newpadding+'px')
            }
        },1)
    });
    $(".img-right").mousedown(function(){
        var list = $(this).parents('.img-gd').find('.img-list');
        var padding = list.css('marginLeft');
        clearTimeout(timeoutId);
        timeoutId = window.setInterval(function(){
            var newpadding = parseInt(list.css('marginLeft').replace('px',''))-1;
            if(-newpadding<=(list.width()-1440)){
                list.css('marginLeft',newpadding+'px')
            }
        },1)
    });
    $(".img-left").mouseup(function(){
        clearTimeout(timeoutId);
    });
    $(".img-right").mouseout(function(){
        clearTimeout(timeoutId);
    });
    $(".img-left").mouseout(function(){
        clearTimeout(timeoutId);
    });
    $(".img-right").mouseup(function(){
        clearTimeout(timeoutId);
    });
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
    var itf = function(){
        var html = $(this).parent(".teacher-row").html();
        $(this).parent(".teacher-row").html(techthers);
        $(".headteacher").data('html',html);
        $(".headteacher").find(".teacher-avatar img")[0].src =$(this).find('img').attr('src');
        $(".headteacher").css('visibility','visible');
        var info = $(this).find(".info");
        $(".information").find(".name").html(info.find(".name").html());
        $(".information").find(".content").html(info.data("content"));
        $(".information").find(".idea").html(info.data('idea'));
        $(".headteacher").mouseleave(function(){
            $(this).parent(".teacher-row").html($(this).data('html'))
            $(".teacher-item").mouseover(itf);
        })
    }
    $(".teacher-item").mouseover(itf)

});