 $(function() {

        $(window).scroll(function() {

            var scroll = $(window).scrollTop();
       
           
            if (scroll >= 100) {

                $(".navtop").addClass("fixed-nav");
                $(".mebr_top").addClass("fixed");
                $("#back").css({ display: "block" });
            } else {

                $(".navtop").removeClass("fixed-nav");
                $(".mebr_top").removeClass("fixed");
                $("#back").css({ display: "none" });


            }


        });

    });
$(function(){

    $(window).width(function(){
        var width = $(window).width();

        if (width <=567){

            $("#back").css({ margin: "15px 0px 0px -65px" });
         }
    });

});

