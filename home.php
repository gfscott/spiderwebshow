<?php get_header(); ?>

<div class="main_content">
		
		<p>
		  <div style="float:left;">
		    <a href="https://twitter.com/spiderwebshow" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @SpiderWebShow</a>
		  </div> &nbsp;
		  <div class="fb-like" data-href="https://www.facebook.com/PraxNACspiderwebshow" data-width="" data-height="" data-colorscheme="light" data-layout="button" data-action="like" data-show-faces="false" data-send="false" style="margin-left: 1em; float:left;">
		  </div>&nbsp;
		  <div class="instagram-follow" style="float:left; margin-left: 1em;">
        <a href="https://instagram.com/spiderwebshow?ref=badge" class="ig-b- ig-b-v-24"><img src="//badges.instagram.com/static/images/ig-badge-view-24.png" alt="Instagram" target="_blank" /></a>
		  </div>
		</p>
    
    <div id="masonry" class="directory">
      <?php dynamic_sidebar( 'front-page' ); // This is a dynamic sidebar widget area that displays only on the homepage. Controlled from the Widgets dashboard. Accepts plain text and html ?>
    </div>

<script>

head.ready('twitter', function(){
  head.load('//cdnjs.cloudflare.com/ajax/libs/masonry/3.1.5/masonry.pkgd.min.js', function(){
    var directory = document.querySelector('.directory');
    var msnry = new Masonry(directory, {
      itemSelector: '.directory-card',
      transitionDuration: 0
    });
    
    twttr.ready(function(twttr){
      twttr.events.bind('rendered', function(event){
          msnry.layout();
      });
    });
  });
});

head.ready('fitvids', function(){
  $('.video').fitVids();
});

</script>

<?php get_footer(); ?>
