(function() {
  var data, list, token;

  list = $('.notifications-container').notificationList();

  list.notificationList('checkPagination');

  token = $('meta[name="csrf-token"]').attr('content');

  data = {
    _token: token,
    _method: 'put'
  };

  $.post('/admin/inoplate-notification/notifications/mark-as-viewed', data);

}).call(this);

//# sourceMappingURL=index.js.map
