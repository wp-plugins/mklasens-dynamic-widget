<?php
defined( 'ABSPATH' ) or die( 'You can\'t access this file directly!');
/**
 * Plugin Name: mklasen's Dynamic Widget
 * Plugin URI: http://plugins.mklasen.com/dynamic-widget/
 * Description: Add per-page/post configurable WYSIWYG editors as a widget to your sidebar.
 * Version: 1.0
 * Author: Marinus Klasen
 * Author URI: http://mklasen.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
 
 Copyright 2015  Marinus Klasen  (email : marinus@mklasen.nl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 
 */
	
	/* **************************
	#
	#  Include Styles and Scripts for Front-End
	#
	*************************** */ 
		
	function mklasens_dynamic_widget_enqueue() {
		wp_register_script('mklasens-dynamic-widget-js', plugins_url('js/index.js', __FILE__), array('jquery'), '', true);
		wp_register_style('mklasens-dynamic-widget-css', plugins_url('css/index.css', __FILE__), false);
		wp_enqueue_script('jquery');
		wp_enqueue_script('mklasens-dynamic-widget-js');
		wp_enqueue_style('mklasens-dynamic-widget-css');
	}
	
	add_action( 'wp_enqueue_scripts', 'mklasens_dynamic_widget_enqueue' );
	
		
	/**
	 * Adds a box to the main column on the Post and Page edit screens.
	 */
	function mklasens_dynamic_widget_metabox() {
	
		$screens = get_post_types();
	
		foreach ( $screens as $screen ) {
	
			add_meta_box(
				'mklasen_add_dynamic_widget_content',
				__( 'Dynamic Widget', 'mklasens-dynamic-widget-textdomain' ),
				'mklasens_dynamic_widget_media_button_metabox_content',
				$screen,
				'side'
			);
		}
	}
	add_action( 'add_meta_boxes', 'mklasens_dynamic_widget_metabox' );
	
	/**
	 * Prints the box content.
	 * 
	 * @param WP_Post $post The object for the current post/page.
	 */
	function mklasens_dynamic_widget_media_button_metabox_content( $post ) {
		
		$currentContent = get_post_meta($post->ID, 'mklasens-dynamic-text-content', true);
		
		wp_editor( htmlspecialchars_decode($currentContent), 'mklasens-dynamic-text-editor', $settings = array('textarea_name'=>'mklasens-dynamic-text-input', 'media_buttons' => false) );
	}
	
	/**
	 *  Save the custom content metaboxes
	 */
	function mk_save_my_postdata( $post_id ) {                   
    if (!empty($_POST['mklasens-dynamic-text-input'])) {
        
        $data = $_POST['mklasens-dynamic-text-input'];
        update_post_meta($post_id, 'mklasens-dynamic-text-content', $data);
    }
}
add_action( 'save_post', 'mk_save_my_postdata' );  
	
	
	/**
	 * 
	 * Include admin js/css
	 * 
	 */
	
	function mklasens_dynamic_widget_scripts() {
		wp_enqueue_script( 'mklasen-dynamic-widget-admin-js', plugin_dir_url( __FILE__ ) . '/js/admin.js', 'jquery', '', true );
		wp_enqueue_style( 'mklasen-dynamic-widget-admin-css', plugin_dir_url( __FILE__ ) . '/css/admin.css' );
	}
	
	add_action('admin_enqueue_scripts', 'mklasens_dynamic_widget_scripts');
	
	
	/**
	 * 
	 * Register widget
	 * 
	 */
	 
	 class mkDynamicWidget extends WP_Widget {
	
		function mkDynamicWidget() {
			// Instantiate the parent object
			$widget_ops = array( 'classname' => 'mklasens-dynamic-widget', 'description' => __( "Add specific widget content per page/post." ) );
			$this->WP_Widget( 'mkDynamicWidget', __('Dynamic Widget'), $widget_ops);
			parent::__construct( false, 'mklasen\'s Dynamic Widget' );
		}
	
		function widget( $args, $instance ) {
			global $post;
			
			extract($args);
			
			$baseContent = $instance['text'];
			$currentContent = get_post_meta($post->ID, 'mklasens-dynamic-text-content', true);
			
			if ($baseContent || $currentContent) {			
				echo $before_widget;
				// Widget output
				
					
					if (!empty($currentContent)) {
						echo do_shortcode($currentContent);
					} else {
						echo $baseContent;
					}
					
				echo $after_widget;	
			}
		}
	
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['text'] =  $new_instance['text'];
	        return $instance;
		}
	
		function form( $instance ) {
			
			
			/// Output voor widget backend
			
			// word via ajax ingeladne, wp editor niet direct compatible.
			
			echo '<p>This widget can be configured on page/post level.</p>';
			
			
			//$instance = wp_parse_args( (array) $instance, array( 'text' => '' ) );
			//$text = format_to_edit($instance['text']);
			//wp_editor( htmlspecialchars_decode('test'), 'mklasens-dynamic-text-editor-widget', $settings = array('textarea_name'=>'mklasens-dynamic-text-widget-input') );
			$settings = array(
				'textarea_name' => 'mklasens-dynamic-text-widget-input',
				'media_buttons' => false,
				'tinymce' => true
			);
			//wp_editor('test', 'mktest', $settings);
		}
	}
	
	function mklasen_register_widgets() {
		register_widget( 'mkDynamicWidget' );
	}
	
	add_action( 'widgets_init', 'mklasen_register_widgets' );
	
	