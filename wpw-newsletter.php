<?php 
/*
Plugin Name: WPW-Newsletter
Plugin URI:  http://wpwtyczki.pl
Description: Plugin for WordPress to capture subscribers
Version:     1.0
Author:      WPWtyczki
Author URI:  http://wpwtyczki.pl
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/*
WPW-Newsletter is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WPW-Newsletter is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {URI to Plugin License}.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once(ABSPATH.'wp-includes/pluggable.php');
require_once('includes/activator.php');
require_once('includes/view.php');
require_once('includes/model.php');


?>