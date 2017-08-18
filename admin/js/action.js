$(function()
  {
    $result = $("#result");
});

function add_alert(text, style="info")
{
    return "<div class=\"row\"><div class='col-md-6 col-md-offset-4 alert alert-"+style+"'>"+text+"<span class='close' data-dismiss='alert'>&times;</span></div></div>";
}

function add_category(obj, act="add", catid='0')
{

    $obj = $(obj);
    $form = $obj.closest("form");
    $name = $form.find("input[name=name]");
    $description = $form.find("[name=description]");
    $parent = $form.find("[name=parent]");

    name = $name.val();
    description = $description.val();
    parent = $parent.val();
    //    parent = parent=="--null--"?null:parent;

    if($name.val()=="")
    {
        $result.html(add_alert("Please insert the name.", "danger"));
        return;
    }
    $obj.attr("disabled", "disabled");
    $.ajax(
        {
            url:window.location,
            method:"post",
            data:"submit=submit&catid="+(catid)+"&name="+name+"&description="+description+"&parent="+parent,
            success:function(response)
            {
                var pos = response.indexOf(":");
                state = response.substr(0,pos);
                console.log(response);
                if(state.match("failed"))
                {
                    console.log("failed");
                }
                msg = "";
                if(pos>=0)
                {
                    msg = response.substr(pos+1);
                }else
                {
                    msg = response;
                }
                if(state.match("failed"))
                {
                    $result.html(add_alert(msg, "danger"));
                }else if(state.match("success"))
                {
                    if(act=="edit")
                    {   
                        $result.html(add_alert("Category Edited successfully", "success"));
                    }else
                    {
                        $result.html(add_alert("The category "+name+" is added successfuly", "success"));
                        $name.val('');
                        $description.val('');
                        $parent.val('null');
                    }
                }else
                {
                    $result.html(msg);
                }

                $obj.removeAttr("disabled");
            },
            error:function()
            {
                $result.html(add_alert("Can't send the request", "danger"));
                $obj.removeAttr("disabled");
            }
        });
}
