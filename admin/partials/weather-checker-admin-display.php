<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       frankluongo.com
 * @since      1.0.0
 *
 * @package    Weather_Checker
 * @subpackage Weather_Checker/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div>
  <?php settings_errors();?>
  <form method="POST" action="options.php">
    <?php settings_fields('weather-checker');?>
    <?php do_settings_sections('weather-checker')?>
    <?php submit_button();?>
  </form>
</div>
