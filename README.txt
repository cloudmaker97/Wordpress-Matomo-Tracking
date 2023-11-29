=== Plugin Name ===
Contributors: cloudmaker97
Tags: tracking, matomo, analytics, cookieless
Requires at least: 6.4.1
Tested up to: 6.4.1
Stable tag: 0.0.7
License: BSD-3-Clause
License URI: https://opensource.org/licenses/BSD-3-Clause

This plugin provides a feature to implement the Matomo tracking code (via. HTTP API), cookieless.

== Description ==

If you want to use Matomo (formerly Piwik) to track your website, you need to add the tracking code to your website.
This plugin provides a feature to implement the Matomo HTTP-Tracking, so you can use Matomo without cookies.

== Installation ==

1. You upload the plugin files to the `/wp-content/plugins/wp-matomo-http-tracking` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings to configure the plugin
    1. Enter the URL of your Matomo installation (e.g. https://matomo.example.com)
    2. Enter the ID of your website (you can find it in the Matomo backend)
    3. Enter the Token Auth (you can find it in the Matomo backend)

== Changelog ==

= 0.0.1 =
- Initialized the plugin