<div class="wrap dable">
	<h1><?php echo get_admin_page_title() ?></h1>
	<?php settings_errors(); ?>
	<div class="container">
		<form method="post" action="options.php">
		<?php settings_fields( 'dable-settings-group' ); ?>

		<section>
			<h2><?php esc_html_e('Default Settings', 'dable'); ?></h2>
			<p><?php esc_html_e('Please enter the required default settings', 'dable'); ?></p>

			<h3>
				<?php esc_html_e('Content Wrapper Setting', 'dable'); ?>
				<button type="button" class="toggle"><span class="dashicons dashicons-editor-help"></span></button>
			</h3>
			<p class="desc">
				<?php
					printf(
						esc_html__('If you turn this ON, the %s tag will automatically wrap the content. Please set it to ON if you want to use personalized content recommendation service.', 'dable'),
						'<code>' . esc_html('<div itemprop = "articleBody">') . '</code>'
					);
				?>
			</p>
			<p>
				<label for="wrap_content">
					<?php $wrap_content = $this->get_option( 'wrap_content' ); ?>
					<input type="checkbox" id="wrap_content" name="dable-og-settings[wrap_content]" <?php checked( $wrap_content, true ); ?> value="true">
					<span><?php esc_html_e('Wrap content with <div itemprop="articleBody">. Uncheck if you do not need it.', 'dable'); ?></span>
				</label>
			</p>

			<h3>
				<?php esc_html_e('Target Post Types', 'dable'); ?>
				<button type="button" class="toggle"><span class="dashicons dashicons-editor-help"></span></button>
			</h3>
			<p class="desc">
				<?php esc_html_e('Please select the type of posts you would like to include in the widget. Set the "Pages" button to "ON" if you want to expose notice or contact pages.', 'dable'); ?>
			</p>
			<p class="post-types">
				<?php
					$registered_post_types = get_post_types( array('public'=>true), 'objects' );
					$post_types = get_option( 'dable-target-post-types' );

					if ( ! is_array( $post_types ) ) {
						update_option( 'dable-target-post-types', array( 'post' ) );
						$post_types = array( 'post' );
					}
				?>
				<?php foreach ( $registered_post_types as $key => $type ) : $id = 'target_post_type_' . $key; ?>
				<label for="<?php echo esc_attr( $id ); ?>" class="toggle-slide">
					<input
						type="checkbox"
						id="<?php echo esc_attr( $id ); ?>"
						name="dable-target-post-types[]"
						value="<?php echo esc_attr( $type->name ); ?>"
						<?php echo in_array( $key, $post_types, true ) ? 'checked' : '' ?>
					/>
					<span><i></i><?php echo esc_html( $type->label ); ?></span>
				</label>
				<?php endforeach; ?>
			</p>
		</section>

		<section>
			<h2>Open Graph</h2>
			<h3>
				<?php esc_html_e('Meta Tags', 'dable'); ?>
				<button type="button" class="toggle"><span class="dashicons dashicons-editor-help"></span></button>
			</h3>
			<p class="desc">
				<?php
					printf(
						esc_html__('By turning this ON create %s meta tags. If you are using a plugin that already has the same functionality, please set it to OFF.', 'dable'),
						'<a href="http://ogp.me/" target="_blank">Open Graph</a>'
					);
				?>
			</p>
			<p class="meta-tags">
				<?php $print_og_tag = $this->get_option( 'print_og_tag' ); ?>
				<label for="print_og_tag_1">
					<input type="radio" id="print_og_tag_1" name="dable-og-settings[print_og_tag]" <?php checked( $print_og_tag, true ); ?> value="true">
					<?php esc_html_e('Create Open Graph meta tags.', 'dable'); ?>
				</label>
				<label for="print_og_tag_2">
					<input type="radio" id="print_og_tag_2" name="dable-og-settings[print_og_tag]" <?php checked( $print_og_tag, false ); ?> value="">
					<?php esc_html_e('Do not generate Open Graph meta tags. Select this option if you are using a plugin that already has the same functionality.', 'dable'); ?>
				</label>
			</p>
			<h3>
				<?php esc_html_e('Thumbnail Size', 'dable'); ?>
				<button type="button" class="toggle"><span class="dashicons dashicons-editor-help"></span></button>
			</h3>
			<p class="desc">
				<?php esc_html_e('A function for adjusting the size of thumbnail image in Dable widget.', 'dable'); ?>
			</p>
			<p class="thumbnail-sizes">
				<?php
					$thumbnail_size = intval( $this->get_option( 'thumbnail_size', 250 ) );
					$thumbnail_size = $thumbnail_size > 500 ? 600 : 250;
				?>
				<label for="thumbnail_size_250">
					<input type="radio" name="dable-og-settings[thumbnail_size]" id="thumbnail_size_250" <?php checked( $thumbnail_size, 250 ); ?> value="250">
					250px
				</label>
				<label for="thumbnail_size_600">
					<input type="radio" name="dable-og-settings[thumbnail_size]" id="thumbnail_size_600" <?php checked( $thumbnail_size, 600 ); ?> value="600">
					600px
				</label>
			</p>
		</section>

		<section>
			<h2><?php esc_html_e('Widget Setting', 'dable'); ?></h2>
			<h3><?php esc_html_e('Widget', 'dable'); ?></h3>
			<p class="widget-types">
				<?php $widget_type = $this->get_option( 'widget_type', 'responsive' ); ?>
				<label for="widget_type_responsive">
					<input type="radio" name="dable-widget-settings[widget_type]" id="widget_type_responsive" <?php checked( $widget_type, 'responsive' ); ?> value="responsive">
					<?php esc_html_e('Script for Responsive Web', 'dable'); ?>
				</label>
				<label for="widget_type_platform">
					<input type="radio" name="dable-widget-settings[widget_type]" id="widget_type_platform" <?php checked( $widget_type, 'platform' ); ?> value="platform">
					<?php esc_html_e('Script for PC/Mobile Web', 'dable'); ?>
				</label>
			</p>

			<?php
				$categories = array(
					'post' => array(
						'label' => __('Post', 'dable'),
						'positions' => array(
							'top'        => __('Top of article', 'dable'),
							'in_article' => __('In-Article (after 2nd paragraph)', 'dable'),
							'bottom'     => __('Bottom of article', 'dable'),
							'bottom2'    => __('Bottom of article 2', 'dable'),
						),
					),
					'page' => array(
						'label' => __('Page', 'dable'),
						'positions' => array(
							'top'     => __('Top of article', 'dable'),
							'bottom'  => __('Bottom of article', 'dable'),
							'bottom2' => __('Bottom of article 2', 'dable'),
						),
					),
					'archive' => array(
						'label' => __('Archive', 'dable'),
						'positions' => array(
							'bottom'  => __('Bottom of page', 'dable'),
							'bottom2' => __('Bottom of page 2', 'dable'),
						),
					),
				);

				$platforms = array(
					'responsive' => array( 'responsive' => '' ),
					'platform'   => array(
						'pc'     => __('PC', 'dable'),
						'mobile' => __('Mobile', 'dable'),
					),
				);
			?>

			<nav class="nav-tab-wrapper dable-category-tabs">
				<?php $first = true; foreach ( $categories as $cat_key => $category ) : ?>
				<a href="#" class="nav-tab <?php echo $first ? 'nav-tab-active' : ''; ?>" data-tab="<?php echo esc_attr( $cat_key ); ?>">
					<?php echo esc_html( $category['label'] ); ?>
				</a>
				<?php $first = false; endforeach; ?>
			</nav>

			<?php $first = true; foreach ( $categories as $cat_key => $category ) : ?>
			<div class="dable-category-panel <?php echo ! $first ? 'hidden' : ''; ?>" data-category="<?php echo esc_attr( $cat_key ); ?>" style="padding: 15px 0;">

				<?php foreach ( $platforms as $platform_type => $platform_list ) : ?>
				<div class="dable-widget-<?php echo esc_attr( $platform_type ); ?> <?php echo $widget_type !== $platform_type ? 'hidden' : ''; ?>">
					<?php foreach ( $platform_list as $plat_key => $plat_label ) : ?>
						<?php if ( ! empty( $plat_label ) ) : ?>
							<h4><?php echo esc_html( $plat_label ); ?></h4>
						<?php endif; ?>

						<?php foreach ( $category['positions'] as $pos_key => $pos_label ) :
							$field_key = $plat_key . '_' . $cat_key . '_' . $pos_key;
							$display_id = 'display_widget_' . $field_key;
							$code_name = 'widget_code_' . $field_key;
						?>
						<p>
							<label for="<?php echo esc_attr( $display_id ); ?>">
								<input
									type="checkbox"
									id="<?php echo esc_attr( $display_id ); ?>"
									name="dable-widget-settings[<?php echo esc_attr( $display_id ); ?>]"
									<?php echo $this->get_option( $display_id ) ? 'checked' : '' ?>
									value="true"
								>
								<span><?php echo esc_html( ( ! empty( $plat_label ) ? $plat_label . ' ' : '' ) . $pos_label ); ?></span>
							</label>
							<textarea
								name="dable-widget-settings[<?php echo esc_attr( $code_name ); ?>]"
								class="large-text"
								rows="4"
							><?php echo esc_html( $this->get_option( $code_name ) ); ?></textarea>
						</p>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</div>
				<?php endforeach; ?>
			</div>
			<?php $first = false; endforeach; ?>
		</section>

		<?php submit_button(); ?>
		</form>
	</div>
</div>
