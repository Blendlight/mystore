$(function()
  {
    $child_toggle = $(".child-toggle");
    $child_toggle.on("click", function()
                     {
        $(this).closest(".category_container").children(".category_childs").toggleClass("visible");
    })
});