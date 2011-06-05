<?php

function my_strip_tags($content='') {
    return strip_tags($content, '<p>');
}

add_filter('the_content', 'my_strip_tags');
?>
Some HTML here. Some Codeigniter code here too..
			<?php
			//Start the Wordpress Loop
			 while (have_posts()) : the_post(); ?>
    			<span class="smallredtext"><?php the_title(); ?> - <?php twentyten_posted_on(); ?></span><br />
    			<span class="bodytext"><?php the_content(); ?></span><br />
    			<a href="<?php the_permalink(); ?>" class="smallgraytext">More...</a><br /><br />
			<?php endwhile; // End the loop. Whew.  ?>
Some more html here mixed with Codeigniter code