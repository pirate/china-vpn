<?php
/**
 * Comments Template
 *
 * @file           comments.php
 * @package        Pilot Fish
 * @filesource     wp-content/themes/pilot-fish/comments.php
 * @since          Pilot Fish 0.1
 */
if ( post_password_required() ) : ?>
	<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'pilotfish' ); ?></p>
	<?php /* Stop the rest of comments.php from being processed */
			return;
endif; ?>

<?php if (have_comments()) : ?>
    <h6 id="comments"><?php comments_number(__('No Response to', 'pilotfish'), __('One Response to', 'pilotfish'), __('% Responses to', 'pilotfish')); ?> <i><?php the_title(); ?></i></h6>

    <ol class="commentlist">
        <?php wp_list_comments('avatar_size=60'); ?> 
    </ol>
    
    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
    <nav class="pager">
        <div class="previous"><?php previous_comments_link(__( '&#8249; previous','pilotfish' )); ?></div><!-- end of .previous -->
        <div class="next"><?php next_comments_link(__( 'next &#8250;','pilotfish', 0 )); ?></div><!-- end of .next -->
    </nav><!-- end of.pager -->
    <?php endif; ?>

<?php else : ?>

<?php endif; ?>

<?php if (comments_open()) : ?>

    <?php
    $fields = array(
        'author' => '<p id="comment-form-author">' . '<label for="author">' . __('Name','pilotfish') . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="author" name="author" placeholder="'. __('name (required)', 'pilotfish').'" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" /></p>',
        'email' => '<p id="comment-form-email"><label for="email">' . __('E-mail','pilotfish') . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="email" name="email" placeholder="'. __('email (required)', 'pilotfish').'" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" /></p>',
        'url' => '<p id="comment-form-url"><label for="url">' . __('Website','pilotfish') . '</label>' .
        '<input id="url" name="url" placeholder="'. __('website', 'pilotfish').'" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>',
    );

    $defaults = array('fields' => apply_filters('comment_form_default_fields', $fields));
    comment_form($defaults);
    ?>
<?php endif; ?>
