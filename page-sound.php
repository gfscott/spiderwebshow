<?php get_header(); ?>
<div class="main_content vertical_sound pure-g-r">
	<!-- This is a default template page! -->
  
  <div class="pure-u-1">  
  
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
	<h1><?php the_title(); ?></h1>
	
	<?php the_content(); ?>
	
	<?php endwhile; else: ?>
  	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	<?php endif; ?>
	
	
	<hr />
	
	<?php
	  
	  $sounds = get_terms( 'sounds' );
  	
  	foreach($sounds as $sound) {
          	
    	$soundName = $sound->name;
    	$soundSlug = $sound->slug;
    	$soundID = $sound->term_id;
    	$soundDesc = $sound->description;
    	$soundImg = z_taxonomy_image_url($soundID, 'thumbnail');
    	$soundURL = get_bloginfo('url') . "/sounds/" . $sound->slug;
    	
    	// Get the podcast term's custom fields based on its ID. See functions.php for the code that controls the podcast term's custom fields
    	// See also: http://sabramedia.com/blog/how-to-add-custom-fields-to-custom-taxonomies
    	$podcast_custom_fields = get_option( "taxonomy_term_$sound->term_id");    	
      $podcastURLitunes = $podcast_custom_fields['podcast_itunes_link'];
  
  ?>    	
    
    <div class="podcast podcast-id-<?php echo $podcastID ?> podcast-<?php echo $podcastSlug ?>">
      
      <h2><a href="<?php echo $soundURL ?>"><?php echo $soundName ?></a></h2>
      
      <?php if ( $soundImg ) { ?>
        <figure>
          <a href="<?php echo $soundURL ?>">
            <img src="<?php echo $soundImg ?>" alt="<?php echo $soundName ?>" />
          </a>
        </figure>
      <?php } ?>
      
      <?php if ( $soundDesc ) { ?>
        <p><?php echo $soundDesc ?></p>
      <?php } ?>
      <?php if ( $podcastURLitunes ) { ?>
      <p><small><a href="<?php echo $podcastURLitunes ?>">Subscribe in iTunes</a></small></p>
      <?php } ?>
      
    </div>
    	
  
  <?php }; // End foreach ?>
	
	
  </div>

</div>
<?php get_footer(); ?>