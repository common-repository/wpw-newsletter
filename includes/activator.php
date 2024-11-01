<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// ajax
function wpw_newsletter_ajaxurl() {
   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}
add_action('wp_head', 'wpw_newsletter_ajaxurl');



// Załączanie plików ze skryptami
function wpw_newsletter_add_scripts() {
	wp_register_script('wpw_newsletter_search', plugins_url('../js/search.js', __FILE__));
	wp_register_script('wpw_newsletter_pagination', plugins_url('../js/list.pagination.min.js', __FILE__));
	wp_enqueue_script('wpw_newsletter_search');
	wp_enqueue_script('wpw_newsletter_pagination');
}
function wpw_newsletter_front_scripts() {
	wp_register_script('wpw_newsletter_scripts', plugins_url('../js/script.js', __FILE__));
	wp_enqueue_script('wpw_newsletter_scripts');
}

add_action('admin_head', 'wpw_newsletter_add_scripts');
add_action('wp_footer', 'wpw_newsletter_front_scripts');



// Załączanie pliku ze stylami
function wpw_newsletter_add_stylesheet() {
	wp_register_style('wpw_newsletter_stlyes', plugins_url('../styles/style.css', __FILE__));
	wp_enqueue_style('wpw_newsletter_stlyes');
}
function wpw_newsletter_add_front_end_stylesheet() {
	wp_register_style('wpw_newsletter_stlyes', plugins_url('../styles/front-style.css', __FILE__));
	wp_enqueue_style('wpw_newsletter_stlyes');
}
add_action('admin_head', 'wpw_newsletter_add_stylesheet');
add_action('wp_footer', 'wpw_newsletter_add_front_end_stylesheet');



// Tworzenie zakładek wtyczki
function wpw_newsletter_create_menu() {
	$icon_url = plugins_url('../img/wpw_newsletter_icon.png', __FILE__);
	add_menu_page('WPW-Home-Panel', 'WPW-Newsletter', 'manage_options', 'wpw-home', 'wpw_menu_home_page', $icon_url, 61);
	add_submenu_page('wpw-home', 'WPW-Newsletter', 'Lista Subskrybentów', 'manage_options', 'wpw-subscribers-page', 'wpw_newsletter_admin_page');
}
add_action('admin_menu', 'wpw_newsletter_create_menu');



// Tworzenie tabeli w bazie danych
function wpw_newsletter_activate() {
	global $wpdb;
	$table_name = $wpdb->prefix . "subscribers"; 
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
	 	  email VARCHAR(50) NOT NULL
	)$charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
register_activation_hook(plugin_dir_path(dirname(__FILE__)) . 'wpw-newsletter.php', 'wpw_newsletter_activate');



// Shortcode Subskrybcji
function wpw_newsletter_subscribe(){
global $current_user;
$result = '';
$zmienna = "test";
$token = wp_create_nonce('wpw_newsletter_token');
$result = <<<EOD
<div id="subscribe" class="wrap">
<form method="post" action="">
<input type="hidden" id="_wpnonce" name="_wpnonce" value="{$token}" />
<input type="hidden" name="_wp_http_referer" value="/newsletter/" />
<input id="emailsubscribe" type="email" name="subscribe" placeholder="e-mail">
<input id="sendSubscribe" class="button-primary" type="button" value="Dodaj do listy">
</form>
<div class="alert">
</div>
</div>
EOD;
return $result;
}
add_shortcode('wpw-newsletter-subscribe', 'wpw_newsletter_subscribe');
add_filter('widget_text','do_shortcode');



// Shortcode wypisu z subskrybcji
function wpw_newsletter_unsubscribe(){
global $current_user;
$result = '';
$token = wp_create_nonce('wpw_newsletter_token');
$result = <<<EOD
<div class="wrap">
<form method="post" action="">
<input type="hidden" id="_wpnonce" name="_wpnonce" value="{$token}" />
<input type="hidden" name="_wp_http_referer" value="/newsletter/" />
<input id="emailUnsubscribe" type="email" name="unsubscribe" placeholder="e-mail" value="">
<input id="sendUnSubscribe" class="button-primary" type="button" value="Wypisz">
</form>
<div class="alert">
</div>
</div>
EOD;
return $result;
}
add_shortcode('wpw-newsletter-unsubscribe', 'wpw_newsletter_unsubscribe');


// WP Nonce
function wpw_newsletter_nonce ($wpwtoken) {
	$nonce = $_REQUEST['_wpnonce'];
	
	if (wp_verify_nonce($nonce, $wpwtoken) == false) {
		echo '<p class="wpw-error">Przepraszamy, dane nie mogły zostać przesłane</p>';
		wp_die();
		return false;
	}
}
?>