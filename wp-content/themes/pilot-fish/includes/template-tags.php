<?php

// Return post entry meta information
function pilotfish_entry_meta() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s by %3$s', 'pilotfish' ),'meta-prep meta-prep-author',
		            sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
			            get_permalink(),
			            esc_attr( get_the_time() ),
			            get_the_date()
		            ),
		            sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			            get_author_posts_url( get_the_author_meta( 'ID' ) ),
			        sprintf( esc_attr__( 'View all posts by %s', 'pilotfish' ), get_the_author() ),
			            get_the_author()
		                )
			        );
}
