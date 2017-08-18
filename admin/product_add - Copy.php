<?php
$page_title = "Add Product";
include_once("header.php");
include_once("includes/functions.php");
include_once("../includes/php/conx.php");
?>
<style>
    .product_img_parent
    {
        position: relative;
    }
    .cancel
    {
        position: absolute;
        top: 0;
        right: 20px;
        opacity: .6;    
        padding: 0 4px;
        background: white;
        border-radius: 50%;
        border:1px solid black;
        font-size: 21px;
        font-weight: 700;
        cursor:pointer;
        line-height: 1;
    }
    .cancel:hover
    {
        opacity: 1;
    }
</style>

<div class="jumbotron">
</div>
<div class="container">
    <h2>Add new Product</h2>
    <form class="form" onsubmit="return false;" method="post" action="product_save.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product_name">Name</label>
            <input type="text" name="product_name" class="form-control" placeholder="Product Name" id="product_name">
        </div>
        <div class="form-group">
            <label for="product_description">description</label>
            <textarea type="text" name="product_description" class="form-control" placeholder="papalaceholder" id="product_description">
            </textarea>
        </div>
        <div class="row">
            <div class="col-sm-4 form-group">
                <label for="product_category">Category</label>
                <SELECT type="text" name="product_category" class="form-control" id="product_category">
                    <option value="null">--PRODUCT CATEGORY--</option>
                    <?php get_category_select_options($conx);?>
                </SELECT>
            </div>
            <div class="col-sm-4 form-group">
                <label for="product_price">Price</label>
                <input type="number" name="product_price" class="form-control" placeholder="papalaceholder" id="product_price">
            </div>
            <div class="col-sm-4">
                <label for="product_stock">Stock</label>
                <input type="number" name="product_stock" class="form-control" placeholder="papalaceholder" id="product_stock">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="product_date">date</label>
                <input type="date" name="product_date" class="form-control" placeholder="papalaceholder" id="product_date">
            </div>
            <div class="col-md-4 form-group">
                <label for="product_country">Country</label>
                <input type="text" name="product_country" class="form-control" placeholder="papalaceholder" id="product_country">
            </div>
            <div class="col-md-4">

            </div>
        </div>
        <div>
            <input type="file" id="input_images" name="product_images" multiple/>
            <div id="prouduct_images_container"></div>
        </div>
        <input name="submit" class="btn btn-primary" type="submit" value="Add">
    </form>
</div>

<script>
    product_images = new Array();
    $p_images_container = $("#prouduct_images_container");
    $input_img = $("#input_images");
    $(function(){
        $input_img.on("change", function()
                      {
            for(i=0;i<this.files.length;i++)
            {
                var file = this.files[i];
                product_images.push(new Array(file, false));
            }

            update_images();
        });

    });

    function update_images(from_begining=false)
    {
        if(from_begining)
        {
            $p_images_container.html("");
        }
        product_images.forEach(function(product_image, index)
                               {
            var elements_per_row = 4;
            if(product_image!=null && (product_image[1] != true || from_begining))
            {
                //bs-col-per-row / bs-col-per-row/elements_per_row
                if(index%( 12 / ( 12 / elements_per_row) )==0)
                {
                    $row = $("<div class='row'>");
                    $p_images_container.append($row);
                }
                var col = $("<div class='product_img_parent col-md-"+(12/elements_per_row)+" col-sm-4'>");
                var cancel = $("<div data-target='"+index+"' class='cancel' onclick='delete_img(this)'>");
                cancel.html("&times;");
                var img  = $("<img id='"+index+"' class='img-thumbnail img-responsive' onclick='show_img(this)'/>")[0];
                col.append(img);
                col.append(cancel);
                $row.append(col);

                //now read the file
                var reader = new FileReader();
                reader.onload = (function(image){
                    return function(evt)
                    {
                        image.src = evt.target.result;
                    }
                }(img));

                reader.readAsDataURL(product_image[0]);
                product_image[1] = true;
            }
        })
    }

    function delete_img(obj)
    {
        $obj = $(obj);
        var ind = $obj.attr("data-target");
        product_images[ind] = null;
        $obj.closest(".product_img_parent").remove();
        //        update_images(true);
    }

    $("form").on("submit", function(){
        fdata = new FormData();
        form = this;
        $form = $(this);
        //remove the files input we will use our image selector
        $form.find("input[type=file]").remove();

        Array.prototype.forEach.call(form.elements, function(el){
            $el = $(el);
            if($el.attr("name"))
            {
                fdata.append($el.attr("name"), $el.val());
                //                console.log($el.attr("name"));
            }
        });

        product_images.forEach(function(p_img){
            //            console.log("hello")
            if(p_img!=null){

                fdata.append("product_images[]", p_img[0]); 
                //                console.log(p_img[0]);
            }
        });

        nxr = new XMLHttpRequest();
        nxr.onload = function(evt)
        {
            res = evt.target.responseText;
            console.log(res);
        }
        nxr.open("post","product_save.php");
        nxr.send(fdata);

        //        $.ajax({
        //            url:"product_save.php",
        //            method:"post",
        //            data: "s",
        //            success:function(e)
        //            {
        //                console.log(e);
        //                a = e;
        //            },
        //            error:function(e)
        //            {
        //                console.log("failed");
        //                console.log(e);
        //            }
        //        })
    })


</script>
<?php
include_once("footer.php");
?>