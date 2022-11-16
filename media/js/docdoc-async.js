(function () {
  var queue = [];
  var isInitialized = false;
  window.DdFeedAsyncInit = function () {
    isInitialized = true;
    queue.forEach(function(config) {
      DdWidget(config);
    });
    queue = [];
  };
  window.DdWidgetAsync = function(config) {
    if (isInitialized) {
      DdWidget(config);
    } else {
      queue.push(config);
    }
  }
})();