<?php

class Weather_Checker_Public {
	private $plugin_name;
  private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
    $this->version = $version;
    add_shortcode('weather_checker', array($this, 'public_content'));
    add_action('woocommerce_cart_calculate_fees', array($this, 'check_for_temperature_fee') );
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/weather-checker-public.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/weather-checker-public.js', array( 'jquery' ), $this->version, false );
    $api = array(
      'key' =>  get_option('weather_api_key', '')
    );
    wp_localize_script( $this->plugin_name, 'api', $api );
  }

  public function public_content() {
    require_once plugin_dir_path(__FILE__) . '/partials/weather-checker-public-display.php';
  }

  public function check_for_temperature_fee() {
    global $woocommerce, $post;
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
      return;
    }

    $shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
    if (count($shipping_methods) > 0) {
      foreach($shipping_methods as $shipping_method){
        if(strpos($shipping_method, 'local_pickup') !== false){
          return;
        }
      }
    }

    $zipcode = WC()->customer->get_shipping_postcode();
    $country = strtolower(WC()->customer->get_shipping_country()) ?: 'us';
    $api_key = get_option('weather_api_key', '');
    $temp_threshold = get_option('temperature_threshold', '');
    $fee = get_option('fee_amount', '');
    $fee_name = get_option('fee_name', '');


    $response = $this->get_forecast($zipcode, $country, $api_key);
    if ($response === NULL) {
      return;
    }

    $five_days = $this->get_first_five_days($response->list);
    $average_temp = $this->get_average_temp($five_days);

    if ( $average_temp > $temp_threshold ) :
      $woocommerce->cart->add_fee( $fee_name, $fee, true, 'standard' );
    endif;
  }

  public function get_forecast($zipcode, $country, $api_key) {
    $res = @file_get_contents("http://api.openweathermap.org/data/2.5/forecast?zip=$zipcode,$country&APPID=$api_key");
    if ($res === false) {
      return NULL;
    } else {
      return json_decode($res);
    }
  }

  public function get_first_five_days($list) {
    $five_days = array_slice($list, 0, 5);
    $temps = array_map(array($this, 'get_day_temp'), $five_days);
    return $temps;
  }

  public function get_day_temp($day) {
    return $day->main->temp;
  }

  public function get_average_temp($days) {
    $sum = array_sum($days);
    $avg = $sum / 5;
    $avgInFahrenheit = $this->convert_kelvin_to_fahrenheit($avg);
    return $avgInFahrenheit;
  }

  public function convert_kelvin_to_fahrenheit($temp) {
    return ((($temp - 273.15) * 9) / 5) + 32;
  }

}
