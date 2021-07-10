<?php

//login.php

include('header_new.php');

include('class/Appointment.php');

$object = new Appointment;

?>
<link rel="stylesheet" type="text/css" href="css/login.css">

<?php
			if(isset($_SESSION["success_message"]))
			{
				echo $_SESSION["success_message"];
				unset($_SESSION["success_message"]);
			}
			?>

<div class="container-fluid">
  <div class="row no-gutter">
    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
    <div class="col-md-8 col-lg-6">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
              <h3 class="login-heading mb-4 text-center">Patient Login</h3>
              <form method="post" id="patient_login_form">
                <div class="form-label-group">
                  <input type="text" name="patient_email_address" id="patient_email_address" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" />
                  <label for="inputEmail">Email address</label>
                </div>

                <div class="form-label-group">
                  <input type="password" name="patient_password" id="patient_password" class="form-control" required  data-parsley-trigger="keyup" />
                  <label for="inputPassword">Password</label>
                </div>

                <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit" name="patient_login_button" id="patient_login_button" value="login" style="background-color: #a6ca63; border-color: #a6ca63; lighting-color: #a6ca63; outline-color: #a6ca63; flood-color: #a6ca63;">
                					<input type="hidden" name="action" value="patient_login" />Login</button>
                <div class="text-center">
                  <a class="small" href="register.php" style="color: #a6ca63;">Didn't have account? Register here.</a></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php

include('footer.php');

?>


<script>

$(document).ready(function(){

	$('#patient_login_form').parsley();

	$('#patient_login_form').on('submit', function(event){

		event.preventDefault();

		if($('#patient_login_form').parsley().isValid())
		{
			$.ajax({

				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:"json",
				beforeSend:function()
				{
					$('#patient_login_button').attr('disabled', 'disabled');
				},
				success:function(data)
				{
					$('#patient_login_button').attr('disabled', false);

					if(data.error != '')
					{
						$('#message').html(data.error);
					}
					else
					{
						window.location.href="dashboard.php";
					}
				}
			});
		}

	});

});



</script>