$(function(){
    cols_per_row = 4;
    form_default = $("#product_add_form").clone();
    form_edit_default = $("#product_edit_form").clone();
    $("#product_add_form").on("submit", product_form_submit);
    $("#product_edit_form").on("submit", product_form_edit_submit);
    $(".add_more").on("click", add_more_input);
    $(".btn-product-remove").on("click", function(evt){
        $that = $(this);
        $container = $that.closest(".container");
        pid = $that.attr("data-id");
        $.ajax({
            url:"product_edit.php",
            type:"post",
            data:"product_remove_submit=submit&product_id="+pid,
            success:function(d)
            {
                $container.html("");
                if(d.match(/success/))
                {
                    $container.append($("<div class='alert alert-success'>Product deleted successfully</div>"));
                }else
                {
                    $container.append($("<div class='alert alert-danger'>Product delete fail</div>"));
                }
            }
        });
    });
});
function product_form_submit(evt){
    evt.preventDefault();
    that = $(this);
    form_container = that.closest(".container");
    fdata = new FormData();
    elements = this.elements;

    //grab All the data from the form
    $.each(elements, function(i, element)
           {
        $element = $(element);
        //if element have data-ignore we will ignore it or do something else
        if($element.attr("data-ignore")!="true")
        {
            var name = $element.attr("name");
            if(name)
            {
                fdata.append(name, $element.val());
            }
        }else
        {
            if($element.hasClass("product_imgs"))
            {
                $images = $element.closest(".img_input").find(".img_container");
                $.each($images, function(i, img){
                    var img_id = $(img).attr("data-id");
                    var file = element.files[img_id];
                    fdata.append("product_images[]", file);
                });
            }
        }
    });

    var XHR = new XMLHttpRequest();
    var form_cloned = that.clone();
    XHR.onloadstart = function(evt)
    {
        form_container.html("please wait..");
    }
    XHR.onload = function(evt)
    {
        form_container.html("");
        console.log(evt.target.response);
        var response_text = evt.target.response
        if(response_text.match(/success/))
        {
            var alertbox = $("<div class='alert alert-success'/>");
            alertbox.html("Product Added Successfully");
            form_container.append(alertbox);
            //                    form_container.append($("<br/><a class='btn btn-primary' href='product_add.php'>Add Another</a>"));
            form_container.append(form_default);
            form_default = form_default.clone();
        }else
        {
            var alertbox = $("<div class='alert alert-danger'/>");
            alertbox.html("Product Not Added");
            form_container.append(alertbox)
            form_container.append(form_cloned);
        }

        $("#product_add_form").on("submit", product_form_submit);
        $(".add_more").on("click", add_more_input);
    }
    XHR.onloadend = function(evt)
    {

    }
    XHR.open("POST", "product_add.php");
    XHR.send(fdata);

}

function product_form_edit_submit(evt)
{
    evt.preventDefault();
    that = $(this);
    form_container = that.closest(".container");
    fdata = new FormData();
    elements = this.elements;
    //grab All the data from the form
    $.each(elements, function(i, element)
           {
        $element = $(element);
        if($element.attr("data-ignore")!="true")
        {
            var name = $element.attr("name");
            if(name)
            {
                fdata.append(name, $element.val());
            }
        }else
        {
            if($element.hasClass("img_to_remove"))
            {
                if($element[0].checked == true)
                {
                    fdata.append("image_to_remove[]", $element.attr("data-id"));
                }
            }else if($element.hasClass("product_imgs"))
            {
                $images = $element.closest(".img_input").find(".img_container");
                $.each($images, function(i, img){
                    var img_id = $(img).attr("data-id");
                    var file = element.files[img_id];
                    fdata.append("product_images[]", file);
                });
            }
        }
    });



    var XHR = new XMLHttpRequest();
    XHR.onloadstart = function(evt)
    {
        form_container.html("Please wait");
    }
    XHR.onload = function(evt)
    {
        form_container.html("");
        response = evt.target.response;
        console.log(response);
        var check_updated = response.match(/:update-([\w]+):?/);
        var check_saved = response.match(/:images-saved-([\w]+)/);
        var check_removed = response.match(/:images-removed-([a-z]+)?/);
        if(check_updated)
        {
            if(check_updated[1] == "successfull")
            {
                form_container.append($("<div class='alert alert-success'><span class='glyphicon glyphicon-edit'></span> Fields updated successfully</div>"));
            }else
            {
                form_container.append($("<div class='alert alert-danger'><span class='glyphicon glyphicon-edit'></span> Fields not updated</div>"));
            }

        }

        if(check_saved)
        {
            if(check_saved[1] == "successfull")
            {
                check_saved_count = response.match(/images-saved-successfull-\/(.+)\/:?/);
                check_saved_count = check_saved_count[1];
                form_container.append($("<div class='alert alert-success'><span class='glyphicon glyphicon-plus'></span> New "+check_saved_count+" images saved</div>"));
            }else
            {
                form_container.append($("<div class='alert alert-danger'><span class='glyphicon glyphicon-plus'></span> New images insertion failed</div>"));
            }
        }

        if(check_removed)
        {
            if(check_removed[1] == "successfull")
            {
                check_removed_count = response.match(/images-removed-successfull-\/(.+)\/:?/);
                check_removed_count = check_removed_count[1];
                form_container.append($("<div class='alert alert-success'><span class='glyphicon glyphicon-trash'></span>"+check_removed_count+" images removed</div>"));
            }else
            {
                form_container.append($("<div class='alert alert-danger'><span class='glyphicon glyphicon-trash'></span> images removed failed</div>"));
            }
        }
    }
    XHR.onloadend = function(evt)
    {

    }
    XHR.open("POST", "product_edit.php");
    XHR.send(fdata);
}



function add_more_input(evt){
    evt.preventDefault();
    var $btn = $(this);
    var $img_input = $('<div class="img_input"><div class="row"><input  data-ignore="true" type="file" class="product_imgs col-sm-2" onchange="load_images(this)" multiple /><div class="col-sm-1"><div class="close" onclick="input_remove(this)">&times;</div></div></div><div class="product_img_container"></div></div>');
    $img_input.insertBefore($btn);
}

function input_remove(obj)
{
    $(obj).closest(".img_input").remove();
}
function add_img_input_field(obj)
{
    $x = $("<hr/>");
    $obj = $(obj);
}

function load_images(obj)
{
    var $obj = $(obj);
    var $product_img_container=$obj.closest(".img_input").find(".product_img_container");
    var files = obj.files;

    $product_img_container.html("");
    var count=0;
    $.each(files, function(i, file){
        if(file.type.match(/image\//i))
        {
            if(count++%(12/(12/cols_per_row))==0)
            {
                $row = $("<div class='row'>");
                $product_img_container.append($row);
            }
            var reader = new FileReader();
            var $img_container = $("<div data-id='"+i+"' class='img_container col-md-"+(12/cols_per_row)+"'>");
            var $close = $("<div onclick='img_remove(this)' class='img_remove'>");
            $close.html("&times;");
            var $img = $("<img class='img-thumbnail img-responsive'>");
            reader.onload = (function(image){
                return function(evt)
                {
                    image.src = evt.target.result;
                }
            }($img[0]));

            $img_container.append($close);
            $img_container.append($img);
            $row.append($img_container);
            reader.readAsDataURL(file);
        }
    });
}

function img_remove(obj)
{
    var $img_container =    $(obj).closest(".img_container");
    var $img_input = $img_container.closest(".img_input");
    var img_count = $img_input.find(".img_container").length;
    if(img_count<=1)
    {
        $img_input.remove();
    }
    $img_container.remove();
}