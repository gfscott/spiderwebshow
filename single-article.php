<?php get_header(); ?>
<div class="main_content vertical_cdncult pure-g-r">

	<?php 
	
		// WP_Query arguments
		$args = array (
			'post_type'              => 'edition',
			'posts_per_page'         => '1',
		);
		
		// The Query
		$latest_edition_date = new WP_Query( $args );
		
		// The Loop
		if ( $latest_edition_date->have_posts() ) {
			while ( $latest_edition_date->have_posts() ) {
				$latest_edition_date->the_post();
				$most_recent_date = get_the_date();
			}
		} else {
			// no posts found
		}
		
		// Restore original Post Data
		wp_reset_postdata();
	
	?>
	
	<header class="cdncult_header pure-u-1">
		<h1><a href="/cdncult" title="#CdnCult Home">#Cdncult Times</a></h1>
		<p class="cdncult-tagline">Reporting and commentary about Canadian performance culture in Internet times &bull; <?php echo $most_recent_date; ?></p>
	</header>
	<hr/>
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); // WORDPRESS LOOP BEGINS ?>
		<header class="post-header pure-u-1">
			<time class="post-pub-date" datetime="<?php the_time('c'); ?>"><?php the_time('l, F j, Y'); ?></time>
			<h1 class="post-title"><?php the_title(); ?></h1>
			<p class="author-name">By <?php if ( function_exists( 'coauthors_posts_links' ) ) {
				    coauthors_posts_links();
				} else {
				    the_author_posts_link();
				}; ?></p>

			<div class="share" style="margin-bottom:2em;">
				<div class="share-button twitter" style="vertical-align: top;">
					<a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-via="SpiderWebShow" data-hashtags="cdncult" data-dnt="true">Tweet This</a>
				</div>
				<div class="share-button facebook">
					<div class="fb-like" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
				</div>
			</div>
			
		</header>
		
		<section class="post-content pure-u-3-4">
						
			<?php the_content(); ?>
						
			<hr>
			
			<aside>
				<?php $the_parent = wpcf_pr_post_get_belongs(get_the_ID(),'edition'); ?>
				<p>This article originally appeared in <a href="<?php echo get_permalink($the_parent); ?>"><?php echo get_the_title($the_parent); ?></a></p>
			</aside>

			<div class="comments">
				<!-- START: Livefyre Embed -->
				<div id="livefyre-comments"></div>
				<script type="text/javascript" src="http://zor.livefyre.com/wjs/v3.0/javascripts/livefyre.js"></script>
				<script type="text/javascript">
				(function () {
				    var articleId = fyre.conv.load.makeArticleId(null);
				    fyre.conv.load({}, [{
				        el: 'livefyre-comments',
				        network: "livefyre.com",
				        siteId: "346370",
				        articleId: articleId,
				        signed: false,
				        collectionMeta: {
				            articleId: articleId,
				            url: fyre.conv.load.makeCollectionUrl(),
				        }
				    }], function() {});
				}());
				</script>
				<!-- END: Livefyre Embed --> 
			</div>
			
		</section>
		
		<aside class="pure-u-1-4">
		<?php 
			if ( function_exists( 'coauthors_posts_links' ) ) { 
				$coauthors = get_coauthors();
				}
				
				if($coauthors && count($coauthors) == 1) { ?>
				
					<?php
					/////////////////////////////
					// CUSTOM POST TYPE METADATA
					/////////////////////////////
						$thisPost = (string) $post->ID;
						$twitterHandle = types_render_usermeta_field( "twitter-handle", array("raw"=>"true")); // get custom field for company/artist twitter handle -- OPTIONAL
						$authorWebsite = get_the_author_meta("user_url"); // get custom field for compsny/artist website -- OPTIONAL
						$authorBio = get_the_author_meta("description"); // get the author's description from their user account
						$authorID = get_the_author_meta("ID"); // get the author's description from their user account
						$authorPhoto = types_render_usermeta_field( "author-photo", array("raw"=>"true")); // get URL for author image
						
						if($authorPhoto){
							// Function to replace the upload-sized author photo with the thumbnail-sized one
							$findImgExt = "/(\.jpg|\.jpeg|\.png)$/";
							$addThumbDimension = "-75x75$1";
							$authorPhoto = preg_replace($findImgExt, $addThumbDimension, $authorPhoto);
						}
									
						$authorName = get_the_author();
					
					?>
				
					<div class="post_meta post_meta-author">
					<p class="about-the-author">About the author</p>
					
					<?php if($authorPhoto){ ?>
						<figure class="author-photo">
							<a href="<?php echo get_author_posts_url($authorID); ?>" title="Visit <?php echo $authorName; ?>&rsquo;s Author Profile">
								<img src="<?php echo $authorPhoto; ?>" alt="<?php echo $authorName; ?>" />
							</a>
						</figure>
					<?php } ?>
					
					<p class="author-name"><a href="<?php echo get_author_posts_url($authorID); ?>" title="Visit <?php echo $authorName; ?>&rsquo;s Author Profile"><?php echo $authorName; ?></a></p>
					<?php if($authorBio){ echo '<p class="author-bio">'.$authorBio.'</p>'; } ?>
					<?php if($authorWebsite || $twitterHandle) { ?><p class="connect-the-author">Elsewhere online</p> <?php } ?>
					<?php if($authorWebsite){ echo '<p><a href="'.$authorWebsite.'" class="author-website" title="Visit '.$authorName.'&rsquo;s website" target="_blank">Website <i class="icon-external-link"></i></a></p>'; } ?>
					<?php if($twitterHandle){ echo '<p><a href="https://twitter.com/'.$twitterHandle.'" title="Follow '.$authorName.' on Twitter" class="twitter-follow-button" data-dnt="true" data-show-count="false">@'.$twitterHandle.'</a></p>'; } ?>
					
					<?php $args = array( 'posts_per_page' => 5, 'author' => $authorID, 'exclude' => $thisPost ); // Most recent five other posts by this author, excluding the current one
						$myposts = get_posts( $args );
						if(count($myposts) >= 1 ) : ?>
						<p class="author-related-posts-header">Also by this author</p>
						<?php foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
							<a href="<?php the_permalink(); ?>" title="Read <?php the_title(); ?>" class="author-related-post"><?php the_title(); ?></a>
					<?php endforeach; endif; wp_reset_postdata();?>
					</div>
			<?php } else { ?>
			
				<?php foreach($coauthors as $author) { ?>
					
				<?php } ?>
			
			<?php } // end else ?>			
				
			
			<?php if(!has_category('uncategorized')) {  // Only display the category & tag box if the post has been actually given a category other than "uncategorized" ?>
			<div class="post_meta post_meta-bottom">
					<p class="category-list-header">Filed Under</p>
					<p class="category-list"><?php the_category(', '); // output as an inline list, separated by commas ?></p>
				<?php if(has_tag()): ?>
					<p class="tag-list-header">Tagged With</p>
					<p class="tag-list"><?php the_tags('',', ',''); // output as an inline list with nothing preceding or following, separated by commas ?></p>
				<?php endif; ?>
			</div>
			<?php } ?>
			
		</aside>
			
	
	<?php endwhile; else: ?>
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
	<?php endif; // WORDPRESS LOOP ENDS ?>

</div><!-- /.main_content -->

<?php get_footer(); ?>
