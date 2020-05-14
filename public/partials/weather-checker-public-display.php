<div
  class="weather-checker"
  data-js="WeatherChecker"
  >
  <form class="weather-checker__form" data-js="WeatherChecker.Form">
    <input data-js="WeatherChecker.Form.Zip" type="number" placeholder="Zipcode" required>
    <input type="submit" value="Submit">
  </form>
  <div
    class="weather-checker__notification"
    data-js="WeatherChecker.Notification"
    >
    <div class="weather-checker-notification__container">
      <button
        aria-label="close"
        class="weather-checker-notification__close-button"
        data-js="WeatherChecker.Notification.Close"
        >
        <div class="weather-checker-notification-close-button__bar bar-1"></div>
        <div class="weather-checker-notification-close-button__bar bar-2"></div>
      </button>
      <div
        class="weather-checker-notification__content-slot"
        data-js="WeatherChecker.Notification.ContentSlot"
        >
      </div>
    </div>
  </div>
</div>
