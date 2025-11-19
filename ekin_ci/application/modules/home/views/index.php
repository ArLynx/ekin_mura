<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>E-kin</title>
	<meta content="" name="description">

	<meta content="" name="keywords">

	<!-- Favicons -->
	<script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
	<!-- Google fonts-->
	<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />


	<!-- Vendor CSS Files -->

	<link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet" />
	<!-- Template Main CSS File -->
	<style>
	
.page-top{
	margin-top:20%;
	margin-bottom:300px
}
		.contact-section {
			padding: 1%;
		}

		.footer {
			padding: 0.5%;
		}

		.card {
			height: 245px;
		}
		.text-dark{
			color:#475f7b;
		}
		h1{
			font-family: "Lucida Console", "Courier New", monospace; !important;  
		}
		.mx-auto.text-center h1,
    .mx-auto.text-center h2,
    .mx-auto.text-center a {
        text-shadow: 1px 1px 1px #ededed;
    }
	</style>
	<script>
		var base_url = "<?php echo base_url(); ?>";
	</script>
</head>


<body class="page-top"></body>
	<!-- Navigation-->
	<nav class="fixed-top" >
		<div class="container">
		<?php echo alert_message(); ?>
					<?php echo alert_validation(); ?>


		</div>
	</nav>
	<!-- Masthead-->
	<header class="masthead">
	<div class="card" style="width: 35%; height:20rem; margin:auto; background-color: rgba(255, 255, 255, 0.7);">
    <div class="container d-flex h-100 align-items-center">
        <div class="mx-auto text-center">
            <h1 class="mx-auto my-0 text-uppercase" style="color:#475f7b; font-size: 5.0rem;">E-Kinerja</h1>
            <h2 class="mx-auto mt-2 mb-3" style="color:#475f7b; font-size: 1.3rem; font-style:italic">Kabupaten Murung Raya</h2>
            <a class="btn btn-info mt-3" href="#myModal" data-toggle="modal">Login</a>
        </div>
    </div>
</div>

	</header>


	<!-- Bootstrap core JS-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
	<!-- Third party plugin JS-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
	<!-- Core theme JS-->
	<script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
	<script src="<?php echo base_url(); ?>assets/template_login/vendor/jquery/jquery-3.5.1.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/template_login/vendor/bootstrap4/js/bootstrap.min.js"></script>
	<!-- Vendor JS Files -->
	<script src="<?php echo base_url(); ?>assets/template_login/vendor/bootstrap/js/bootstrap.bundle.js"></script>
	<script src="<?php echo base_url(); ?>assets/template_login/vendor/aos/aos.js"></script>

	<!-- Template Main JS File -->
	<script src="<?php echo base_url(); ?>assets/template_login/js/main.js"></script>
	<script src="<?php echo base_url(); ?>assets/template_admin/js/HoldOn.min.js"></script>
	
	<div id="myModal" class="modal fade">
		<div class="modal-dialog modal-login">
			<div class="modal-content">
				<?php echo form_open(); ?>
				<div class="modal-header">
					<h4 class="modal-title">Login</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<span class='error_field'></span>
					<!-- <form class="form-horizontal" action="<?php echo base_url('login/act_login') ?>" method="post" enctype="multipart/form-data" role="form"> -->

					<div class="form-group">
						<label>Username</label>
							<input class="mb-4 form-control" type="text" name="username" placeholder="Username"
							value="<?php echo set_value('username'); ?>">
					</div>
					<div class="form-group">
						<div class="clearfix">
							<label>Password</label>
						</div>
						

						<input type="password" class="form-control" required="required" placeholder="Password" name="password">
					</div>
					<div class="form-group">
						<label>Akses Login</label>
							<select name="akses_login" class="form-control">
							<option value="1">PNS / PPPK</option>
							<option value="2">KEPEGAWAIAN</option>
						</select>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<input type="submit" class="btn-sm btn-info" value="Login" onclick="check_auth()">
				</div>
				<?php echo form_close(); ?>
				<!-- </form> -->
			</div>
		</div>
	</div>

</body>

</html>

<script>
	let optionsHoldOn = {
		theme: "sk-cube-grid",
		message: 'Loading',
		textColor: "white"
	};

	function check_auth() {
		let username = $("input[name='username']").val();
		let password = $("input[name='password']").val();
		$(".error_field").html("");

		if (!username) {
			$(".error_field").html("<div class='alert alert-danger'>Field username tidak boleh kosong</div>");
		} else if (!password) {
			$(".error_field").html("<div class='alert alert-danger'>Field Password tidak boleh kosong</div>");
		} else {
			$.ajax({
				url: base_url + 'login/act_login',
				data: {
					username: username,
					password: password
				},
				type: 'POST',
				beforeSend: function() {
					HoldOn.open(optionsHoldOn);
				},
				success: function(response) {
					if (response == true) {
						$(".error_field").html("<div class='alert alert-success'>Berhasil...</div>");
						location.reload();
					} else {
						$(".error_field").html("<div class='alert alert-danger'>Username dan Password salah</div>");
					}
				},
				complete: function() {
					HoldOn.close();
				}
			});
		}
	}

	$(function() {
		$('#myModal').on('keypress', function(event) {
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if (keycode == '13') {
				check_auth();
			}
		});
	})
</script>