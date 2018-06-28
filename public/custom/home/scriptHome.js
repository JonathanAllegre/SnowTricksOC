// PLACE ARROW IN HOME IMG
$(document).ready(function() {
  var heightNav = $("#navTop").height();
  var topP = 72;
  var LeftP = 90;
  var height = $("#bgImg").height();
  var windowWidth = $(window).width();
  var height = ((topP * height) / 100) - heightNav;
    if (windowWidth < 500){
        var left = ((LeftP * windowWidth) / 100) - 20;
    }else{
        var left = (LeftP * windowWidth) / 100;
    }
  $("#linkTricks").css("margin-top", height + "px");
  $("#linkTricks").css("margin-left", left + "px");
  var heightWind = $(window).height();
  $(".containerAcc").css("min-height", heightWind - heightNav);
});

// ON CLICK ON LINK WITH # => SMOOTH SCROLL
var encre = "a[href^=\"#\"]";

$(encre).click(function(){
    var height = $("#bgImg").height();
    var theId = $(this).attr("href");
    if (theId === "#") {
        return;
    }
    $("html, body").animate({
        scrollTop:height+1
    }, 'slow');
    return false;
});

// DISPLAY THE RIGHT FOOTER
$(window).scroll(function() {
    if ($(window).width() > 500){
        var footer = "navBottomDesktop";
    }else {
            footer = "navBottomMobil";
    }
  var height = $("#bgImg").height();
  if(($(window).scrollTop() > height / 1.2)) {
    $("#" + footer).show();
  }else{
    $("#scroll").show();
    $("#" + footer).hide();
  }
});

// MODAL SUPPRESSION
$(".deletIcon").click(function(){
    $('#deleteButton').attr('href', $(this).attr("trickPath"));
    $('#exampleModalLabel').html('Suppression du trick "'+ $(this).attr("trickName")+'"');
});

