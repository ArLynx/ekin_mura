<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Awan Tengah Studio">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<title><?php echo get_config_item('app_title'); ?></title>

	<!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">

	<style>
	
		body {
			color: #000;
			overflow-x: hidden;
			height: 100%;
			background-color: #B0BEC5;
			background-size: cover;
			background-image: url(<?php echo base_url('assets/img/Tugu_Bundaran_Emas.jpg'); ?>);
			font-family: 'Lato', sans-serif;
		}

		[x-cloak] {
            display: none !important;
        }

		input, textarea, select {
			margin-top: 0 !important;
		}

		.card0 {
			box-shadow: 0px 4px 8px 0px #757575;
			border-radius: 10px;
			opacity: .9;
		}

		.card2 {
			margin: 0px 10px 0px 30px;
		}

		.logo {
			width: 75px;
		}

		.image {
			width: 650px;
			height: auto;
		}

		.border-line {
			border-right: 1px solid #EEEEEE
		}

		.facebook {
			background-color: #3b5998;
			color: #fff;
			font-size: 18px;
			padding-top: 5px;
			border-radius: 50%;
			width: 35px;
			height: 35px;
			cursor: pointer
		}

		.twitter {
			background-color: #1DA1F2;
			color: #fff;
			font-size: 18px;
			padding-top: 5px;
			border-radius: 50%;
			width: 35px;
			height: 35px;
			cursor: pointer
		}

		.linkedin {
			background-color: #2867B2;
			color: #fff;
			font-size: 18px;
			padding-top: 5px;
			border-radius: 50%;
			width: 35px;
			height: 35px;
			cursor: pointer
		}

		.line {
			height: 1px;
			width: 15%;
			background-color: #E0E0E0;
			margin-top: 10px
		}

		.or {
			font-size: 90%;
			font-weight: bold;
		}

		.text-sm {
			font-size: 14px !important;
		}

		::placeholder {
			color: #888;
			opacity: 1;
			font-weight: 300
		}

		:-ms-input-placeholder {
			color: #888;
			font-weight: 300
		}

		::-ms-input-placeholder {
			color: #888;
			font-weight: 300
		}

		label h6 {
			letter-spacing: 2px;
		}

		input,
		textarea,
        select {
			padding: 10px 12px 10px 12px;
			border: 2px solid lightgrey;
			border-radius: 2px;
			margin-bottom: 5px;
			margin-top: 2px;
			width: 100%;
			box-sizing: border-box;
			color: #888;
			font-size: 14px;
			letter-spacing: 1px
		}

		input:focus,
		textarea:focus,
        select:focus {
			-moz-box-shadow: none !important;
			-webkit-box-shadow: none !important;
			box-shadow: none !important;
			border: 2px solid #888;
			outline-width: 0
		}

		button:focus {
			-moz-box-shadow: none !important;
			-webkit-box-shadow: none !important;
			box-shadow: none !important;
			outline-width: 0
		}

		a {
			color: inherit;
			cursor: pointer
		}

		.btn-green {
			background-color: #38ada9;
			/* width: 150px; */
			color: #fff;
			border-radius: 2px
		}

		.btn-green:hover {
			background-color: #000;
			color: #fff;
			cursor: pointer
		}

		.bg-blue {
			color: #fff;
			background-color: #1A237E
		}

        .bg-green {
            color: #fff;
            background: #38ada9;
			border-bottom-left-radius: 10px;
			border-bottom-right-radius: 10px;
        }

		@media screen and (max-width: 991.98px) {
			.logo {
				margin-left: 0px
			}

			.image {
				/* width: 300px; */
				/* height: 220px */
			}

			.border-line {
				border-right: none
			}

            .card1 {
                display: none;
            }

			.card2 {
				border-top: transparent !important;
				margin: 0px 15px;
			}

            .container-fluid {
                padding-right: 1em !important;
                padding-left: 1em !important;
            }
        }

	</style>
</head>

<body>
	<?php echo $_main_content; ?>

	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
		integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
	</script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
		integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
	</script>
	<script src="//unpkg.com/alpinejs" defer></script>
	<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>">
	</script>
</body>

</html>
