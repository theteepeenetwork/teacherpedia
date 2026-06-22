<style>
	#search-bar-home {
		width: 90%;
		max-width: 700px;
		margin: auto;
	}

	#search-button-home {
		width: 150px;
		;
		margin: auto;
		margin-top: 10px;

	}

	.featurette-divider {
		margin-top: 50px;
		margin-bottom: 50px;
	}

	.carousel {
		min-height: 500px;
		max-height: 500px;
	}

	.carousel a {
		color: white;
	}

	.background {
		min-height: 500px;
		max-height: 500px;
		height: 100vw;
		width: 100vw;
	}

	.background img {

		width: 50vw;
		max-width: 300px;
		margin: auto;
	}

	#slide1 {
		background-color: var(--theme-3);
	}

	#slide2 {
		background-color: var(--theme-1);
	}

	#slide3 {
		background-color: var(--theme-2);
	}
</style>

<br />


<!-- Carousel -->

<div class="bd-example">
	<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
		<ol class="carousel-indicators">
			<li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
			<li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
			<li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
		</ol>
		<div class="carousel-inner">
			<div class="carousel-item active">
				<div class="background" id="slide1">
					<div class="carousel-caption d-block">
						<img src="/assets/img/gallery/boy1.png" alt="teacherpedia mascot boy1">
						<br />
						<h5>Welcome to Teacherpedia</h5>
						<p>We're brand new and just getting started!</p>
					</div>
				</div>
			</div>
			<div class="carousel-item">
				<div class="background" id="slide2">
					<div class="carousel-caption d-block">
						<img src="/assets/img/gallery/girl1.png" alt="teacherpedia mascot girl1">
						<br />
						<h5>Our resources are automatically generated!</h5>
						<p>Every resources is customisable so you can differentiate-to-need quickly and easily.</p>
					</div>
				</div>
			</div>
			<div class="carousel-item">
				<div class="background" id="slide3">
					<div class="carousel-caption d-block">
						<a href="<?php echo $feature->link . '/' . $feature->id;
									?>"><img class="" src="<?php echo $feature->resource_thumb
															?>">
							<br />
							<h5>Try our <?php echo $feature->resource_name
										?></h5>
							<p><?php echo $feature->resource_excerpt
								?></p>
						</a>
					</div>
				</div>
			</div>
		</div>
		<a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
</div>

<!-- End Carousel -->

<hr class="featurette-divider">


<form id="" class="form" method="post" role="search" action="/resources/search/results">
	<input id="search-bar-home" name="search_input" type=" text" name="search" class="form-control" placeholder="Let's find something awesome!"></input>
	<button id="search-button-home" type="submit" class="btn-info form-control my-btn">Search</button>
</form>

<hr class="featurette-divider">

<div class="row">
	<div class="col-lg-3 col-md-3"><img src="/assets/img/gallery/boy1.png" alt="teacherpedia mascot boy1"></div>
	<div class="col-lg-3 col-md-3"><img src="/assets/img/gallery/girl1.png" alt="teacherpedia mascot girl1"></div>
	<div class="col-lg-3 col-md-3"><img src="/assets/img/gallery/boy2.png" alt="teacherpedia mascot boy1"></div>
	<div class="col-lg-3 col-md-3"><img src="/assets/img/gallery/boy1.png" alt="teacherpedia mascot boy1"></div>
</div>

<hr class="featurette-divider">

<div class="row">
	<h1 style="margin: auto">
		<div class="col-12">Check our our latest Resources!</div>
	</h1>
</div>

<br />


<?php
$count = 0;

foreach ($latest_resources as $row) {

	if ($count % 2 == 0) {

		echo '<div class="row featurette">
			<div class="col-md-7">
				<h2 class="featurette-heading">' . $row->resource_name . '<span class="text-muted"></span></h2>
				<p class="lead">' . $row->resource_description . '</p>
			</div>
			<div class="col-md-5">
			<a href="' . "resource/" . $row->slug . '"><img class="featurette-image img-fluid mx-auto" alt="500x500" src="' . $row->resource_banner . '"></a>
			</div>
			</div>';
	} else {
		echo '<div class="row featurette">
			<div class="col-md-7 order-md-2">
			  <h2 class="featurette-heading">' . $row->resource_name . '<span class="text-muted"></span></h2>
			  <p class="lead">' . $row->resource_description . '</p>
			</div>
			<div class="col-md-5 order-md-1">
			  <a href="' . "/resource" . $row->link . '/' . $row->slug . '"><img class="featurette-image img-fluid mx-auto" alt="500x500" src="' . $row->resource_banner . '"></a>
			</div>
		  </div>';
	}
	echo '<hr class="featurette-divider">';
	$count++;
}
?>






<!--
<div class="card-columns"> 
	<?php /*foreach($table->result() as $row) {
		echo
	'<a href="' . base_url() . 'resources/load/' . $row->link . '/' . $row->id . '"class="custom-card">' .
		'<div class="card">' . 
			'<img class="card-img-top" src="' .  $row->resource_thumb . '" alt="Card image cap">' .
			'<div class="card-body">' .
				'<h5 class="card-title">' . $row->resource_name . '</h5>' .
				'<p class="card-text">' . $row->resource_description . '</p>' .
				'<p class="card-text"><small class="text-muted"></small></p>' .
			'</div>' .
		'</div>' .
	'</a>';
	}*/
	?>
	
	
</div>	-->

<script>
	$('.carousel').carousel({
		interval: 8000
	})

	$('#myCarousel').carousel({
		interval: 5000
	})
</script>