(function() {
  var NotificationNavbar, navbar,
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  NotificationNavbar = (function() {
    function NotificationNavbar($element, options) {
      var websocketUrl;
      this.$element = $element;
      this.__onWsConnected = bind(this.__onWsConnected, this);
      console.log(options);
      this.$markup = options.markup || this.__getDefaultMarkup();
      this.payloads = options.payloads || {};
      this.endInfo = options.endInfo || '.end-info';
      this.wrapper = options.wrapper || 'ul';
      this.holder = options.holder || 'li';
      this.nextLoader = options.nextLoader || '.loader';
      this.$nextLoader = $(this.nextLoader, this.$element);
      this.$wrapper = $(this.wrapper, this.$element);
      this.markAsReadUrl = options.markAsReadUrl;
      websocketUrl = this.$element.data('websocket');
      this.$wrapper.slimScroll({
        height: options.height || '100px'
      }).bind('slimscrolling', (function(_this) {
        return function(e, pos) {
          return _this.checkPagination();
        };
      })(this));
      $(window).scroll((function(_this) {
        return function() {
          return _this.checkPagination();
        };
      })(this));
      $(".slimScrollBar").hide();
      options = {
        'skipSubprotocolCheck': true,
        'maxRetries': 60,
        'retryDelay': 2000
      };
      new ab.connect(websocketUrl, this.__onWsConnected, this.__onWsClosed, options);
    }

    NotificationNavbar.prototype.getMarkup = function() {
      return this.$markup;
    };

    NotificationNavbar.prototype.checkPagination = function() {
      var url;
      if (this.$nextLoader.is(':within-viewport') && !this.$nextLoader.data('loading')) {
        url = $('a', this.$nextLoader).prop('href');
        return this.__loadMore(url);
      }
    };

    NotificationNavbar.prototype.__onWsConnected = function(session) {
      console.info('connected');
      session.subscribe('notification', (function(_this) {
        return function(topic, data) {
          return _this.$wrapper.prepend(_this.__buildMarkup(data.notification));
        };
      })(this));
      return session.subscribe('notification.count', (function(_this) {
        return function(topic, data) {
          return console.log(data);
        };
      })(this));
    };

    NotificationNavbar.prototype.__onWsClosed = function() {
      return console.warn('WebSocket connection closed');
    };

    NotificationNavbar.prototype.__loadMore = function(url, payloads, callback) {
      var items;
      if (payloads == null) {
        payloads = {};
      }
      payloads = this.__normalizePayloads(payloads);
      url = "" + url + payloads;
      items = [];
      this.$element.trigger("notifications.notification.navbar.loading");
      this.$nextLoader.data('loading', true);
      return $.get(url, (function(_this) {
        return function(result) {
          var i, item, len, ref;
          if (typeof callback === 'function') {
            callback.apply(_this);
          }
          ref = result.notifications.data;
          for (i = 0, len = ref.length; i < len; i++) {
            item = ref[i];
            $('li.loader', _this.$wrapper).before(_this.__buildMarkup(item));
          }
          if (result.notifications.next_page_url) {
            $('a', _this.$nextLoader).prop('href', result.notifications.next_page_url);
            _this.$nextLoader.data('loading', false);
            $(_this.endInfo, _this.$element).addClass('hide');
            _this.checkPagination();
          } else {
            _this.$nextLoader.addClass('hide');
            $(_this.endInfo, _this.$element).removeClass('hide');
          }
          return _this.$element.trigger("notifications.notification.navbar.loaded");
        };
      })(this), 'json');
    };

    NotificationNavbar.prototype.__normalizePayloads = function(payloads) {
      var globalPayloads;
      globalPayloads = this.payloads;
      if (typeof payloads === 'function') {
        payloads = payloads.apply(this);
      }
      if (typeof this.payloads === 'function') {
        globalPayloads = this.payloads.apply(this);
      }
      payloads = $.extend(globalPayloads, payloads, true);
      payloads = this.__stringifyPayload(payloads);
      return payloads;
    };

    NotificationNavbar.prototype.__getDefaultMarkup = function() {
      return $("<li class=\"clearfix\"> <div class=\"message\"></div> </li>");
    };

    NotificationNavbar.prototype.__buildMarkup = function(data) {
      var $markup;
      $markup = this.getMarkup().clone();
      if ($markup instanceof $) {
        $('.message', $markup).text(data.message);
        $markup.data('id', data.id);
        $markup.data('viewed', data.viewed);
      } else if (typeof $markup === 'function') {
        $markup = $markup.apply(this, [data]);
      }
      return $markup;
    };

    NotificationNavbar.prototype.__stringifyPayload = function(payloads) {
      var k, string, v;
      string = "";
      for (k in payloads) {
        v = payloads[k];
        string = string + "&" + k + "=" + v;
      }
      return string;
    };

    return NotificationNavbar;

  })();

  $.fn.notificationNavbar = function(option) {
    var args, defaults;
    args = arguments;
    defaults = {
      height: "100px"
    };
    this.each(function() {
      var $this, argsToSent, data, k, options, v;
      $this = $(this);
      data = $this.data('notification.navbar');
      options = $.extend({}, defaults, $this.data(), typeof option === 'object' && option);
      if (!data) {
        $this.data('notification.navbar', (data = new NotificationNavbar($this, options)));
      }
      if (typeof option === 'string') {
        argsToSent = [];
        for (k in args) {
          v = args[k];
          if (k > 0) {
            argsToSent.push(v);
          }
        }
        return data[option].apply(data, argsToSent);
      }
    });
    return this;
  };

  navbar = $('.notifications-menu').notificationNavbar({
    wrapper: "ul.notifications-wrapper",
    height: "200px"
  });

  navbar.notificationNavbar('checkPagination');

}).call(this);

//# sourceMappingURL=navbar.js.map
