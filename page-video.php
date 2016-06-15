<?php get_header(); ?>
<div class="main_content vertical_video pure-g-r">
	<!-- This is a default template page! -->

  <div class="pure-u-1">

  <figure class="vertical_header">
    <img class="vertical_header-img" src="https://spiderwebshow.ca/wp-content/uploads/2015/10/sws-talkshow.jpg" alt="">
    <p class="vertical_header-caption">Photo: <em>Habitat</em> by Mathieu Murphy-Perron, from the <a href="https://spiderwebshow.ca/images">SpiderWebShow Gallery</a></p>
  </figure>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<h1><?php the_title(); ?></h1>

	<?php the_content(); ?>

	<?php endwhile; else: ?>
  	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	<?php endif; ?>


	<hr />

	<?php

	  $videos = get_terms( 'video-series' );

  	foreach($videos as $video) {

    	$videoName = $video->name;
    	$videoSlug = $video->slug;
    	$videoID = $video->term_id;
    	$videoDesc = $video->description;
    	$videoImg = z_taxonomy_image_url($videoID, 'square-300');
    	$videoURL = get_bloginfo('url') . "/video-series/" . $video->slug;

    	// Get the podcast term's custom fields based on its ID. See functions.php for the code that controls the podcast term's custom fields
    	// See also: http://sabramedia.com/blog/how-to-add-custom-fields-to-custom-taxonomies
    	$podcast_custom_fields = get_option( "taxonomy_term_$video->term_id");
      $podcastURLitunes = $podcast_custom_fields['podcast_itunes_link'];

  ?>

    <div class="podcast podcast-card podcast-id-<?php echo $podcastID ?> podcast-<?php echo $podcastSlug ?>">

      <?php if ( $videoImg ) { ?>
        <figure>
          <a href="<?php echo $videoURL ?>">
            <img src="<?php echo $videoImg ?>" alt="<?php echo $videoName ?>" />
          </a>
        </figure>
      <?php } ?>

      <div class="podcast-text">
        <h2><a href="<?php echo $videoURL ?>"><?php echo $videoName ?></a></h2>

        <?php if ( $videoDesc ) { ?>
          <p><?php echo $videoDesc ?></p>
        <?php } ?>

      </div>

    </div>


  <?php }; // End foreach ?>


  </div>

</div>
<?php get_footer(); ?>
