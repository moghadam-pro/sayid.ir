<?php
/**
 * Native Elementor widgets for the homepage sections, so editors compose the
 * page visually in Elementor instead of dropping raw Shortcode widgets.
 *
 * Only loaded when Elementor is active (see sayid-site-core.php). Every
 * widget is a thin wrapper that calls the same Sayid_Core_Render methods the
 * shortcodes use — no markup or behavior is duplicated, only exposed through
 * Elementor's widget UI with a couple of editorial controls (item counts).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'elementor/elements/categories_registered', function ( $elements_manager ) {
	$elements_manager->add_category(
		'sayid',
		array(
			'title' => __( 'Sayid — sayid.ir', 'sayid-site-core' ),
			'icon'  => 'fa fa-plug',
		)
	);
} );

abstract class Sayid_Elementor_Widget_Base extends \Elementor\Widget_Base {
	public function get_categories() {
		return array( 'sayid' );
	}
	public function get_icon() {
		return 'eicon-post-list';
	}
}

class Sayid_Widget_Now extends Sayid_Elementor_Widget_Base {
	public function get_name() { return 'sayid_now'; }
	public function get_title() { return __( 'Sayid — این روزها', 'sayid-site-core' ); }
	protected function render() { echo Sayid_Core_Render::now(); } // phpcs:ignore
}

class Sayid_Widget_Selected_Work extends Sayid_Elementor_Widget_Base {
	public function get_name() { return 'sayid_selected_work'; }
	public function get_title() { return __( 'Sayid — کارهای منتخب', 'sayid-site-core' ); }
	protected function render() { echo Sayid_Core_Render::selected_work(); } // phpcs:ignore
}

class Sayid_Widget_Lab extends Sayid_Elementor_Widget_Base {
	public function get_name() { return 'sayid_lab'; }
	public function get_title() { return __( 'Sayid — آزمایشگاه', 'sayid-site-core' ); }
	protected function render() { echo Sayid_Core_Render::lab(); } // phpcs:ignore
}

class Sayid_Widget_Signature extends Sayid_Elementor_Widget_Base {
	public function get_name() { return 'sayid_signature'; }
	public function get_title() { return __( 'Sayid — طراحی × کد × هوش مصنوعی', 'sayid-site-core' ); }
	protected function render() { echo Sayid_Core_Render::signature(); } // phpcs:ignore
}

class Sayid_Widget_Notes extends Sayid_Elementor_Widget_Base {
	public function get_name() { return 'sayid_notes'; }
	public function get_title() { return __( 'Sayid — یادداشت‌های تازه', 'sayid-site-core' ); }

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'محتوا', 'sayid-site-core' ) ) );
		$this->add_control(
			'count',
			array(
				'label'   => __( 'تعداد', 'sayid-site-core' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 5,
				'min'     => 3,
				'max'     => 8,
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo Sayid_Core_Render::notes( (int) $settings['count'] ); // phpcs:ignore
	}
}

class Sayid_Widget_Featured_Article extends Sayid_Elementor_Widget_Base {
	public function get_name() { return 'sayid_featured_article'; }
	public function get_title() { return __( 'Sayid — نوشته منتخب', 'sayid-site-core' ); }
	protected function render() { echo Sayid_Core_Render::featured_article(); } // phpcs:ignore
}

class Sayid_Widget_Connect extends Sayid_Elementor_Widget_Base {
	public function get_name() { return 'sayid_connect'; }
	public function get_title() { return __( 'Sayid — حرف بزنیم', 'sayid-site-core' ); }
	protected function render() { echo Sayid_Core_Render::connect(); } // phpcs:ignore
}

class Sayid_Widget_Scroll_Cue extends Sayid_Elementor_Widget_Base {
	public function get_name() { return 'sayid_scroll_cue'; }
	public function get_title() { return __( 'Sayid — نشانگر اسکرول', 'sayid-site-core' ); }
	protected function render() { echo Sayid_Core_Render::scroll_cue(); } // phpcs:ignore
}

class Sayid_Widget_Theme_Switch extends Sayid_Elementor_Widget_Base {
	public function get_name() { return 'sayid_theme_switch'; }
	public function get_title() { return __( 'Sayid — سوییچ تم', 'sayid-site-core' ); }
	protected function render() { echo Sayid_Core_Render::theme_switch(); } // phpcs:ignore
}

class Sayid_Widget_Homepage_Deferred extends Sayid_Elementor_Widget_Base {
	public function get_name() { return 'sayid_homepage_deferred'; }
	public function get_title() { return __( 'Sayid — بدنه‌ی معلق صفحه اصلی', 'sayid-site-core' ); }

	public function get_icon() { return 'eicon-lock-user'; }

	protected function render() {
		echo Sayid_Core_Render::deferred_homepage(); // phpcs:ignore
	}
}

class Sayid_Widget_Related extends Sayid_Elementor_Widget_Base {
	public function get_name() { return 'sayid_related'; }
	public function get_title() { return __( 'Sayid — مطالب مرتبط', 'sayid-site-core' ); }
	protected function render() {
		$post_id = get_the_ID();
		if ( $post_id ) {
			echo Sayid_Core_Render::related( $post_id ); // phpcs:ignore
		}
	}
}

add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
	$widgets = array(
		'Sayid_Widget_Now',
		'Sayid_Widget_Selected_Work',
		'Sayid_Widget_Lab',
		'Sayid_Widget_Signature',
		'Sayid_Widget_Notes',
		'Sayid_Widget_Featured_Article',
		'Sayid_Widget_Connect',
		'Sayid_Widget_Scroll_Cue',
		'Sayid_Widget_Theme_Switch',
		'Sayid_Widget_Homepage_Deferred',
		'Sayid_Widget_Related',
	);
	foreach ( $widgets as $widget_class ) {
		$widgets_manager->register( new $widget_class() );
	}
} );
