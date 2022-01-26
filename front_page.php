<?php /* Template Name: Home Page */ ?>
<html>
	
    <head>
        <meta charset="UTF-8">
        
        <title>Brewery</title>
        
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

        <link href="<?php echo get_stylesheet_directory_uri()?>/css/styles.css" rel="stylesheet" type="text/css">
    
    </head>

    <body>

		<!-- Navigation -->
		<nav class="navbar navbar-expand-lg navbar-light bg-light shadow fixed-top">
			<div class="container">
				<a class="navbar-brand" href="#">
					Brewery
				</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			</div>
		</nav>
		<!-- End Navigation  -->

		<!-- Stores -->
		<section class="py-5 gallery-block cards-gallery" id="stores">
			<div class="container">
				<h2 class="fw-light text-center header-title">Stores</h2>
				<div class="row"><?php
                    $args = array(
                        'post_status' => 'publish',
                        'post_type' => 'brewery',
                        'orderby' => 'ID',
                        'order' => 'DESC',
                        'paged' => 1,
                        'posts_per_page' => 100
                    );

                    $pageposts = new WP_Query( $args );

                    while($pageposts->have_posts()) : $pageposts->the_post();?>
		            <div class="col-md-6 col-lg-4 store-item" data-address='<?php echo get_field("street")." ".get_field("city")." ". get_field("state")." ".get_field("country");?>' data-toggle="modal" data-target="#map-modal">
		                <div class="card border-0 transform-on-hover">
		                	<a class="lightbox" href="#">
		                		<img src="<?php echo get_stylesheet_directory_uri()?>/images/643219-gettyimages-1248993201.jpeg" alt="Card Image" class="card-img-top">
		                	</a>
		                    <div class="card-body">
		                        <h6><a href="#"><?php echo get_field("name"); ?></a></h6>
		                        <p><b>Brewery Type:</b> <?php echo get_field("brewery_type"); ?></p>
		                        <p><b>Address:</b> <?php echo get_field("street")." ".get_field("city")." ". get_field("state")." ".get_field("country")." ".get_field("postal_code");?></p>
		                    </div>
		                </div>
		            </div><?php
		            endwhile;
                    wp_reset_postdata();?>
		        </div>
			</div>
		</section>
		<!-- Stores -->

		<!-- Footer -->
		<footer class="footer text-center">
      		<div class="container">
        		<p>© 2022 Brewery, All Rights Reserved</p>
      		</div>
    	</footer>
    	<!-- End Footer -->

    	<!-- Modal -->
		<div class="modal fade" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="map-modal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Store Location</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="mapouter"><div class="gmap_canvas"><iframe height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=123 E Pike St Cynthiana Kentucky United States&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://123moviesz.nl"></a><br><style>.mapouter{position:relative;text-align:right;height:500px;}</style><a href="https://googlemapsembedcodegenerator.com"></a><style>.gmap_canvas {overflow:hidden;background:none!important;height:500px;width:100%;}</style></div></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
    	<!-- End Modal -->

    	<!-- Scripts -->
		<script src="<?php echo get_stylesheet_directory_uri()?>/js/jquery.min.js"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    	<!-- End Scripts -->

    	<script>
    		$(document).ready(function(){

    			$(".store-item").click(function(e){
    				e.preventDefault();

    				var address = $(this).attr("data-address");

    				map = '<div class="mapouter"><div class="gmap_canvas"><iframe height="500" id="gmap_canvas" src="https://maps.google.com/maps?q='+address+'&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://123moviesz.nl"></a><br><style>.mapouter{position:relative;text-align:right;height:500px;}</style><a href="https://googlemapsembedcodegenerator.com"></a><style>.gmap_canvas {overflow:hidden;background:none!important;height:500px;width:100%;}</style></div></div>';

    				$(".modal-body").html(map);
    			});
    		});
    	</script>

	</body>

</html>