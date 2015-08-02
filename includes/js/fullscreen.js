var resetx = $('#gameDiv').width();
var resety = $('#gameDiv').height();

document.getElementById("resetSlider").addEventListener("click", function(){ 
    $('#gameDiv').width(resetx+'px').height(resety+'px').css('position', 'relative');
     $('#gameDiv').css('margin-right','auto').css('margin-left','auto');
    $(".ui-slider-handle").css("left","0%");
});

var isFullscreen = false;
    
function oFull(){

    $('#gameDiv').width(resetx+'px').height(resety+'px').css('position', 'relative');
    $('#gameDiv').css('margin-right','auto').css('margin-left','auto');

    isFullscreen = true;

    $('#close').css('bottom','4px').css('right','4px')
    $('#close').css('zIndex','999').css('position','fixed')
    $('#close').show();
    $('#gameDiv').css({top:'0px',left:'0px',width:'100%',height:'100%'});
    $('#gameDiv').css({position:'fixed',zIndex:999,backgroundColor:'#000'});
    $('#swf').width('100%').height('100%');
    $('#gameDiv div[style^="visib"]').remove();
    $('#gameDiv').show()
}

function cFull(){
    isFullscreen = false;
    $('#close').hide();
    $('#swf').width('100%').height('100%');
    $('#gameDiv').width(resetx+'px').height(resety+'px').css('position', 'relative');
    $('#gameDiv').css('margin-right','auto').css('margin-left','auto');
    $(".ui-slider-handle").css("left","0%");
}

$(document).keyup(function(e) {
    if (e.keyCode == 27) {
        if(isFullscreen){
            cFull();
        }
    }
});

setTimeout( function() {
    $('#fullscreen').bind('click', function() {
        oFull();
    });
}, 2000 );
