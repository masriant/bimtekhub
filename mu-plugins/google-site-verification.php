<?php
/**
 * Plugin Name: Google Site Verification
 * Description: Adds Google site verification meta tag.
 * Version: 1.0
 * Author: Masrianto
 * Author URI: https://bimtekhub.com
 */

function google_site_verification() {
    echo '<meta name="google-site-verification" content="av2rpSIYvX4gfgjThO7LgHUib4d-JfZTgwMo_3w_BME" />' . "\n";
}

add_action('wp_head', 'google_site_verification');
