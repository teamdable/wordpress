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
					<input type="radio" id="print_og_tag_1" name="dable-og-settings[print_og_tag]" <?php checked( $print_og_tag, false ); ?> value="">
					<?php esc_html_e('Create Open Graph meta tags.', 'dable'); ?>
				</label>
				<label for="print_og_tag_2">
					<input type="radio" id="print_og_tag_2" name="dable-og-settings[print_og_tag]" <?php checked( $print_og_tag, true ); ?> value="true">
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
			<fieldset class="dable-widget-responsive <?php echo 'responsive' !== $widget_type ? 'hidden' : ''; ?>">
				<p>
					<label for="display_widget_responsive_bottom">
						<input
							type="checkbox"
							id="display_widget_responsive_bottom"
							name="dable-widget-settings[display_widget_responsive_bottom]"
							<?php echo $this->get_option( 'display_widget_responsive_bottom' ) ? 'checked' : '' ?>
							value="true"
						>
						<span><?php esc_html_e('Bottom of article', 'dable'); ?></span>
					</label>
					<textarea
						placeholder="<?php esc_attr_e( 'This widget is exposed at the bottom of the by-line.', 'dable' ); ?>"
						name="dable-widget-settings[widget_code_responsive_bottom]"
						class="large-text"
						rows="4"
					><?php echo esc_html( $this->get_option( 'widget_code_responsive_bottom' ) ); ?></textarea>
				</p>
				<p>
					<label for="display_widget_responsive_left">
						<input
							type="checkbox"
							id="display_widget_responsive_left"
							name="dable-widget-settings[display_widget_responsive_left]"
							<?php echo $this->get_option( 'display_widget_responsive_left' ) ? 'checked' : '' ?>
							value="true"
						>
						<span><?php esc_html_e('Left side of article', 'dable'); ?></span>
					</label>
					<textarea
						placeholder="<?php esc_attr_e( 'This widget is exposed in the top left corner.', 'dable' ); ?>"
						name="dable-widget-settings[widget_code_responsive_left]"
						class="large-text"
						rows="4"
					><?php echo esc_html( $this->get_option( 'widget_code_responsive_left' ) ); ?></textarea>
				</p>
				<p>
					<label for="display_widget_responsive_right">
						<input
							type="checkbox"
							id="display_widget_responsive_right"
							name="dable-widget-settings[display_widget_responsive_right]"
							<?php echo $this->get_option( 'display_widget_responsive_right' ) ? 'checked' : '' ?>
							value="true"
						>
						<span><?php esc_html_e('Right side of article', 'dable'); ?></span>
					</label>
					<textarea
						placeholder="<?php esc_attr_e( 'This widget is exposed in the top right corner.', 'dable' ); ?>"
						name="dable-widget-settings[widget_code_responsive_right]"
						class="large-text"
						rows="4"
					><?php echo esc_html( $this->get_option( 'widget_code_responsive_right' ) ); ?></textarea>
				</p>
			</fieldset>
			<fieldset class="dable-widget-platform <?php echo 'platform' !== $widget_type ? 'hidden' : ''; ?>">
				<p>
					<label for="display_widget_pc_bottom">
						<input
							type="checkbox"
							id="display_widget_pc_bottom"
							name="dable-widget-settings[display_widget_pc_bottom]"
							<?php echo $this->get_option( 'display_widget_pc_bottom' ) ? 'checked' : '' ?>
							value="true"
						>
						<span><?php esc_html_e('PC Bottom of article', 'dable'); ?></span>
					</label>
					<textarea
						placeholder="<?php esc_attr_e( 'This widget is exposed at the bottom of the by-line.', 'dable' ); ?><?php esc_attr_e('(PC site only)', 'dable'); ?>"
						name="dable-widget-settings[widget_code_pc_bottom]"
						class="large-text"
						rows="4"
					><?php echo esc_html( $this->get_option( 'widget_code_pc_bottom' ) ); ?></textarea>
				</p>
				<p>
					<label for="display_widget_pc_left">
						<input
							type="checkbox"
							id="display_widget_pc_left"
							name="dable-widget-settings[display_widget_pc_left]"
							<?php echo $this->get_option( 'display_widget_pc_left' ) ? 'checked' : '' ?>
							value="true"
						>
						<span><?php esc_html_e('PC Left side of article', 'dable'); ?></span>
					</label>
					<textarea
						placeholder="<?php esc_attr_e( 'This widget is exposed in the top left corner.', 'dable' ); ?><?php esc_attr_e('(PC site only)', 'dable'); ?>"
						name="dable-widget-settings[widget_code_pc_left]"
						class="large-text"
						rows="4"
					><?php echo esc_html( $this->get_option( 'widget_code_pc_left' ) ); ?></textarea>
				</p>
				<p>
					<label for="display_widget_pc_right">
						<input
							type="checkbox"
							id="display_widget_pc_right"
							name="dable-widget-settings[display_widget_pc_right]"
							<?php echo $this->get_option( 'display_widget_pc_right' ) ? 'checked' : '' ?>
							value="true"
						>
						<span><?php esc_html_e('PC Right side of article', 'dable'); ?></span>
					</label>
					<textarea
						placeholder="<?php esc_attr_e( 'This widget is exposed in the top right corner.', 'dable' ); ?><?php esc_attr_e('(PC site only)', 'dable'); ?>"
						name="dable-widget-settings[widget_code_pc_right]"
						class="large-text"
						rows="4"
					><?php echo esc_html( $this->get_option( 'widget_code_pc_right' ) ); ?></textarea>
				</p>
				<p>
					<label for="display_widget_mobile_bottom">
						<input
							type="checkbox"
							id="display_widget_mobile_bottom"
							name="dable-widget-settings[display_widget_mobile_bottom]"
							<?php echo $this->get_option( 'display_widget_mobile_bottom' ) ? 'checked' : '' ?>
							value="true"
						>
						<span><?php esc_html_e('Mobile Bottom of article', 'dable'); ?></span>
					</label>
					<textarea
						placeholder="<?php esc_attr_e( 'This widget is exposed at the bottom of the by-line.', 'dable' ); ?><?php esc_attr_e('(Mobile site only)', 'dable'); ?>"
						name="dable-widget-settings[widget_code_mobile_bottom]"
						class="large-text"
						rows="4"
					><?php echo esc_html( $this->get_option( 'widget_code_mobile_bottom' ) ); ?></textarea>
				</p>
				<p>
					<label for="display_widget_mobile_left">
						<input
							type="checkbox"
							id="display_widget_mobile_left"
							name="dable-widget-settings[display_widget_mobile_left]"
							<?php echo $this->get_option( 'display_widget_mobile_left' ) ? 'checked' : '' ?>
							value="true"
						>
						<span><?php esc_html_e('Mobile Left side of article', 'dable'); ?></span>
					</label>
					<textarea
						placeholder="<?php esc_attr_e( 'This widget is exposed in the top left corner.', 'dable' ); ?><?php esc_attr_e('(Mobile site only)', 'dable'); ?>"
						name="dable-widget-settings[widget_code_mobile_left]"
						class="large-text"
						rows="4"
					><?php echo esc_html( $this->get_option( 'widget_code_mobile_left' ) ); ?></textarea>
				</p>
				<p>
					<label for="display_widget_mobile_right">
						<input
							type="checkbox"
							id="display_widget_mobile_right"
							name="dable-widget-settings[display_widget_mobile_right]"
							<?php echo $this->get_option( 'display_widget_mobile_right' ) ? 'checked' : '' ?>
							value="true"
						>
						<span><?php esc_html_e('Mobile Right side of article', 'dable'); ?></span>
					</label>
					<textarea
						placeholder="<?php esc_attr_e( 'This widget is exposed in the top right corner.', 'dable' ); ?><?php esc_attr_e('(Mobile site only)', 'dable'); ?>"
						name="dable-widget-settings[widget_code_mobile_right]"
						class="large-text"
						rows="4"
					><?php echo esc_html( $this->get_option( 'widget_code_mobile_right' ) ); ?></textarea>
				</p>
			</fieldset>
		</section>

		<?php submit_button(); ?>
		</form>

		<ul class="news">
			<li class="news__entry template">
				<h3 class="news__title"><a href="#">Subject 1</a></h3>
				<div class="news__thumbnail"><a href="#"></a></div>
				<p class="news__content"><a href="#" class="more-link">Lorem ipsum</a></p>
			</li>
		</ul>
	</div>
</div>
