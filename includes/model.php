<?php
	
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
$table_name = $wpdb->prefix.'subscribers';
	

// zapisywanie danych z formularza do bazy
if (isset($_POST['subscribe']) 
	&& preg_match('/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,10}$/', $_POST['subscribe'])
	) {

	wpw_newsletter_nonce('wpw_newsletter_token');

 	$email_adress = sanitize_email($_POST['subscribe']);
 	
	$sql = $wpdb->prepare("SELECT 'email' FROM $table_name WHERE (email LIKE %s)", $email_adress);
	$results = $wpdb->get_results($sql, OBJECT );

 	if (empty($results)) {
 		$sql = $wpdb->prepare("INSERT INTO $table_name (email) VALUES (%s)", $email_adress);
 		$wpdb->query($sql);
 		echo '<p class="wpw-succesfull">Dziękujemy za zapisanie się do naszego newslettera</p>';
 	} else {
 		echo '<p class="wpw-error">Podany adres istnieje już w bazie</p>';
	}
	wp_die();
	return false;
 }


// wypisywanie z bazy
 else if (isset($_POST['unsubscribe'])
 	&& (!empty($_POST['unsubscribe']))
 	&& preg_match('/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,10}$/', $_POST['unsubscribe'])
 	) {

 	wpw_newsletter_nonce('wpw_newsletter_token');

 	$email_adress = sanitize_email($_POST['unsubscribe']);

	$sql = $wpdb->prepare("SELECT 'email' FROM $table_name WHERE (email LIKE %s)", $email_adress);
	$results = $wpdb->get_results($sql, OBJECT );

 	if (empty($results)) {
 		echo '<p class="wpw-error">Podany adres nie znajduje się na naszej liście.</p>';
 	} else {
 		$sql = $wpdb->prepare("DELETE FROM $table_name WHERE (email LIKE %s)", $email_adress);
 		$wpdb->query($sql);
    	echo '<p class="wpw-succesfull">Twój adres został usunięty z naszej listy.</p>';
	}
	wp_die();
	return false;
} 


// zapisywanie danych do pliku
 else if (isset($_POST['export']) && current_user_can('administrator'))  {

 	wpw_newsletter_nonce('wpw_newsletter_token_export');

	$retrieve_data = $wpdb->get_results("SELECT * FROM $table_name");

	$fh = fopen(plugin_dir_path( __FILE__ ) . '/wpw-newsletter-list.txt', "w");
	fclose($fh);

	foreach ($retrieve_data as $retrieved_data){
		$tekst = esc_textarea($retrieved_data->email . ";" . "\n");
		$fh = fopen(plugin_dir_path( __FILE__ ) . 'wpw-newsletter-list.txt', "a");
		fwrite($fh, $tekst);
		fclose($fh);
	}
 } 


// usuwanie danych z panelu administratora
else if (isset($_POST['x']) && ($_POST['x'] != NULL) && current_user_can('administrator')) {

	wpw_newsletter_nonce('wpw_newsletter_token_export');

	$retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name" );

	foreach ($retrieve_data as $retrieved_data){
		if (($_POST['x'] == $retrieved_data->email)) {
			$delete_email = sanitize_email($_POST['x']);
 	 		$sql_delete = $wpdb->prepare("DELETE FROM {$table_name} WHERE email LIKE %s" ,$delete_email);
			$wpdb->query($sql_delete);

		}
	}
}


// dane nie przeszły walidacji
 else if (isset($_POST['subscribe']) || isset($_POST['unsubscribe']) && !preg_match('/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,10}$/', $_POST['subscribe'])) {
 	echo '<p class="wpw-error">Wprowadź poprawnie adres e-mail.</p>';
 	wp_die();
 	return false;
 }

?>