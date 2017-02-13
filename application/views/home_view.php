<!-- Header -->
<?php $this->load->view('template/header.php'); ?>
<!-- End Header -->


<!-- Body -->
<header id="first">
    <div class="header-content">
        <div class="inner">
            <h1 class="cursive">Welcome to Campfire <img id="transparentLogo" src="<?php echo base_url("assets/img/ionicons_bonfire.png");?>"></h1>
            <h4>Connect with others by simply searching for events or groups</h4>
            <hr>
            <a id = 'search_events' href="<?php echo base_url(); ?>index.php/event/search" class="btn btn-primary btn-xl">Search Events</a> &nbsp;
            <a id = 'search_groups' href="<?php echo base_url(); ?>index.php/group/search" class="btn btn-primary btn-xl">Search Groups</a>
        </div>
    </div>
    <video autoplay="" loop="" class="fillWidth fadeIn wow collapse in" data-wow-delay="0.5s" poster="<?php echo base_url("assets/img/Campfire.png"); ?>" id="video-background">
        <source src="<?php echo base_url("assets/video/Campfire.mp4"); ?>" type="video/mp4">Your browser does not support the video tag. I suggest you upgrade your browser.
    </video>
</header>

<section id="about" class="bg-primary">
<div class="container">
	<div class="row">
		<div class="col-md-4 wow fadeInLeft">
			<h4>Development Team</h4>
			<img src="<?php echo base_url("assets/img/devteam.JPG"); ?>" class="img-rounded"  width="304" height="236">
		</div>
		<div class="col-md-8">
			<h2 class="wow fadeInUp">About Us</h2>
			<p class="wow fadeInUp">
				Our team believes in bringing people together who have similar interests. </br>
				We also want those people to have an opportunity to get involved in </br>
				activities based on those similar interests. This is what we believe a </br>
				campfire is all about. Campfire allows anyone, whether you are a user or </br>
				not, to view events happening in your area. </br>
			</p>
			<h3 class="wow fadeInRight">
				Registered User
			</h3>
			<p  class="wow fadeInRight">
				1. Create and join any number of groups based on your interests </br>
				2. View any events in your area </br>
				3. View any events in an area you search by zipcode or city,state </br>
				4. Create events </br>
				5. Manage group-specific events </br>
			</p>
			<h3  class="wow fadeInRight">
				Guest
			</h3>
			<p  class="wow fadeInRight">
				1. View any events in your area </br>
				2. View any events in the area you search for by zip code or city, state </br>
				3. Create events </br>
			</p>
			<h3  class="wow fadeInDown">
				Contact Us
			</h3>
			<p  class="wow fadeInDown">
				Any questions? suggestions? Contact us at <a href = "http://www.gmail.com">teamcampfirellc@gmail.com</a>.
			</p>
		</div>
	</div>
</div>
</section>

<section id="contact" class="bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h2 class="margin-top-0 wow fadeIn">Get in Touch</h2>
                <hr class="primary">
                <p>We love feedback. Fill out the form below and we'll get back to you as soon as possible.</p>
            </div>
            <div class="col-lg-10 col-lg-offset-1 text-center">
                <?php
                $this->load->helper("form");

                echo validation_errors();

                echo form_open("/home/send_email");
                ?>

                <form method="post" action="/home/send_email" class="contact-form row">
                    <div class="col-md-6">
                        <label></label>
                        <input name="name" id="name" type="text" value="<?php echo set_value('name');?>" class="form-control custom-form-control" placeholder="Name">
                    </div>
                    <div class="col-md-6">
                        <label></label>
                        <input name="email" id="email" type="text" value="<?php echo set_value('email');?>" class="form-control custom-form-control" placeholder="Email">
                    </div>
                    <div class="col-md-12">
                        <label></label>
                        <textarea name="message" id="message" class="form-control custom-form-control" rows="9" placeholder="Your message here.."><?php echo set_value('message');?></textarea>
                    </div>
                    <div class="col-md-4 col-md-offset-4">
                        <label></label>
<!--                        <button type="button" data-toggle="modal" data-target="#alertModal" class="btn btn-warning btn-block btn-lg">Send <i class="ion-android-arrow-forward"></i></button>-->
                        <input type="submit" class=" btn btn-warning btn-xl wow fadeInDown" name="submit" value="Submit"/>
                    </div>
                </form>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>


<!-- End Body -->
<script>
$(".page-scroll").on("click", function(event) {
      var $ele = $(this);
      $('html, body').stop().animate({
          scrollTop: ($($ele.attr('href')).offset().top - 60)
      }, 1450, 'easeInOutExpo');
      event.preventDefault();
});
$('#topNav').affix({
    offset: {
        top: 200
    }
});

new WOW().init();

$('.navbar-collapse ul li a').click(function() {
    /* always close responsive nav after click */
    $('.navbar-toggle:visible').click();
});

</script>

<!-- Footer -->
<?php $this->load->view('template/footer.php'); ?>
<!-- End Footer -->
