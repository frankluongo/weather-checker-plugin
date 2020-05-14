<?php

class Weather_Checker_Admin {
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->admin_plugin_title = 'Weather Checker';
    $this->admin_plugin_page_slug = 'weather-checker';

    $this->admin_weather_checker_setting_section = 'weather-checker-api-key-section';

    $this->admin_plugin_api_key_name = 'weather_api_key';
    $this->admin_plugin_temperature_threshold = 'temperature_threshold';
    $this->admin_plugin_fee_amount = 'fee_amount';
    $this->admin_plugin_fee_name = 'fee_name';

    // Add Weather Checker To Admin Menu
    add_action('admin_menu', array($this, 'add_weather_checker_to_admin_menu'));
    // Add Weather Checker Information to That Menu Page
    add_action('admin_init', array($this, 'add_weather_checker_admin_settings'));
  }

  //
  // Admin Functionality
  //

  public function add_weather_checker_to_admin_menu() {
    add_menu_page(
      $this->admin_plugin_title, // Title of the Page
      $this->admin_plugin_title, // Title of The Settings Page
      'manage_options', // User capabilities for this page
      $this->admin_plugin_page_slug, // Page Slug
      array($this, 'render_weather_checker_admin_page_content'), // Callback Function when initializing the page
      'dashicons-store', // Icon
      110
    );
  }

  public function add_weather_checker_admin_settings() {
    add_settings_section(
      $this->admin_weather_checker_setting_section,
      'Weather Checker',
      '',
      $this->admin_plugin_page_slug
    );

    $this->register_weather_checker_fields();
    $this->add_weather_checker_setting_fields();
  }

  public function register_weather_checker_fields() {
    register_setting(
      $this->admin_plugin_page_slug,
      $this->admin_plugin_api_key_name
    );

    register_setting(
      $this->admin_plugin_page_slug,
      $this->admin_plugin_temperature_threshold
    );

    register_setting(
      $this->admin_plugin_page_slug,
      $this->admin_plugin_fee_amount
    );

    register_setting(
      $this->admin_plugin_page_slug,
      $this->admin_plugin_fee_name
    );
  }

  public function add_weather_checker_setting_fields() {
    add_settings_field(
      'weather-checker-api-key', // ID For Setting
      'Weather Checker API Key', // Field Title
      array($this, 'render_api_key_input'), // Callback Function to Render the Form
      $this->admin_plugin_page_slug, // Page The Setting Shows Up On
      $this->admin_weather_checker_setting_section // The Section the Setting Shows Up On
    );

    add_settings_field(
      'weather-checker-temperature-threshold', // ID For Setting
      'Temperature Threshold', // Field Title
      array($this, 'render_temperature_threshold'), // Callback Function to Render the Form
      $this->admin_plugin_page_slug, // Page The Setting Shows Up On
      $this->admin_weather_checker_setting_section // The Section the Setting Shows Up On
    );

    add_settings_field(
      'weather-checker-fee-amount', // ID For Setting
      'Fee Amount', // Field Title
      array($this, 'render_fee_amount'), // Callback Function to Render the Form
      $this->admin_plugin_page_slug, // Page The Setting Shows Up On
      $this->admin_weather_checker_setting_section // The Section the Setting Shows Up On
    );

    add_settings_field(
      'weather-checker-fee-name', // ID For Setting
      'Fee Name', // Field Title
      array($this, 'render_fee_name'), // Callback Function to Render the Form
      $this->admin_plugin_page_slug, // Page The Setting Shows Up On
      $this->admin_weather_checker_setting_section // The Section the Setting Shows Up On
    );
  }


  public function render_weather_checker_admin_page_content() {
    require_once plugin_dir_path(__FILE__) . '/partials/weather-checker-admin-display.php';
  }

  //
  // Render Form Fields
  //

  public function render_api_key_input() {
    $api_key = esc_attr(get_option($this->admin_plugin_api_key_name, ''));
    ?>
    <input id="weather-api" type="text" name="<?php echo $this->admin_plugin_api_key_name; ?>" placeholder="Weather Website API" value="<?php echo $api_key; ?>">
    <?php
  }

  public function render_temperature_threshold() {
    $temp_threshold = esc_attr(get_option($this->admin_plugin_temperature_threshold, ''));
    ?>
    <input id="temp-threshold" type="number" name="<?php echo $this->admin_plugin_temperature_threshold; ?>" placeholder="Temperature Threshold" value="<?php echo $temp_threshold; ?>">
    <?php
  }

  public function render_fee_name() {
    $fee_name = esc_attr(get_option($this->admin_plugin_fee_name, ''));
    ?>
    <input id="fee-name" type="text" name="<?php echo $this->admin_plugin_fee_name; ?>" placeholder="Fee Name" value="<?php echo $fee_name; ?>">
    <?php
  }

  public function render_fee_amount() {
    $fee_amount = esc_attr(get_option($this->admin_plugin_fee_amount, ''));
    ?>
    <input id="fee-amount" type="number" name="<?php echo $this->admin_plugin_fee_amount; ?>" placeholder="Fee Amount" value="<?php echo $fee_amount; ?>">
    <?php
  }

  //
  // Add Styles & Scripts
  //

  public function enqueue_styles() {
    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/weather-checker-admin.css', array(), $this->version, 'all' );
	}

  public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/weather-checker-admin.js', array( 'jquery' ), $this->version, false );
  }
}
