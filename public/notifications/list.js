(function() {
  var NotificationList,
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  NotificationList = (function() {
    function NotificationList($element, options) {
      var websocketUrl;
      this.$element = $element;
      this.__onWsConnected = bind(this.__onWsConnected, this);
      this.$markup = options.markup || this.__getDefaultMarkup();
      this.payloads = options.payloads || {};
      this.endInfo = options.endInfo || '.end-info';
      this.wrapper = options.holder || 'ul';
      this.holder = options.holder || 'li';
      this.nextLoader = options.nextLoader || '.loader';
      this.$nextLoader = $(this.nextLoader, this.$element);
      this.$wrapper = $(this.wrapper, this.$element);
      this.markAsReadUrl = options.markAsReadUrl;
      websocketUrl = this.$element.data('websocket');
      $(window).scroll((function(_this) {
        return function() {
          return _this.checkPagination();
        };
      })(this));
      options = {
        'skipSubprotocolCheck': true,
        'maxRetries': 60,
        'retryDelay': 2000
      };
      new ab.connect(websocketUrl, this.__onWsConnected, this.__onWsClosed, options);
    }

    NotificationList.prototype.getMarkup = function() {
      return this.$markup;
    };

    NotificationList.prototype.checkPagination = function() {
      var url;
      console.log(this.$nextLoader.is(':within-viewport'));
      if (this.$nextLoader.is(':within-viewport') && !this.$nextLoader.data('loading')) {
        url = $('a', this.$nextLoader).prop('href');
        return this.__loadMore(url);
      }
    };

    NotificationList.prototype.__onWsConnected = function(session) {
      console.info('connected');
      return session.subscribe('notification', (function(_this) {
        return function(topic, data) {
          return _this.$wrapper.prepend(_this.__buildMarkup(data.notification));
        };
      })(this));
    };

    NotificationList.prototype.__onWsClosed = function() {
      return console.warn('WebSocket connection closed');
    };

    NotificationList.prototype.__loadMore = function(url, payloads, callback) {
      var items;
      if (payloads == null) {
        payloads = {};
      }
      payloads = this.__normalizePayloads(payloads);
      url = "" + url + payloads;
      items = [];
      this.$element.trigger("notifications.notification.list.loading");
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
            _this.$wrapper.append(_this.__buildMarkup(item));
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
          return _this.$element.trigger("notifications.notification.list.loaded");
        };
      })(this), 'json');
    };

    NotificationList.prototype.__normalizePayloads = function(payloads) {
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

    NotificationList.prototype.__getDefaultMarkup = function() {
      return $("<li class=\"clearfix\"> <span class=\"pull-left message\"></span> <span class=\"pull-right datetime\"></span> </li>");
    };

    NotificationList.prototype.__buildMarkup = function(data) {
      var $markup, datetime, dayDiff, now;
      $markup = this.getMarkup().clone();
      if ($markup instanceof $) {
        datetime = moment(data.created_at);
        now = moment(Date.now());
        dayDiff = now.diff(datetime, 'days');
        if (dayDiff > 1) {
          datetime = moment(data.created_at).format('LL');
        } else {
          datetime = moment(data.created_at).fromNow();
        }
        $('.message', $markup).text(data.message);
        $('.datetime', $markup).text(datetime);
        $markup.data('id', data.id);
        $markup.data('viewed', data.viewed);
      } else if (typeof $markup === 'function') {
        $markup = $markup.apply(this, [data]);
      }
      return $markup;
    };

    NotificationList.prototype.__stringifyPayload = function(payloads) {
      var k, string, v;
      string = "";
      for (k in payloads) {
        v = payloads[k];
        string = string + "&" + k + "=" + v;
      }
      return string;
    };

    return NotificationList;

  })();

  $.fn.notificationList = function(option) {
    var args, defaults;
    args = arguments;
    defaults = {
      height: "450px"
    };
    this.each(function() {
      var $this, argsToSent, data, k, options, v;
      $this = $(this);
      data = $this.data('notification.list');
      options = $.extend({}, defaults, $this.data(), typeof option === 'object' && option);
      if (!data) {
        $this.data('notification.list', (data = new NotificationList($this, options)));
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

}).call(this);

//# sourceMappingURL=list.js.map
