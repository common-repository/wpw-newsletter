<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Widok zakładki głównej wtyczki
function wpw_menu_home_page() {
	?>
	<div class="wpw">
		<div class="wrap">
			<div class="row">

				<img src="<?php echo plugins_url('/img/icon.jpg', dirname(__FILE__)); ?>">
				<h2 class="wpwh">WPW-Newsletter</h2>
			</div>
		</div>
		<div class="wrap description">
			<div class="row">
				<div class="col-1">
					<h2>Opis</h2>
				</div>
				<div class="col-6">
					<h2>Plugin for WordPress to capture subscribers</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-1 bg01">
					<img src="<?php echo plugins_url('img/description.jpg', dirname(__FILE__)); ?>">
				</div>
				<div class="col-6">
					<h2><a href="http://wpwtyczki.pl/" target="_blank">www.wpwtyczki.pl</a></h2>
				</div>
			</div>
		</div>
		<div class="wrap">
			<div class="row">
				<div class="col-12">
					<h2>Funkcje</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-1 bg02">
					<img src="<?php echo plugins_url('/img/function.jpg', dirname(__FILE__)); ?>">
				</div>
				<div class="col-6">
					<ul>
						<li>- Dodawanie do strony (za pomocą shortcodów) formularza umożliwiającego zapis do newslettera</li>
						<li>- Zapisywanie/wypisywanie z newslettera</li>
						<li>- Wyświetlanie listy subskrybentów w panelu administracyjnym</li>
						<li>- Pobieranie pliku .txt z listą subskrybentów</li>
						<li>- Usuwanie wybranych adresów z poziomu panelu adminisrtacyjnego</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="wrap">
			<div class="row">
				<div class="col-12">
					<h3>Instrukcja</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-1 bg01">
					<img src="<?php echo plugins_url('/img/instruction.jpg', dirname(__FILE__)); ?>">
				</div>
				<div class="col-11">
					<p>Dodaj wybrany shortcode w dowolnym miejscu na stronie.</p><br /><br />

					<p>Tworzenie pola dodającego adres e-mail do subskrybcji odbywa się za pomocą shortcod'u: <strong>[wpw-newsletter-subscribe][/wpw-newsletter-subscribe]</strong></p><br /><br />

					<p>Tworzenie pola wypisu z subskrybcji odbywa się za pomocą shortcod'u: <strong>[wpw-newsletter-unsubscribe][/wpw-newsletter-unsubscribe]</strong></p>
				</div>
			</div>
		</div>
		</div>
	<?php
}

// Widok zakładki listy subskrybentów
function wpw_newsletter_admin_page(){
	?>

<?php
	// Wyświetlanie listy subskrybentów
	global $wpdb;
	$table_name = $wpdb->prefix . "subscribers";
	$retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name" );
	$token = wp_create_nonce('wpw_newsletter_token_export');
?>
	<div class="wpw">
	<div class="wrap">
		<div class="row">
			<img src="<?php echo plugins_url('/img/icon.jpg', dirname(__FILE__)); ?>">
			<h2 class="wpwh">WPW-Newsletter</h2>
		</div>
	</div>

	<div class="wrap export">
		<div class="row">
			<div class="col-12">
				<h2>Export zapisanych subskrybcji do pliku .txt</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-1 bg01">
				<img src="<?php echo plugins_url('/img/instruction.jpg', dirname(__FILE__)); ?>">
			</div>
			<div class="col-11">
					<form method="post" action="">
						<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo $token ?>" />
						<input type="hidden" name="_wp_http_referer" value="/newsletter/" />
						<input type="hidden" name="export">
						<input class="button" type="submit" value="Export">
					</form> 
					<?php
					 if(isset($_POST['export']) && current_user_can('administrator')) {
					 	echo '<a class="button" href="' . esc_url(plugins_url('wpw-newsletter-list.txt', __FILE__)) . '" download>Pobierz</a>';
					 	
					} else {
						$uchwyt = fopen(plugin_dir_path( __FILE__ ) . '/wpw-newsletter-list.txt', "w");
						unlink(plugin_dir_path( __FILE__ ) . '/wpw-newsletter-list.txt');
					} ?>
			</div>
		</div>
	</div>

	<div class="wrap">
		<div class="row">
			<div class="col-12">
				<h2>Lista Subskrybentów</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-1 bg02">
				<img src="<?php echo plugins_url('/img/function.jpg', dirname(__FILE__)); ?>">
			</div>
			<div class="col-11">
				<div id="search-list">
				  	<input class="search" placeholder="szukaj..."/>
				    <span class="sort" data-sort="mail"></span>
					<ul class="list">
						<?php foreach ($retrieve_data as $retrieved_data){ ?>
						<li>
							<form method="post" action="">
								<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo $token ?>" />
								<input type="hidden" name="_wp_http_referer" value="/newsletter/" />
								<input type="hidden" name="x" value="<?php echo esc_html($retrieved_data->email);?>">
								<input class="button-primary" type="submit" value="x">
							</form>
							<?php echo '<p class="mail">' . esc_html($retrieved_data->email) . '</p>'?>
						</li>
					<?php 
					}
					?>
					</ul>
					<ul class="pagination"></ul>
				</div>
			</div>
		</div>
	</div>
	</div>
<?php
	
}
?>