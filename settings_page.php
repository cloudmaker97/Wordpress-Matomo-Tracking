<div>
    <h1><?= MATOMO_HTTP_TRACKING_PLUGIN_NAME ?></h1>

    <?php
    if (!matomoHttp_validateSettings()) {
        ?>
        <section class="error">
            <h2>Configuration failed! - Tracking is disabled</h2>
            <p>
                Please enter your Matomo Site ID, URL and Token in the settings page.
            </p>

            <ul>
                <?php
                    foreach (matomoHttp_validateSettingsDetailed() as $key => $messageArray) {
                        foreach ($messageArray as $message) {
                            echo sprintf("<li><b>%s</b>: %s</li>", matomoHttp_getSettingName($key), $message);
                        }
                    }
                ?>
            </ul>

            <hr>
            <h3>Further hints & tips</h3>
            <ol>
                <li>You can find the <code>Site ID</code> and <code>Token</code> in the Matomo Dashboard.</li>
                <li>The URL has to be the full URL to the matomo instance. (e.g. <code>https://matomo.example.com</code>)
                </li>
            </ol>
        </section>
        <style>
            section.error {
                margin: 0 1em 0 0;
                padding: 1em;
                background-color: #e1e1e1;
                border: 2px solid red;
            }
        </style>
        <?php
    } else {
        echo "<p>Settings are valid</p>";
    }
    ?>

    <section>
        <h2>Tracking Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields(MATOMO_HTTP_TRACKING_PLUGIN_NAMESPACE); ?>
            <?php do_settings_sections(MATOMO_HTTP_TRACKING_PLUGIN_NAMESPACE); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?= matomoHttp_getSettingName(MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID) ?></th>
                    <td>
                        <label>
                            <input type="number" name="<?= MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID; ?>"
                                   value="<?= esc_attr(get_option(MATOMO_HTTP_TRACKING_SETTING_ID_SITE_ID)); ?>"/>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?= matomoHttp_getSettingName(MATOMO_HTTP_TRACKING_SETTING_ID_TRACKING_URL) ?></th>
                    <td>
                        <label>
                            <input type="text" name="<?= MATOMO_HTTP_TRACKING_SETTING_ID_TRACKING_URL; ?>"
                                   value="<?= esc_attr(get_option(MATOMO_HTTP_TRACKING_SETTING_ID_TRACKING_URL)); ?>"/>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?= matomoHttp_getSettingName(MATOMO_HTTP_TRACKING_SETTING_ID_TOKEN) ?></th>
                    <td>
                        <label>
                            <input type="text" name="<?= MATOMO_HTTP_TRACKING_SETTING_ID_TOKEN; ?>"
                                   value="<?= esc_attr(get_option(MATOMO_HTTP_TRACKING_SETTING_ID_TOKEN)); ?>"
                            />
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </section>
</div>