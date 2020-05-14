(function($) {
  "use strict";

  var WeatherChecker = {
    init: function(apiKey) {
      this.apiKey = apiKey;
      this.threshold = 70;
      this.weatherChecker = document.querySelector(
        '[data-js="WeatherChecker"]'
      );
      if (this.weatherChecker === null) {
        return;
      }
      this.getWeatherCheckerElements();
      this.observeForm();
    },

    getWeatherCheckerElements: function() {
      this.form = this.weatherChecker.querySelector(
        '[data-js="WeatherChecker.Form"]'
      );
      this.notification = this.weatherChecker.querySelector(
        '[data-js="WeatherChecker.Notification"]'
      );
      this.notificationContentSlot = this.notification.querySelector(
        '[data-js="WeatherChecker.Notification.ContentSlot"]'
      );
      this.notificationClose = this.notification.querySelector(
        '[data-js="WeatherChecker.Notification.Close"]'
      );
      this.weatherAPIUrlBase =
        "http://api.openweathermap.org/data/2.5/forecast?";
    },

    //
    // Observers
    //

    observeForm: function() {
      this.form.addEventListener("submit", this.handleFormSubmit.bind(this));
    },

    observeNotificationCloseButton: function() {
      this.notificationClose.addEventListener(
        "click",
        this.handleCloseButtonClick.bind(this)
      );
    },

    //
    // Handlers
    //

    handleCloseButtonClick: function() {
      this.closeNotification();
    },

    handleFormSubmit: function(event) {
      event.preventDefault();
      this.zipCode = this.form.querySelector(
        '[data-js="WeatherChecker.Form.Zip"]'
      ).value;
      this.sendAjaxRequest();
    },

    //
    // Actions
    //

    closeNotification: function() {
      this.notification.setAttribute("data-active", false);
    },

    sendAjaxRequest: function() {
      var requestUrl =
        this.weatherAPIUrlBase +
        "zip=" +
        this.zipCode +
        ",us&APPID=" +
        this.apiKey;
      $.ajax({
        url: requestUrl,
        error: this.handleWeatherAPIResponseError.bind(this),
        success: this.handleWeatherAPIResponseSuccess.bind(this)
      });
    },

    handleWeatherAPIResponseError: function(data) {
      console.log(data);
      console.log("error!");
    },

    handleWeatherAPIResponseSuccess: function(data) {
      this.collectForecastDetails(data);
      this.buildNotificationMessage();
      this.buildNotificationElement();
      this.appendNotificationElement();
      this.observeNotificationCloseButton();
    },

    collectForecastDetails: function(data) {
      this.city = data.city.name;
      this.fiveDayForecast = data.list
        .slice(0, 5)
        .map(this.getEachDayTemperature.bind(this));
      this.avgTemp = this.getAverageTemperature(this.fiveDayForecast);
    },

    buildNotificationMessage: function() {
      if (this.avgTemp >= this.threshold) {
        this.notificationHeading = "Oh No!";
        this.notificationText =
          "Since the average temperature for " +
          this.city +
          " is going to be above " +
          this.threshold +
          " degrees (F) over the next 5 days. We'll likely need to ship your order with dry ice to keep it cool. This will raise the cost of shipping, but ensure your chocolate is edible upon arrival!";
      } else {
        this.notificationHeading = "Good News!";
        this.notificationText =
          "Since the average temperature for " +
          this.city +
          " is going to be below " +
          this.threshold +
          " degrees (F) over the next 5 days. We likely won't need to ship your order with dry ice to keep it cool!";
      }
    },

    buildNotificationElement: function() {
      this.notificationContent = document.createElement("div");
      this.notificationContent.classList.add(
        "weather-checker-notification__content"
      );
      this.notificationContent.innerHTML =
        '<h3 class="weather-checker-notification-content__heading">' +
        this.notificationHeading +
        '</h3><p class="weather-checker-notification-content__text">' +
        this.notificationText +
        "</p>";
    },

    appendNotificationElement: function() {
      this.notificationContentSlot.innerHTML = "";
      this.notificationContentSlot.appendChild(this.notificationContent);
      this.notification.setAttribute("data-active", true);
    },

    //
    // HELPERS
    //

    getEachDayTemperature: function(day) {
      return Math.round(this.convertKelvinToFahrenheit(day.main.temp));
    },

    getAverageTemperature: function(days) {
      var reducer = function(acc, value) {
        return acc + value;
      };
      return Math.round(days.reduce(reducer) / 5);
    },

    convertKelvinToFahrenheit: function(kelvin) {
      return ((kelvin - 273.15) * 9) / 5 + 32;
    }
  };

  document.addEventListener("DOMContentLoaded", function() {
    WeatherChecker.init(api.key);
  });
})(jQuery);
