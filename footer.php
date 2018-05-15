<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package submarine
 */

$the_theme = wp_get_theme();
$container = get_theme_mod( 'submarine_container_type' );
?>

<div class="wrapper bg-light" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">

		<div class="row">

			<div class="col-md-12">

				<footer class="site-footer" id="colophon">

					<?php wp_nav_menu(
						array(
							'theme_location'  => 'secondary',
							'container_class' => 'footer-links',
							'fallback_cb'     => '',
							'menu_class'      => 'list-unstyled',
							'walker'          => new submarine_WP_Bootstrap_Navwalker(),
						)
					); ?>

					<?php wp_nav_menu(
						array(
							'theme_location'  => 'social',
							'container_class' => 'social-links',
							'fallback_cb'     => '',
							'menu_class'      => 'list-unstyled',
							'walker'          => new submarine_WP_Bootstrap_Navwalker(),
						)
					); ?>

					<div class="site-info">

							<h3><?php bloginfo( 'name' ); ?></h3>

							<p><?php bloginfo('description'); ?> </p>

					</div><!-- .site-info -->

				</footer><!-- #colophon -->

			</div><!--col end -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

