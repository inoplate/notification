list = $ '.notifications-container'
            .notificationList()

list.notificationList 'checkPagination'

list.on 'notifications.notification.list.loaded', () ->
    $ul = $ 'ul', this
    $ 'li', $ul
        .each () ->
            id = $ this
                    .data 'id'

            viewed = $ this
                        .data 'viewed'

            if viewed == 0
                markAsViewed id
            

markAsViewed = (id) ->
    token = $ 'meta[name="csrf-token"]'
                .attr 'content'
    data = 
        _token : token
        _method : "PUT"

    $.post "/admin/inoplate-notification/notifications/#{id}/mark-as-viewed", data, (result) ->
        console.log result
