<?php

function get_pixelgrade_club_page_layout() {
	// Retrieve the products (themes) the activation customer has access to
	// They should match the ones on his My Account page
	$user_themes = PixelgradeCare_Admin::get_customer_products(); ?>

	<div class="wrap pixelgrade-themes-page">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Pixelgrade Themes', 'pixelgrade_care' ); ?></h1>
		<div class="theme-browser">
			<div class="themes wp-clearfix <?php echo empty($user_themes) ? 'no-results' : ''; ?>">

				<?php
				if ( empty( $user_themes ) ) {
					echo '<p class="no-themes">Sorry, but we couldn\'t find any themes.</p>';
				} else {
					foreach ( $user_themes as $theme ) {
						$aria_action = esc_attr( $theme['id'] . '-action' );
						$aria_name = esc_attr( $theme['id'] . '-name' );

						// do a double check to see if theme is installed
						$get_theme = wp_get_theme( $theme['slug'] );
						if ( ! $get_theme->errors() ) {// theme exists / installed = true
							$theme['installed'] = true;
						} else {
							$theme['installed'] = false;
						}

						// do a double check to see if theme is active
						$active_theme = wp_get_theme();
						if ( $active_theme->get_stylesheet() == $theme['slug'] ) {
							$theme['active'] = true;
						} else {
							$theme['active'] = false;
						}

						?>

						<div class="theme<?php if ( $theme['active'] ) { echo ' active'; } elseif ( $theme['installed'] ) { echo ' installed'; } ?>" tabindex="0" aria-describedby="<?php echo $aria_action . ' ' . $aria_name; ?>">
							<?php if ( ! empty( $theme['screenshot'] ) ) { ?>
								<div class="theme-screenshot">
									<?php echo $theme['screenshot'] ?>
								</div>
							<?php } else { ?>
								<div class="theme-screenshot blank"></div>
							<?php } ?>

							<?php if ( $theme['hasUpdate'] ) : ?>
								<div class="update-message notice inline notice-warning notice-alt">
									<?php if ( $theme['hasPackage'] ) : ?>
										<p><?php _e( 'New version available. <button class="button-link" type="button">Update now</button>' ); ?></p>
									<?php else : ?>
										<p><?php _e( 'New version available.' ); ?></p>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<span class="more-details"
							      id="<?php echo $aria_action; ?>"><?php _e( 'Theme Details' ); ?></span>
							<div class="theme-author"><?php printf( __( 'By %s' ), $theme['author'] ); ?></div>

							<div class="theme-id-container">
								<?php if ( $theme['active'] ) { ?>
									<h2 class="theme-name" id="<?php echo $aria_name; ?>">
										<?php
										/* translators: %s: theme name */
										printf( __( '<span>Active:</span> %s' ), $theme['name'] );
										?>
									</h2>
								<?php } else { ?>
									<h2 class="theme-name" id="<?php echo $aria_name; ?>"><?php echo $theme['name']; ?></h2>
								<?php } ?>

								<div class="theme-actions">
									<?php if ( $theme['active'] ) { ?>
										<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
											<a class="button button-primary customize load-customize hide-if-no-customize"
											   href="<?php echo wp_customize_url( $theme['slug'] ); ?>"><?php _e( 'Customize' ); ?></a>
										<?php } ?>
									<?php } else { ?>
										<?php
										/* translators: %s: Theme name */
										if ( $theme['installed'] ) {
											$aria_label        = sprintf( _x( 'Activate %s', 'theme' ), '{{ data.name }}' );
											$aria_theme_action = 'Activate';
											$aria_class        = 'button-primary club-activate-theme';
										} else {
											$aria_label        = sprintf( _x( 'Install %s', 'theme' ), '{{ data.name }}' );
											$aria_theme_action = 'Install';
											$aria_class        = 'club-install-theme';
										}
										?>
										<?php if ( current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) { ?>
											<a class="button load-customize hide-if-no-customize"
											   href="<?php echo $theme['demo_url']; ?>"
											   target="_blank"><?php _e( 'Live Demo' ); ?></a>
										<?php } ?>
										<a class="button activate <?php echo $aria_class ?>"
										   href="<?php echo '#'; ?>"
										   data-url="<?php echo $theme['download_url'] ?>"
										   data-slug="<?php echo $theme['slug'] ?>"
										   aria-label="<?php echo esc_attr( $aria_label ); ?>"><?php _e( $aria_theme_action ); ?></a>
									<?php } ?>

								</div>
							</div>
						</div>
						<?php
					}
				} ?>
			</div>
		</div>
	</div>
	<script>
		jQuery(document).ready(function () {
			jQuery('.more-details').remove();
		});
	</script>
	<?php
}
