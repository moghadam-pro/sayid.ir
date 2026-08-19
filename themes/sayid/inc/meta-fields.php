<?php
/**
 * Post meta registration (REST-exposed) + native meta boxes. No ACF or
 * third-party fields plugin dependency.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sayid_field_map( $post_type ) {
	$common_related = array(
		'sayid_related_notes'    => array( 'type' => 'array', 'sanitize' => 'int_array' ),
		'sayid_related_articles' => array( 'type' => 'array', 'sanitize' => 'int_array' ),
		'sayid_related_lab'      => array( 'type' => 'array', 'sanitize' => 'int_array' ),
		'sayid_related_projects' => array( 'type' => 'array', 'sanitize' => 'int_array' ),
	);

	switch ( $post_type ) {
		case 'sayid_note':
			return array_merge(
				array( 'sayid_source_url' => array( 'type' => 'string', 'sanitize' => 'url' ) ),
				$common_related
			);

		case 'post':
			return array_merge(
				array(
					'sayid_subtitle'          => array( 'type' => 'string', 'sanitize' => 'text' ),
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
	}
	return array();
}

add_action( 'init', function () {
	foreach ( array( 'sayid_note', 'post', 'sayid_lab', 'sayid_project' ) as $post_type ) {
		foreach ( sayid_field_map( $post_type ) as $key => $def ) {
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
} );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'sayid_note_fields', __( 'جزئیات یادداشت', 'sayid' ), 'sayid_render_meta_box', 'sayid_note', 'normal', 'high' );
	add_meta_box( 'sayid_article_fields', __( 'جزئیات نوشته', 'sayid' ), 'sayid_render_meta_box', 'post', 'normal', 'high' );
	add_meta_box( 'sayid_lab_fields', __( 'جزئیات آیتم آزمایشگاه', 'sayid' ), 'sayid_render_meta_box', 'sayid_lab', 'normal', 'high' );
	add_meta_box( 'sayid_project_fields', __( 'جزئیات پروژه', 'sayid' ), 'sayid_render_meta_box', 'sayid_project', 'normal', 'high' );
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
		'sayid_related_notes'     => __( 'یادداشت‌های مرتبط', 'sayid' ),
		'sayid_related_articles'  => __( 'نوشته‌های مرتبط', 'sayid' ),
		'sayid_related_lab'       => __( 'آیتم‌های آزمایشگاه مرتبط', 'sayid' ),
		'sayid_related_projects'  => __( 'پروژه‌های مرتبط', 'sayid' ),
	);
	return isset( $labels[ $key ] ) ? $labels[ $key ] : $key;
}

function sayid_related_post_type_for_key( $key ) {
	$map = array(
		'sayid_related_notes'    => 'sayid_note',
		'sayid_related_articles' => 'post',
		'sayid_related_lab'      => 'sayid_lab',
		'sayid_related_projects' => 'sayid_project',
	);
	return isset( $map[ $key ] ) ? $map[ $key ] : null;
}

function sayid_render_meta_box( $post ) {
	wp_nonce_field( 'sayid_save_meta', 'sayid_meta_nonce' );
	$fields = sayid_field_map( $post->post_type );
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

	foreach ( sayid_field_map( $post->post_type ) as $key => $def ) {
		if ( sayid_related_post_type_for_key( $key ) ) {
			$raw = isset( $_POST[ $key ] ) ? (array) $_POST[ $key ] : array();
			update_post_meta( $post_id, $key, array_map( 'absint', $raw ) );
			continue;
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
			default:
				update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '' );
		}
	}
}, 10, 2 );
