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
    echo '<meta name="facebook-domain-verification" content="y2er2fbvtu3to2s1h4l5gxex84rak5" />' . "\n";
}

add_action('wp_head', 'google_site_verification');
