<?php
/*
* Matomo HTTP Tracking
* Plugin Name: Matomo HTTP Tracking
* Plugin URI: https://dennis-heinri.ch/projekte
* Description: Adds the Matomo HTTP Tracking Code to your WordPress Site
* Version: 0.0.1
* Requires at least: 6.4.1
* Requires PHP: 8.1
* Author: Dennis Heinrich
* Author URI: https://dennis-heinri.ch
* License: BSD-3-Clause
* License URI: https://opensource.org/licenses/BSD-3-Clause
* Network: false
*/

// Load the dependencies
require_once("vendor/autoload.php");

// This is the name of the plugin
const MATOMO_HTTP_TRACKING_PLUGIN_NAME = "Matomo HTTP";
// The named prefix of the plugin
const MATOMO_HTTP_TRACKING_PLUGIN_NAMESPACE = "matomo_http_tracking";
// These are the IDs of the available settings options
const MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID = "matomo_http_tracking_site_id"; // Tracking Site ID
const MATOMO_HTTP_TRACKING_SETTING_ID_TRACKING_URL = "matomo_http_tracking_url"; // Tracking URL
const MATOMO_HTTP_TRACKING_SETTING_ID_TOKEN = "matomo_http_tracking_token"; // Tracking Token

/**
 * Tracks a visit to the current page with the credentials from the settings page
 * @see matomoHttp_validateSettings() pre-condition for running this function must be passed
 * @return void
 */
function matomoHttp_trackPageVisit(): void
{
    // The validation of the settings must be valid
    if(!matomoHttp_validateSettings()) return;

    // Gather the tracker data
    $matomoSiteId = esc_attr( get_option(MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID) );
    $matomoUrl = esc_attr( get_option(MATOMO_HTTP_TRACKING_SETTING_ID_TRACKING_URL) );
    $matomoToken = esc_attr( get_option(MATOMO_HTTP_TRACKING_SETTING_ID_TOKEN) );
    $matomoPageTitle = get_the_title();

    // Build the tracker object and track the view
    $matomoTracker = new MatomoTracker($matomoSiteId, $matomoUrl);
    $matomoTracker->setTokenAuth($matomoToken);
    $matomoTracker->doTrackPageView($matomoPageTitle);
}

/**
 * Registers the settings for the plugin
 * - Site ID (numeric)
 * - Tracking URL (string)
 * - Token (string)
 * @return void
 */
function matomoHttp_registerSettings(): void
{
    register_setting(MATOMO_HTTP_TRACKING_PLUGIN_NAMESPACE, MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID);
    register_setting(MATOMO_HTTP_TRACKING_PLUGIN_NAMESPACE, MATOMO_HTTP_TRACKING_SETTING_ID_TRACKING_URL);
    register_setting(MATOMO_HTTP_TRACKING_PLUGIN_NAMESPACE, MATOMO_HTTP_TRACKING_SETTING_ID_TOKEN);
}

/**
 * Check if the settings are valid, by checking if the detailed validation array doesn't contain entries.
 * @see matomoHttp_validateSettingsDetailed() Validates the settings, if returned array is empty, they are valid
 * @return bool
 */
function matomoHttp_validateSettings(): bool {
    return count(matomoHttp_validateSettingsDetailed()) <= 0;
}

/**
 * Validates the settings for the plugin and returns an array of failed conditions.
 * If the array is empty, the settings are fully validated and usable.
 * It checks for the following:
 * - Site ID is not empty
 * - Site ID is numeric
 * - Site ID is greater than 0
 * - URL is not empty
 * - URL is a valid URL
 * - Token is not empty
 * @return array If array empty, settings are valid, otherwise associated array with messages
 */
function matomoHttp_validateSettingsDetailed(): array {
    $failedValidations = [];
    // Fields are not allowed to be empty
    if(empty(get_option(MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID))) $failedValidations[MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID][] = "Site ID is empty";
    if(empty(get_option(MATOMO_HTTP_TRACKING_SETTING_ID_TRACKING_URL))) $failedValidations[MATOMO_HTTP_TRACKING_SETTING_ID_TRACKING_URL][] = "Tracking URL is empty";
    if(empty(get_option(MATOMO_HTTP_TRACKING_SETTING_ID_TOKEN))) $failedValidations[MATOMO_HTTP_TRACKING_SETTING_ID_TOKEN][] = "Token is empty";
    // Site ID has to be numeric
    if(!is_numeric(get_option(MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID))) $failedValidations[MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID][] = "Site ID isn't numeric";
    // Site ID has to be greater than 0
    if(get_option(MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID) <= 0) $failedValidations[MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID][] = "Site ID isn't greater or equal 1";
    // URL has to be a valid URL
    if(!filter_var(get_option(MATOMO_HTTP_TRACKING_SETTING_ID_TRACKING_URL), FILTER_VALIDATE_URL)) $failedValidations[MATOMO_HTTP_TRACKING_SETTING_ID_TRACKING_URL][] = "The tracking url seems to be not valid";
    return $failedValidations;
}

/**
 * Adds the settings page to the admin menu for this plugin
 * @return void
 */
function matomoHttp_addSettingsPage(): void
{
    add_menu_page(
        MATOMO_HTTP_TRACKING_PLUGIN_NAME,
        MATOMO_HTTP_TRACKING_PLUGIN_NAME,
        "manage_options",
        MATOMO_HTTP_TRACKING_PLUGIN_NAMESPACE,
        "matomoHttp_renderSettingsPage"
    );
}

/**
 * Returns the setting locale by its identifier
 * @param $keyIdentifier string The id of the setting
 * @return string
 */
function matomoHttp_getSettingName(string $keyIdentifier): string {
    return match ($keyIdentifier) {
        MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID => "Matomo (Site-ID)",
        MATOMO_HTTP_TRACKING_SETTING_ID_TOKEN => "Matomo (Token)",
        MATOMO_HTTP_TRACKING_SETTING_ID_TRACKING_URL => "Matomo (Instance URL)",
        default => $keyIdentifier,
    };
}

/**
 * Renders the settings page for this plugin by including the settings_page.php file
 * @return void
 */
function matomoHttp_renderSettingsPage(): void {
    include "settings_page.php";
}

add_action('admin_menu', 'matomoHttp_addSettingsPage');
matomoHttp_registerSettings();
matomoHttp_trackPageVisit();