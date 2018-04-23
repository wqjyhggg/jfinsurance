<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>JF Insurance Agency Group Inc.</title>
<!-- Font Awesome -->
<link rel="stylesheet" href="<?php echo base_url();?>home/css/font-awesome.min.css">

<!-- Bootstrap core CSS -->
<link href="<?php echo base_url();?>home/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>home/css/owl.carousel.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
<link href="<?php echo base_url();?>home/css/fontawesome-all.min.css" rel="stylesheet">
<!-- Material Design Bootstrap -->
<link href="<?php echo base_url();?>home/css/mdb.min.css" rel="stylesheet">
<!-- Your custom styles (optional) -->
<link href="<?php echo base_url();?>home/css/aos.css" rel="stylesheet">
<link href="<?php echo base_url();?>home/css/style.css" rel="stylesheet">
<!-- responsive.css -->
<link href="<?php echo base_url();?>home/css/responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>home/images/fav.ico" rel="shortcut icon" />

</head>

<body>
	<!---------------------------Header Started---------------------------------->
	<header>
		<section data-aos="fade-right" class="section-block"
			id="brand-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center pt-3 pb-2">
						<a class="navbar-brand" href="<?php echo base_url();?>"><img src="<?php echo base_url();?>home/images/logo.png" alt="Logo" class="logo-image"></a>
					</div>
				</div>
			</div>
		</section>
		<section class="section-block" id="navbar-wrapper">
			<div class="container-full">
				<div class="row">
					<div class="col-md-6">
						<nav class="navbar navbar-expand-lg navbar-light bg-light">
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>

							<div class="collapse navbar-collapse" id="navbarSupportedContent">
								<ul class="navbar-nav mr-auto">
									<li class="nav-item active"><a class="nav-link" href="<?php echo base_url();?>"><?php echo $lang['text_home']; ?> <span class="sr-only">(current)</span></a>
									</li>
									<li class="nav-item"><a class="nav-link" href="<?php echo base_url("user/login");?>">Agent Login</a></li>

									<li class="nav-item"><a class="nav-link border-menu" href="<?php echo base_url("user/login");?>">Buy Product</a></li>
								</ul>
							</div>
						</nav>
					</div>
					<div class="col-md-6">
						<ul class="header-dropdown-block pull-right">
							<!-- li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="language-image"><i class="fas fa-map-marker-alt"></i>Location</span>
								</a>
								<div class="dropdown-menu box-shadow bg-white-text animated fadeInUp" aria-labelledby="navbarDropdown">
								  <a class="dropdown-item" href="#">Action</a>
								  <a class="dropdown-item" href="#">Another action</a>
								  <a class="dropdown-item" href="#">Another action</a>
								  <a class="dropdown-item" href="#">Another action</a>
								</div>
							</li -->
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="language-image"><i class="fas fa-cog"></i>Language</span> </a>
								<div
									class="dropdown-menu box-shadow bg-white-text animated fadeInUp" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="<?php echo base_url();?>lang/english"><span class="lang-img"><img src="<?php echo base_url();?>home/images/icon_flag_1.png"></span><span class="lang-name"><?php echo $lang['txt_english']?></span></a>
									<a class="dropdown-item" href="<?php echo base_url();?>lang/chinese"><span class="lang-img"><img src="<?php echo base_url();?>home/images/icon_flag_2.png"></span><span class="lang-name"><?php echo $lang['txt_chinese']?></span></a>
									<a class="dropdown-item" href="<?php echo base_url();?>lang/korean"><span class="lang-img"><img src="<?php echo base_url();?>home/images/icon_flag_3.png"></span><span class="lang-name"><?php echo $lang['txt_korean']?></span></a>
									<a class="dropdown-item" href="<?php echo base_url();?>lang/japanese"><span class="lang-img"><img src="<?php echo base_url();?>home/images/icon_flag_4.png"></span><span class="lang-name"><?php echo $lang['txt_japanese']?></span></a>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- banner slider ( used slider for single image so in feature you may add more images without hesitate)-->
		<section class="section-block" id="navbar-wrapper">
			<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					<div class="carousel-item active">
						<img class="d-block w-100" src="<?php echo base_url();?>home/images/banner_1.png" alt="First slide">
						<div class="carousel-caption pt-5" data-aos="fade-down">
							<div class="title-image">
								<img src="<?php echo base_url();?>home/images/title.png" class="h1-main-image">
							</div>
							<div class="clearfix"></div>
							<div class="p-3">
								<p class="white-text mb-2">We don't like to think about it, but sudden, unexpected accidents or illnesses do happen, and trying to find an pay for adequate medical attention can be difficult when you are abroad.</p>
								<p class="white-text mb-3">Health care costs around the world can be very expensive. Hospital can charge thousands of dollars per day. Your health plan may or may not cover a minute protion of these cost. Without adequate insurance coverage you could be responsible from dollar one, which could create a massive impact on your personal finances. Why take the risk?</p>
								<div class="banner-btn">
									<button type="button" onclick="location.href='<?php echo base_url("user/login");?>';" class="btn btn-primary btn-white text-uppercase btn-auto">GET A Quote</button>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</section>
	</header>
	<!---------------------------Header End----------------------------------->
	<!---------------------------App Download Portion ----------------------------------->
	<section class="section-block bg-green py-2" id="app-wrapper">
		<div class="custom-container">
			<div class="row">
				<div class="col-md-6 ">
					<div class="app-content" data-aos="fade-right">
						<h2 class="text-uppercase white-text mb-4">JF Insurance app download</h2>
						<div class="btn-group jf-btn-group text-center my-1 pl-5">
							<button type="button" class="btn btn-primary btn-white btn-auto">iOS</button>
							<button type="button" class="btn  btn-primary btn-white btn-auto">Android</button>
						</div>
					</div>
				</div>
				<div class="col-md-6 text-right" data-aos="fade-left">
					<div class="app-image">
						<img src="<?php echo base_url();?>home/images/img11.png" class="img-fluid">
					</div>
				</div>
			</div>
		</div>
	</section>
	<!---------------------------About Us---------------------------------->
	<section class="section-block py-5 my-5" id="abt-wrapper"
		data-aos="fade-up">
		<div class="custom-container">
			<div class="row">
				<div class="col-md-3">
					<img src="<?php echo base_url();?>home/images/about1.png" class="abt-img img-fluid" alt="about image">
				</div>
				<div class="col-md-9">
					<h1 class="border-title mt-4 mb-2 ">
						<span class="border-green-bottom green-text text-uppercase">About Us</span> <span class="border-sub-title">We take care of you</span>
					</h1>
					<p>JF Insurance Agency Group Inc. (JF) is a licensed brokerage firm incorporated in 1992. We are the leading private firm in providing Emergency Hospital and Medical coverage for Canadians, visitors across Canada and International students. We are recognized for our dedication to serve our clients on both an individual basis and association groups.</p>
				</div>
			</div>
		</div>
	</section>
	<!---------------------------Zig Zag Portion ----------------------------------->
	<section class="section-block mb-5" id="zigzag-wrapper">
		<div class="row">
			<div class="col-md-6 p-0" data-aos="fade-right">
				<div class="zig-image zig-block">
					<img src="<?php echo base_url();?>home/images/img3.png" class="img-fluid">
				</div>
			</div>
			<div class="col-md-6 p-0" data-aos="fade-left">
				<div class="zig-content zig-block">
					<div class="box-outer">
						<div class="outer">
							<div class="middle">
								<div class="inner zig-padding">
									<div class="row justify-content-center">
										<div class="col-xl-6 col-lg-9 col-md-11  col-xs-12">
											<h3 class="green-text mb-3">Founder</h3>
											<p>The founder Mr. Johnson Fu has more than 30 plus years of experience in the Insurance and Financial Industry. Mr. Fu is also very active among many communities and charity events. It is through his involvement in community service that the JF corporate philosophy “To Serve” has come to fruition. This philosophy emphasizes the attitude of all staff members at JF.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 p-0" data-aos="fade-right">
				<div class="zig-content zig-block">
					<div class="box-outer">
						<div class="outer  bg-green ">
							<div class="middle">
								<div class="inner zig-padding">
									<div class="row justify-content-center">
										<div class="col-xl-6 col-lg-9 col-md-11  col-xs-12">
											<h3 class="white-text mb-3">Mission</h3>
											<p class="white-text">To reach all that can benefit from the peace of mind we offer; to ensure claims are processed in a timely manner.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 p-0" data-aos="fade-left">
				<div class="zig-image zig-block">
					<img src="<?php echo base_url();?>home/images/img4.png" class="img-fluid">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 p-0" data-aos="fade-right">
				<div class="zig-image zig-block">
					<img src="<?php echo base_url();?>home/images/img5.png" class="img-fluid">
				</div>
			</div>
			<div class="col-md-6 p-0" data-aos="fade-left">
				<div class="zig-content zig-block">
					<div class="box-outer">
						<div class="outer">
							<div class="middle">
								<div class="inner zig-padding">
									<div class="row justify-content-center">
										<div class="col-xl-6 col-lg-9 col-md-11  col-xs-12">
											<h3 class="green-text mb-3">Vision</h3>
											<p>To reach all that can benefit from the peace of mind we offer; to ensure claims are processed in a timely manner.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!---------------------------Offer Portion ----------------------------------->
	<section class="section-block py-5 mb-5 bg-gray" id="offer-wrapper"
		data-aos="fade-up">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="double-title text-center">
						<h1 class="green-text m-0 pb-1 text-uppercase">We Offer</h1>
						<p>We take care of you</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="offer-block my-4 p-3 bg-green">
						<div class="offer-img">
							<img src="<?php echo base_url();?>home/images/img6.png" class="img-fluid">
						</div>
						<h2 class="white-text m-0 pt-3 pb-2">Visitor to Canada</h2>
						<p class="white-text">Health care costs in Canada can be expensive if you’re not covered by a Canadian government health insurance plan. Therefore, be sure to carry visitor insurance to protect your finances and enjoy a secured stay while you are away from your home country.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="offer-block my-4 p-3 bg-green">
						<div class="offer-img">
							<img src="<?php echo base_url();?>home/images/img7.png" class="img-fluid">
						</div>
						<h2 class="white-text m-0 pt-3 pb-2">International Student to Canada</h2>
						<p class="white-text">Studying aboard is exciting and adventurous. Make sure you have the right insurance coverage for your journey while in Canada.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="offer-block my-4 p-3 bg-green">
						<div class="offer-img">
							<img src="<?php echo base_url();?>home/images/img8.png" class="img-fluid">
						</div>
						<h2 class="white-text m-0 pt-3 pb-2">Canadian Travel Out</h2>
						<p class="white-text">Your provincial health plan only covers a part of your health care costs incurred outside of Canada and limits coverage when travelling to another province.</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<button type="button" onclick="location.href='<?php echo base_url("user/login");?>';" class="btn btn-primary border btn-white text-uppercase btn-auto">GET A Quote</button>
				</div>
			</div>
		</div>
	</section>
	<!--------------------------- Footer Started ----------------------------------->
	<footer>
		<section class="section-block py-1" id="footer-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-12 pb-5 text-center">
						<h1 class="text-uppercase green-text">Contact us</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6" data-aos="fade-right">
						<h1 class="pb-2 mb-3 border-bottom normal-font">Toronto Office</h1>
						<div class="address-line py-1">
							<div class="row">
								<div class="col-md-2 col-lg-2 col-xl-1">
									<img src="<?php echo base_url();?>home/images/icon_location.png" class="img-fluid">
								</div>
								<div class="col-md-10 col-lg-10 col-xl-11">
									15 Wertheim Court, Suite 501 Richmond Hill, ON <br />L4B 3H7 CANADA
								</div>
							</div>
						</div>
						<div class="address-line py-1">
							<div class="row">
								<div class="col-md-2 col-lg-2 col-xl-1">
									<img src="<?php echo base_url();?>home/images/icon_fax.png" class="img-fluid">
								</div>
								<div class="col-md-10 col-lg-10 col-xl-11">
									<p>
										<span class="address-title">Phone:</span><a href="tel: 905-707-1512"> 905-707-1512</a>
									</p>
									<p>
										<span class="address-title">Fax:</span>905-707-1513
									</p>
									<p>
										<span class="address-title">Toll Free:</span><a href="tel:1-877-832-5541">1-877-832-5541</a>
									</p>
									<p>
										<span class="address-title">Toll Free Fax:</span>1-888-988-3268
									</p>

								</div>
							</div>
						</div>
						<div class="address-line py-1">
							<div class="row">
								<div class="col-md-2 col-lg-2 col-xl-1">
									<img src="<?php echo base_url();?>home/images/icon_mail.png" class="img-fluid">
								</div>
								<div class="col-md-10 col-lg-10 col-xl-11">
									<span class="address-title">Email:</span><a href="maito:info@jfgroup.ca">info@jfgroup.ca</a>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-6" data-aos="fade-left">
						<h1 class="pb-2 mb-3 border-bottom normal-font">Vancouver Office</h1>
						<div class="address-line py-1">
							<div class="row">
								<div class="col-md-2 col-lg-2 col-xl-1">
									<img src="<?php echo base_url();?>home/images/icon_location.png" class="img-fluid">
								</div>
								<div class="col-md-10 col-lg-10 col-xl-11">
									128-6061 No. 3 RoadRichmond, BC<br />V6Y 2B2 CANADA
								</div>
							</div>
						</div>
						<div class="address-line py-1">
							<div class="row">
								<div class="col-md-2 col-lg-2 col-xl-1">
									<img src="<?php echo base_url();?>home/images/icon_fax.png" class="img-fluid">
								</div>
								<div class="col-md-10 col-lg-10 col-xl-11">
									<p>
										<span class="address-title">Phone:</span><a href="tel:604-232-0896">604-232-0896</a>
									</p>
									<p>
										<span class="address-title">Fax:</span>604-232-0897
									</p>
									<p>
										<span class="address-title">Toll Free:</span><a href="tel:1-877-232-0896">1-877-232-0896</a>
									</p>
									<p>
										<br />
									</p>
								</div>
							</div>
						</div>
						<div class="address-line py-1">
							<div class="row">
								<div class="col-md-2 col-lg-2 col-xl-1">
									<img src="<?php echo base_url();?>home/images/icon_mail.png" class="img-fluid">
								</div>
								<div class="col-md-10 col-lg-10 col-xl-11">
									<span class="address-title">Email:</span><a href="maito:vancouver@jfuinsurance.com">vancouver@jfuinsurance.com</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--------------------------- Copyright Block Started ----------------------------------->
		<section class="section-block bg-green mt-3" id="copyright-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						<p class="white-text py-2">.Copyright © 2009-2016 JF Insurance Agency Group Inc. All rights reserved.</p>
					</div>
				</div>
			</div>
		</section>
	</footer>
	<!-- -------------------SCRIPTS ------------------->
	<!-- JQuery -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="<?php echo base_url();?>stylesheet/jquery/dist/jquery.min.js"></script>
	<!-- script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script -->

	<!-- Bootstrap tooltips -->
	<script type="text/javascript" src="<?php echo base_url();?>home/js/popper.min.js"></script>
	<!-- Bootstrap core JavaScript -->
	<script type="text/javascript" src="<?php echo base_url();?>home/js/bootstrap.min.js"></script>
	<!-- MDB core JavaScript -->
	<script type="text/javascript" src="<?php echo base_url();?>home/js/mdb.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>home/js/owl.carousel.js"></script>
	<!-- animation js -->
	<script src="<?php echo base_url();?>home/js/aos.js"></script>
	<!-- animation script -->
	<script>
            AOS.init({
                easing: 'ease-out-back',
                 // once:       true,
                 reverse:false,
                 duration: 2000
            });
   </script>
	<!-- animation script end-->
	<script>
		$('.owl-carousel').owlCarousel({
			loop:true,
			autoplay:true,
			autoplayTimeout:2000,
			autoplayHoverPause:true,
			margin:25,
			nav:true,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				1000:{
					items:6
				}
			}
		})
	</script>
	<script>
		$('.carousel').carousel({
		  interval: 2000
		})
	</script>
</body>
</html>
