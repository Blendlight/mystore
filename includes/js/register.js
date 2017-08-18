$(function()
  {
    $form_register = $("#form_register");
    $form_register.on("submit", form_register_submit);

    function form_register_submit(evt){
        evt.preventDefault();
        $this = $(this);

        $inp_name = $this.find("[name=name]");
        $inp_user_name = $this.find("[name=user_name]");
        $inp_email = $this.find("[name=email]");
        $inp_pass = $this.find("[name=password]");
        $inp_repass = $this.find("[name=repassword]");

        var name = $inp_name.val();
        var user_name = $inp_user_name.val();
        var email = $inp_email.val();
        var pass = $inp_pass.val();
        var repass = $inp_repass.val();

        //        console.log($inp_name, $inp_user_name, $inp_email, $inp_pass, $inp_repass);


        //check for user_name

        $.ajax({
            url:"get_data_ajax.php",
            type:"post",
            data:"get_data=check_user_registration&uname="+user_name+"&email="+email,
            success:function(data_return)
            {
                result = JSON.parse(data_return);
                console.log(result);
                success = true;

                name_exists = result[0];
                email_exists = result[1];

                if(name_exists=="true")
                {
                    $inp_user_name.siblings(".result").html("This User name already exists");
                    success = false;
                }else
                {
                    $inp_user_name.siblings(".result").html("");
                }

                if(email_exists=="true")
                {
                    $inp_email.siblings(".result").html("Another account is register to this email");
                    success = false;

                }else
                {
                    $inp_email.siblings(".result").html("");
                }

                if(pass !== repass)
                {
                    $inp_repass.siblings(".result").html("Password doesen't matched");
                    success = false;
                }else
                {
                    $inp_repass.siblings(".result").html("");
                }

                if(success)
                {
                    $.ajax(
                        {
                            url:"register.php",
                            type:"post",
                            data:"submit=submit&register=register&name="+name+"&uname="+user_name+"&email="+email+"&pass="+pass,
                            success:function(data_return)
                            {
                                console.log(data_return);
                                if(data_return.match(/success:/))
                                {
                                    window.location = "./";
                                }else{
                                    alert("registration failed");
                                }
                            }
                        });
                }

            }
        });

    }
});