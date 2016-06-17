(function() {
  var list, markAsViewed;

  list = $('.notifications-container').notificationList();

  list.notificationList('checkPagination');

  list.on('notifications.notification.list.loaded', function() {
    var $ul;
    $ul = $('ul', this);
    return $('li', $ul).each(function() {
      var id, viewed;
      id = $(this).data('id');
      viewed = $(this).data('viewed');
      if (viewed === 0) {
        return markAsViewed(id);
      }
    });
  });

  markAsViewed = function(id) {
    var data, token;
    token = $('meta[name="csrf-token"]').attr('content');
    data = {
      _token: token,
      _method: "PUT"
    };
    return $.post("/admin/inoplate-notification/notifications/" + id + "/mark-as-viewed", data, function(result) {
      return console.log(result);
    });
  };

}).call(this);

//# sourceMappingURL=index.js.map
