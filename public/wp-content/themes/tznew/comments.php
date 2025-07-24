<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TZnew
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area mt-12 p-6 bg-gray-50 rounded-lg">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title text-2xl font-bold mb-6">
			<?php
			$tznew_comment_count = get_comments_number();
			if ( '1' === $tznew_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One comment on &ldquo;%1$s&rdquo;', 'tznew' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf( 
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $tznew_comment_count, 'comments title', 'tznew' ) ),
					number_format_i18n( $tznew_comment_count ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<ol class="comment-list space-y-6 mb-6">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 60,
					'callback' => 'tznew_comment_callback',
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments p-4 bg-yellow-50 text-yellow-800 rounded-md"><?php esc_html_e( 'Comments are closed.', 'tznew' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	// Custom comment form with Tailwind classes
	$comment_form_args = array(
		'title_reply' => '<span class="text-2xl font-bold">' . esc_html__('Leave a Comment', 'tznew') . '</span>',
		'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title mb-4">',
		'title_reply_after' => '</h3>',
		'comment_notes_before' => '<p class="comment-notes mb-4 text-gray-600">' . esc_html__('Your email address will not be published. Required fields are marked *', 'tznew') . '</p>',
		'comment_field' => '<div class="comment-form-comment mb-4"><label for="comment" class="block text-sm font-medium text-gray-700 mb-1">' . esc_html__('Comment', 'tznew') . ' <span class="required">*</span></label><textarea id="comment" name="comment" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="6" required="required"></textarea></div>',
		'fields' => array(
			'author' => '<div class="comment-form-author mb-4"><label for="author" class="block text-sm font-medium text-gray-700 mb-1">' . esc_html__('Name', 'tznew') . ' <span class="required">*</span></label><input id="author" name="author" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required="required" /></div>',
			'email' => '<div class="comment-form-email mb-4"><label for="email" class="block text-sm font-medium text-gray-700 mb-1">' . esc_html__('Email', 'tznew') . ' <span class="required">*</span></label><input id="email" name="email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required="required" /></div>',
			'url' => '<div class="comment-form-url mb-4"><label for="url" class="block text-sm font-medium text-gray-700 mb-1">' . esc_html__('Website', 'tznew') . '</label><input id="url" name="url" type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" /></div>',
			'cookies' => '<div class="comment-form-cookies-consent mb-4 flex items-start"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" class="mt-1 mr-2" /><label for="wp-comment-cookies-consent" class="text-sm text-gray-700">' . esc_html__('Save my name, email, and website in this browser for the next time I comment.', 'tznew') . '</label></div>',
		),
		'submit_button' => '<input name="%1$s" type="submit" id="%2$s" class="%3$s bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300" value="%4$s" />',
		'submit_field' => '<div class="form-submit">%1$s %2$s</div>',
	);

	comment_form($comment_form_args);
	?>

</div><!-- #comments -->