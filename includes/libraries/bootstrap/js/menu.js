$(document).ready(function(){
    ontop = $(".onTop");
    window.onkeypress = function(evt)
    {
        console.log(evt);
        if(evt.key=='s')
        {
            ontop.toggleClass("show");
        }
    }
});
