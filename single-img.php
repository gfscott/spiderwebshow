<?php get_header(); ?>
<div class="main_content vertical_sound pure-g-r">
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  
  <?php
  	
  	$img =  wp_get_post_terms( $post->ID, 'img-series' );
  
  if ($img) {

    $img = $img[0];  	
    	$imgID = $img->term_id;
    	$imgName = $img->name;
    	$imgDesc = $img->description;
    	$imgSlug = $img->slug;
    	$imgURL = get_bloginfo('url') . "/img-series/" . $imgSlug;
    	
  	}
	?>
  <div class="pure-u-1">
    
    <figure class="vertical_header">
      <img class="vertical_header-img" src="https://spiderwebshow.ca/wp-content/uploads/2015/10/sws-gallery.jpg" alt="">
      <p class="vertical_header-caption">Photo: <em>Rage</em> by Mathieu Murphy-Perron, from the <a href="https://spiderwebshow.ca/images">SpiderWebShow Gallery</a></p>
    </figure>
    
    <p>
      <a href="/images" title="More SpiderWebShow Images">SpiderWeb Images</a><?php 
        if ( $imgName && $imgURL ): ?>&nbsp;&raquo;&nbsp;<a href="<?php echo $imgURL ?>"><?php echo $imgName; ?></a>&nbsp;&raquo;&nbsp;<?php the_title(); ?><?php endif; ?>
    </p>

  </div>
	
	<article class="pure-u-1">
		
		<h1><?php the_title(); ?></h1>
		<p>Posted by <?php if ( function_exists( 'coauthors_posts_links' ) ) {
          coauthors_posts_links();
      } else {
          the_author_posts_link();
      } ?> on <?php the_date("F j, Y"); ?></p>
		<?php the_content(); ?>

	</article>
	
	<?php endwhile; else: ?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	<?php endif; ?>

</div>

<?php get_footer(); ?>