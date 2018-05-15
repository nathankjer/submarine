<?php
/**
 * Pagination layout.
 *
 * @package submarine
 */

if ( ! function_exists ( 'submarine_pagination' ) ) {
	function submarine_pagination($args = [], $class = 'pagination pagination-lg') {

	    if ($GLOBALS['wp_query']->max_num_pages <= 1) return;

	    $args = wp_parse_args( $args, [
	        'mid_size'           => 2,
	        'prev_next'          => false,
	        'prev_text'          => __('&laquo;', 'submarine'),
	        'next_text'          => __('&raquo;', 'submarine'),
	        'screen_reader_text' => __('Posts navigation', 'submarine'),
	        'type'               => 'array',
	        'current'            => max( 1, get_query_var('paged') ),
	    ]);

	    $links     = paginate_links($args);
	    $next_link = get_next_posts_page_link();
	    $prev_link = get_previous_posts_page_link();

	    ?>

	    <nav aria-label="<?php echo $args['screen_reader_text']; ?>">
	        <ul class="pagination">
	            <li class="page-item">
	                <a class="page-link" href="<?php echo esc_attr($prev_link); ?>" aria-label="<?php echo __('Previous', 'submarine'); ?>">
	                    <span aria-hidden="true"><?php echo esc_attr($args['prev_text']); ?></span>
	                    <span class="sr-only"><?php echo __('Previous', 'submarine'); ?></span>
	                </a>
	            </li>

	            <?php
	            $i = 1;
	                foreach ( $links as $link ) { ?>
	                    <li class="page-item <?php if ($i == $args['current']) { echo 'active'; }; ?>">
	            <?php echo str_replace( 'page-numbers', 'page-link', $link ); ?>
	                    </li>

	            <?php $i++;} ?>

	            <li class="page-item">
	                <a class="page-link" href="<?php echo esc_attr($next_link); ?>" aria-label="<?php echo __('Next', 'submarine'); ?>">
	                    <span aria-hidden="true"><?php echo esc_attr($args['next_text']); ?></span>
	                    <span class="sr-only"><?php echo __('Next', 'submarine'); ?></span>
	                </a>
	            </li>
	        </ul>
	    </nav>
	    <?php
	}
}