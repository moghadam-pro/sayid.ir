<?php
/**
 * Post meta registration (REST-exposed) + native meta boxes. No ACF or
 * third-party fields plugin dependency.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * $field_map_key is the post type for every real post type (post,
 * sayid_lab, sayid_project) — but `page` alone can mean one of several
 * unrelated field sets depending on which template it's using (Hero
 * content vs. a generic form's recipient address), so for pages the key is
 * instead 'page:<template-purpose>' — see sayid_page_field_map_key().
 */
function sayid_field_map( $field_map_key ) {
	// Notes and Articles are both just `post` (see inc/content-types.php),
	// so there's one "related posts" relation now, not a separate
	// notes-vs-articles pair pointing at the same post type.
	$common_related = array(
		'sayid_related_posts'    => array( 'type' => 'array', 'sanitize' => 'int_array' ),
		'sayid_related_lab'      => array( 'type' => 'array', 'sanitize' => 'int_array' ),
		'sayid_related_projects' => array( 'type' => 'array', 'sanitize' => 'int_array' ),
	);

	switch ( $field_map_key ) {
		case 'post':
			return array_merge(
				array(
					'sayid_subtitle'          => array( 'type' => 'string', 'sanitize' => 'text' ),
					'sayid_source_url'        => array( 'type' => 'string', 'sanitize' => 'url' ),
					'sayid_featured_homepage' => array( 'type' => 'boolean', 'sanitize' => 'bool' ),
				),
				$common_related
			);

		case 'sayid_lab':
			return array_merge(
				array(
					'sayid_short_description' => array( 'type' => 'string', 'sanitize' => 'textarea' ),
					'sayid_status'            => array( 'type' => 'string', 'sanitize' => 'status' ),
					'sayid_started_at'        => array( 'type' => 'string', 'sanitize' => 'text' ),
					'sayid_shipped_at'        => array( 'type' => 'string', 'sanitize' => 'text' ),
					'sayid_tools'             => array( 'type' => 'string', 'sanitize' => 'text' ),
					'sayid_repo_url'          => array( 'type' => 'string', 'sanitize' => 'url' ),
					'sayid_live_url'          => array( 'type' => 'string', 'sanitize' => 'url' ),
					'sayid_featured_homepage' => array( 'type' => 'boolean', 'sanitize' => 'bool' ),
					'sayid_homepage_priority' => array( 'type' => 'integer', 'sanitize' => 'int' ),
				),
				$common_related
			);

		case 'sayid_project':
			return array_merge(
				array(
					'sayid_short_description' => array( 'type' => 'string', 'sanitize' => 'textarea' ),
					'sayid_role'               => array( 'type' => 'string', 'sanitize' => 'text' ),
					'sayid_organization'       => array( 'type' => 'string', 'sanitize' => 'text' ),
					'sayid_project_type'       => array( 'type' => 'string', 'sanitize' => 'text' ),
					'sayid_date_start'         => array( 'type' => 'string', 'sanitize' => 'text' ),
					'sayid_date_end'           => array( 'type' => 'string', 'sanitize' => 'text' ),
					'sayid_tools'              => array( 'type' => 'string', 'sanitize' => 'text' ),
					'sayid_collaborators'      => array( 'type' => 'string', 'sanitize' => 'text' ),
					'sayid_challenge'          => array( 'type' => 'string', 'sanitize' => 'wysiwyg' ),
					'sayid_context'            => array( 'type' => 'string', 'sanitize' => 'wysiwyg' ),
					'sayid_process'            => array( 'type' => 'string', 'sanitize' => 'wysiwyg' ),
					'sayid_decisions'          => array( 'type' => 'string', 'sanitize' => 'wysiwyg' ),
					'sayid_outcome'            => array( 'type' => 'string', 'sanitize' => 'wysiwyg' ),
					'sayid_metrics'            => array( 'type' => 'string', 'sanitize' => 'textarea' ),
					'sayid_external_url'       => array( 'type' => 'string', 'sanitize' => 'url' ),
					'sayid_featured_homepage'  => array( 'type' => 'boolean', 'sanitize' => 'bool' ),
					'sayid_homepage_priority'  => array( 'type' => 'integer', 'sanitize' => 'int' ),
					'sayid_gallery'            => array( 'type' => 'array', 'sanitize' => 'int_array' ),
				),
				$common_related
			);

		// Only rendered on the one page using page-home-content.php — see
		// the add_meta_boxes_page hook below. Every key falls back to the
		// current hardcoded Hero copy when empty (template-parts/hero.php),
		// so this never has to be filled in before the homepage works.
		case 'page:home-content':
			return array(
				'sayid_hero_greeting'        => array( 'type' => 'string', 'sanitize' => 'text' ),
				'sayid_hero_name'            => array( 'type' => 'string', 'sanitize' => 'text' ),
				'sayid_hero_name_suffix'     => array( 'type' => 'string', 'sanitize' => 'text' ),
				'sayid_hero_role'            => array( 'type' => 'string', 'sanitize' => 'text' ),
				'sayid_hero_lede'            => array( 'type' => 'string', 'sanitize' => 'textarea' ),
				'sayid_hero_cta_label'       => array( 'type' => 'string', 'sanitize' => 'text' ),
				'sayid_hero_rotator_phrases' => array( 'type' => 'string', 'sanitize' => 'textarea' ),
			);

		// Any page using page-form.php — see inc/form-template.php. Blank
		// recipient falls back to the site admin email at send time.
		case 'page:form':
			return array(
				'sayid_form_recipient_email' => array( 'type' => 'string', 'sanitize' => 'text' ),
			);

		// Any page using page-archive.php. Blank category = every post,
		// same as this site's own "نوشته‌ها" index.
		case 'page:archive':
			return array(
				'sayid_archive_category' => array( 'type' => 'integer', 'sanitize' => 'category' ),
			);
	}
	return array();
}

/**
 * Which sayid_field_map() key applies to a given page, based on the
 * template it's using — null when the page's template has no fields of
 * its own (the plain "Page" template, page-contact.php, etc).
 */
function sayid_page_field_map_key( $post_id ) {
	return sayid_field_map_key_for_template( get_page_template_slug( $post_id ) );
}

function sayid_field_map_key_for_template( $template ) {
	if ( 'page-home-content.php' === $template ) {
		return 'page:home-content';
	}
	if ( 'page-form.php' === $template ) {
		return 'page:form';
	}
	if ( 'page-archive.php' === $template ) {
		return 'page:archive';
	}
	return null;
}

add_action( 'init', function () {
	// register_post_meta() is keyed by the real post type, so both `page`
	// field maps (home-content, form) get registered under 'page' — which
	// map applies to a given page is decided per-template at render/save
	// time by sayid_page_field_map_key(), not by registration.
	$field_maps = array(
		'post'          => array( 'post' ),
		'sayid_lab'     => array( 'sayid_lab' ),
		'sayid_project' => array( 'sayid_project' ),
		'page'          => array( 'page:home-content', 'page:form', 'page:archive' ),
	);
	foreach ( $field_maps as $post_type => $keys ) {
		foreach ( $keys as $field_map_key ) {
			foreach ( sayid_field_map( $field_map_key ) as $key => $def ) {
				register_post_meta( $post_type, $key, array(
					'type'          => 'array' === $def['type'] ? 'array' : $def['type'],
					'single'        => true,
					'show_in_rest'  => 'array' === $def['type']
						? array( 'schema' => array( 'type' => 'array', 'items' => array( 'type' => 'integer' ) ) )
						: true,
					'auth_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
				) );
			}
		}
	}
} );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'sayid_article_fields', __( 'جزئیات نوشته', 'sayid' ), 'sayid_render_meta_box', 'post', 'normal', 'high' );
	add_meta_box( 'sayid_lab_fields', __( 'جزئیات آیتم آزمایشگاه', 'sayid' ), 'sayid_render_meta_box', 'sayid_lab', 'normal', 'high' );
	add_meta_box( 'sayid_project_fields', __( 'جزئیات پروژه', 'sayid' ), 'sayid_render_meta_box', 'sayid_project', 'normal', 'high' );
} );

/**
 * Which page-specific fields to show depends on the page's *template*, not
 * just its post type — add_meta_boxes_page (fires for `page` only) lets
 * each template register its own box, and 'args' passes the right
 * sayid_field_map() key through to sayid_render_meta_box() so the same
 * generic renderer works for both without mixing the two field sets.
 */
add_action( 'add_meta_boxes_page', function ( $post ) {
	$field_map_key = sayid_page_field_map_key( $post->ID );
	if ( 'page:home-content' === $field_map_key ) {
		add_meta_box( 'sayid_home_content_fields', __( 'محتوای صفحه‌ی اصلی (هیرو)', 'sayid' ), 'sayid_render_meta_box', 'page', 'normal', 'high', array( 'field_map_key' => $field_map_key ) );
	} elseif ( 'page:form' === $field_map_key ) {
		add_meta_box( 'sayid_form_fields', __( 'تنظیمات فرم', 'sayid' ), 'sayid_render_meta_box', 'page', 'normal', 'high', array( 'field_map_key' => $field_map_key ) );
	} elseif ( 'page:archive' === $field_map_key ) {
		add_meta_box( 'sayid_archive_fields', __( 'تنظیمات آرشیو', 'sayid' ), 'sayid_render_meta_box', 'page', 'normal', 'high', array( 'field_map_key' => $field_map_key ) );
	}
} );

function sayid_field_label( $key ) {
	$labels = array(
		'sayid_source_url'        => __( 'لینک منبع (اختیاری)', 'sayid' ),
		'sayid_subtitle'          => __( 'زیرعنوان / چکیده کوتاه', 'sayid' ),
		'sayid_short_description' => __( 'توضیح کوتاه', 'sayid' ),
		'sayid_status'            => __( 'وضعیت', 'sayid' ),
		'sayid_started_at'        => __( 'تاریخ شروع', 'sayid' ),
		'sayid_shipped_at'        => __( 'تاریخ انتشار', 'sayid' ),
		'sayid_tools'             => __( 'ابزارها / فناوری‌ها (با ویرگول جدا کنید)', 'sayid' ),
		'sayid_repo_url'          => __( 'لینک ریپازیتوری', 'sayid' ),
		'sayid_live_url'          => __( 'لینک نسخه‌ی زنده', 'sayid' ),
		'sayid_role'              => __( 'نقش', 'sayid' ),
		'sayid_organization'      => __( 'سازمان / کارفرما', 'sayid' ),
		'sayid_project_type'      => __( 'نوع پروژه', 'sayid' ),
		'sayid_date_start'        => __( 'شروع', 'sayid' ),
		'sayid_date_end'          => __( 'پایان', 'sayid' ),
		'sayid_collaborators'     => __( 'همکاران', 'sayid' ),
		'sayid_challenge'         => __( 'چالش', 'sayid' ),
		'sayid_context'           => __( 'زمینه و بستر', 'sayid' ),
		'sayid_process'           => __( 'فرایند', 'sayid' ),
		'sayid_decisions'         => __( 'تصمیم‌های کلیدی', 'sayid' ),
		'sayid_outcome'           => __( 'نتیجه', 'sayid' ),
		'sayid_metrics'           => __( 'متریک‌ها (اختیاری)', 'sayid' ),
		'sayid_external_url'      => __( 'لینک پورتفولیوی خارجی (اختیاری)', 'sayid' ),
		'sayid_featured_homepage' => __( 'نمایش در صفحه‌ی اصلی', 'sayid' ),
		'sayid_homepage_priority' => __( 'اولویت نمایش در صفحه‌ی اصلی (عدد کوچک‌تر = بالاتر)', 'sayid' ),
		'sayid_gallery'           => __( 'گالری تصاویر (شناسه رسانه، با ویرگول)', 'sayid' ),
		'sayid_related_posts'     => __( 'نوشته‌های مرتبط (یادداشت/مقاله)', 'sayid' ),
		'sayid_related_lab'       => __( 'آیتم‌های آزمایشگاه مرتبط', 'sayid' ),
		'sayid_related_projects'  => __( 'پروژه‌های مرتبط', 'sayid' ),
		'sayid_hero_greeting'     => __( 'متن سلام (پیش از اسم)', 'sayid' ),
		'sayid_hero_name'         => __( 'اسم', 'sayid' ),
		'sayid_hero_name_suffix'  => __( 'پسوند اسم (مثلاً «هستم»)', 'sayid' ),
		'sayid_hero_role'         => __( 'عنوان شغلی', 'sayid' ),
		'sayid_hero_lede'         => __( 'توضیح زیر عنوان شغلی', 'sayid' ),
		'sayid_hero_cta_label'    => __( 'متن دکمه', 'sayid' ),
		'sayid_hero_rotator_phrases'  => __( 'عبارت‌های چرخشی (هرکدوم در یک خط)', 'sayid' ),
		'sayid_form_recipient_email' => __( 'ایمیل گیرنده (خالی = ایمیل مدیر سایت)', 'sayid' ),
		'sayid_archive_category'     => __( 'دسته‌بندی (خالی = همه‌ی نوشته‌ها)', 'sayid' ),
	);
	return isset( $labels[ $key ] ) ? $labels[ $key ] : $key;
}

function sayid_related_post_type_for_key( $key ) {
	$map = array(
		'sayid_related_posts'    => 'post',
		'sayid_related_lab'      => 'sayid_lab',
		'sayid_related_projects' => 'sayid_project',
	);
	return isset( $map[ $key ] ) ? $map[ $key ] : null;
}

function sayid_render_meta_box( $post, $metabox = array() ) {
	wp_nonce_field( 'sayid_save_meta', 'sayid_meta_nonce' );
	$field_map_key = isset( $metabox['args']['field_map_key'] ) ? $metabox['args']['field_map_key'] : $post->post_type;
	$fields        = sayid_field_map( $field_map_key );
	if ( empty( $fields ) ) {
		return;
	}
	echo '<table class="form-table sayid-meta-table"><tbody>';
	foreach ( $fields as $key => $def ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( sayid_field_label( $key ) ) . '</label></th><td>';
		sayid_render_meta_field( $key, $def, $value, $post->ID );
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

function sayid_render_meta_field( $key, $def, $value, $current_post_id = 0 ) {
	if ( 'sayid_status' === $key ) {
		echo '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '">';
		foreach ( sayid_lab_statuses() as $slug => $label ) {
			echo '<option value="' . esc_attr( $slug ) . '" ' . selected( $value, $slug, false ) . '>' . esc_html( $label ) . '</option>';
		}
		echo '</select>';
		return;
	}

	if ( 'category' === $def['sanitize'] ) {
		echo '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '">';
		echo '<option value="">' . esc_html__( '— همه —', 'sayid' ) . '</option>';
		foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) {
			echo '<option value="' . esc_attr( $category->term_id ) . '" ' . selected( (int) $value, $category->term_id, false ) . '>' . esc_html( $category->name ) . '</option>';
		}
		echo '</select>';
		return;
	}

	$related_type = sayid_related_post_type_for_key( $key );
	if ( $related_type ) {
		$value = is_array( $value ) ? $value : array();
		$posts = get_posts( array(
			'post_type'      => $related_type,
			'post_status'    => 'publish',
			'posts_per_page' => 200,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post__not_in'   => array( $current_post_id ),
		) );
		echo '<select multiple size="6" name="' . esc_attr( $key ) . '[]" id="' . esc_attr( $key ) . '" style="min-width:320px">';
		foreach ( $posts as $p ) {
			echo '<option value="' . esc_attr( $p->ID ) . '" ' . selected( in_array( (int) $p->ID, $value, true ), true, false ) . '>' . esc_html( $p->post_title ) . '</option>';
		}
		echo '</select>';
		return;
	}

	switch ( $def['sanitize'] ) {
		case 'bool':
			echo '<label><input type="checkbox" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="1" ' . checked( $value, '1', false ) . '> ' . esc_html__( 'فعال', 'sayid' ) . '</label>';
			break;
		case 'int':
			echo '<input type="number" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="small-text">';
			break;
		case 'url':
			echo '<input type="url" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="large-text" placeholder="https://">';
			break;
		case 'textarea':
			echo '<textarea name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" rows="3" class="large-text">' . esc_textarea( $value ) . '</textarea>';
			break;
		case 'wysiwyg':
			wp_editor( $value, $key, array( 'textarea_rows' => 6, 'media_buttons' => false, 'teeny' => true ) );
			break;
		case 'int_array':
			$display = is_array( $value ) ? implode( ',', $value ) : '';
			echo '<input type="text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $display ) . '" class="large-text" placeholder="12,34,56">';
			break;
		default:
			echo '<input type="text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="large-text">';
	}
}

/**
 * Saves one field per its declared sanitize type. Pulled out of the
 * save_post handler below so it can be reused with two different
 * "field missing from $_POST" policies — see there for why pages need a
 * different one than every other post type.
 */
function sayid_save_field_value( $post_id, $key, $def ) {
	if ( sayid_related_post_type_for_key( $key ) ) {
		$raw = isset( $_POST[ $key ] ) ? (array) $_POST[ $key ] : array();
		update_post_meta( $post_id, $key, array_map( 'absint', $raw ) );
		return;
	}

	switch ( $def['sanitize'] ) {
		case 'bool':
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? '1' : '' );
			break;
		case 'int':
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? absint( $_POST[ $key ] ) : 0 );
			break;
		case 'url':
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? esc_url_raw( wp_unslash( $_POST[ $key ] ) ) : '' );
			break;
		case 'textarea':
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) : '' );
			break;
		case 'wysiwyg':
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? wp_kses_post( wp_unslash( $_POST[ $key ] ) ) : '' );
			break;
		case 'int_array':
			$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
			$raw = is_array( $raw ) ? implode( ',', $raw ) : (string) $raw;
			$ids = array_filter( array_map( 'absint', explode( ',', $raw ) ) );
			update_post_meta( $post_id, $key, array_values( $ids ) );
			break;
		case 'status':
			$slug = isset( $_POST[ $key ] ) ? sanitize_key( $_POST[ $key ] ) : '';
			if ( array_key_exists( $slug, sayid_lab_statuses() ) ) {
				update_post_meta( $post_id, $key, $slug );
			}
			break;
		case 'category':
			$term_id = isset( $_POST[ $key ] ) ? absint( $_POST[ $key ] ) : 0;
			update_post_meta( $post_id, $key, ( $term_id && term_exists( $term_id, 'category' ) ) ? $term_id : 0 );
			break;
		default:
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '' );
	}
}

add_action( 'save_post', function ( $post_id, $post ) {
	if ( ! isset( $_POST['sayid_meta_nonce'] ) || ! wp_verify_nonce( $_POST['sayid_meta_nonce'], 'sayid_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( 'page' === $post->post_type ) {
		// Which of the three page field-maps is "the" one for this page
		// isn't reliable to determine here: WordPress core writes
		// _wp_page_template *after* save_post fires (so
		// get_page_template_slug() can lag by one save), and it's
		// unconfirmed whether the block editor's meta-box-compat POST even
		// carries page_template at all (Page Attributes is native/REST
		// there). So instead of guessing which map applies and saving all
		// of it (blanking the other maps' keys in the process), every page
		// field is saved only if its own key is actually present in
		// $_POST — which only happens when the metabox that key belongs to
		// was the one actually rendered (and thus submitted) this time.
		// None of the three page field-maps use the 'bool' sanitize type,
		// where a missing key would ambiguously mean "unchecked" instead
		// of "not this page's field" — if one ever does, this needs
		// revisiting.
		$page_fields = array_merge(
			sayid_field_map( 'page:home-content' ),
			sayid_field_map( 'page:form' ),
			sayid_field_map( 'page:archive' )
		);
		foreach ( $page_fields as $key => $def ) {
			if ( isset( $_POST[ $key ] ) ) {
				sayid_save_field_value( $post_id, $key, $def );
			}
		}
		return;
	}

	foreach ( sayid_field_map( $post->post_type ) as $key => $def ) {
		sayid_save_field_value( $post_id, $key, $def );
	}
}, 10, 2 );
