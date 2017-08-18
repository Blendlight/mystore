$(function()
  {
    $country_form_result = $("#country_form_result");
    $remove_country = $(".remove_country");
    $modal_country_remove = $("#modal_remove_country");
    $modal_country_remove_body = $modal_country_remove.find(".modal-body");
    $remove_result_default = $modal_country_remove.find("#country_remove_result").clone();
    $remove_country.on("click", fn_remove_country);

    $modal_edit_country = $("#modal_edit_country");
    $country_edit_form_result = $("#country_edit_form_result");
    $edit_country_form = $("#edit_country_form");
    $country_edit_name = $("#country_edit_name");
    $country_edit_id = $("#country_edit_id");

    $btn_edit_country = $(".edit-country");

    $btn_edit_country.on("click", function(evt)
                         {
        $that = $(this);
        var $country_name = $that.closest("tr").find(".country_name");
        var country_name = $country_name.html();
        var country_id = $that.closest("tr").attr("data-id");
        $country_edit_name.val(country_name.trim());
        $country_edit_id.val(country_id);
        $country_edit_form_result.html("");
    });

    $edit_country_form.on("submit", function(evt)
                          {
        evt.preventDefault();
        $that = $(this);
        var country_name = $country_edit_name.val();
        var country_id = $country_edit_id.val();
        $.ajax(
            {
                url:"countries.php",
                type:"post",
                data:"submit=submit&action=edit_country&country_id="+country_id+"&country_name="+country_name,
                success:function(data_return)
                {
                    console.log(data_return);
                    if(data_return.match(/success:/))
                    {
                        var $alert_box = $("<div class='alert alert-success'>Country name updated<div class='close' data-dismiss='alert'>&times;</div></div>");
                        $country_edit_form_result.append($alert_box);
                    }else if(data_return.match(/fail:/))
                    {
                        var msg =  data_return.substr(data_return.indexOf(":")+1);
                        var $alert_box = $("<div class='alert alert-danger'>"+msg+"<div class='close' data-dismiss='alert'>&times;</div></div>");
                        $country_edit_form_result.append($alert_box);
                    }
                }
            }
        );
    });


    $("#new_country_form").on("submit", fn_new_country_form_submit);



    function fn_remove_country(evt)
    {
        $that = $(this);

        $modal_country_remove_body.html("");
        $modal_country_remove_body.append($remove_result_default.clone());
        $modal_country_name = $("#country_remove_name");

        $btn_country_remove = $("#btn_country_remove");
        $btn_country_remove.on("click", fn_btn_country_remove);

        $country_name = $that.closest("tr").find(".country_name");
        $btn_country_remove.attr("data-id", $that.attr("data-id"));
        $modal_country_name.html($country_name.html());


    }



    function fn_btn_country_remove(evt){
        $that = $(this);
        data_id = $that.attr("data-id");
        $modal_body = $that.closest(".modal-body");
        $modal_body.html("<div>Please wait</div>");

        $.ajax({
            url:"countries.php",
            type:"post",
            data:"submit=submit&action=remove_country&country_id="+data_id,
            success:function(return_data)
            {
                console.log(return_data);
                $countr_row = $(".country_row[data-id="+data_id+"]");
                $modal_body.html("");
                if(return_data.match(/success:/))
                {
                    $modal_body.append("<div class='alert alert-success'>Country removed successfully</div>");
                    $countr_row.remove();
                }else if(return_data.match(/fail:/))
                {
                    var msg = return_data.match(/fail:(.*)/)[1];
                    $modal_body.append("<div class='alert alert-danger'>Country removed failed</div><div>"+msg+"</div>");
                }
            }
        });
    }

    function fn_new_country_form_submit(evt)
    {
        evt.preventDefault();
        $that = $(this);
        $country_name = $that.find("input[name=country_name]");
        country_name = $country_name.val();

        if(country_name.trim()=="")
        {
            $country_name.focus();
            return;
        }



        $.ajax(
            {
                url:"countries.php",
                method:"POST",
                data:"submit=submit&action=add_country&country_name="+country_name,
                success:function(data_return)
                {
                    console.log(data_return);
                    if(data_return.match(/success:/))
                    {
                        var $alert_box = $("<div class='alert alert-success'>"+$country_name.val()+" Added successfully<div class='close' data-dismiss='alert'>&times;</div></div>");
                        $country_form_result.append($alert_box);
                    }else if(data_return.match(/fail:/))
                    {
                        var msg =  data_return.substr(data_return.indexOf(":")+1);
                        var $alert_box = $("<div class='alert alert-danger'>"+msg+"<div class='close' data-dismiss='alert'>&times;</div></div>");
                        $country_form_result.append($alert_box);
                    }
                }
            });
    }

});