class NotificationNavbar
    constructor: (@$element, options) ->
        @$markup = options.markup || @__getDefaultMarkup()
        @payloads = options.payloads || {}
        @endInfo = options.endInfo || '.end-info'
        @wrapper = options.wrapper || 'ul'
        @holder = options.holder || 'li'
        @nextLoader = options.nextLoader || '.loader'
        @$nextLoader = $ @nextLoader, @$element
        @$wrapper = $ @wrapper, @$element
        @markAsReadUrl = options.markAsReadUrl
        websocketUrl = @$element.data 'websocket'
        
        @$wrapper.slimScroll
                height: options.height || '100px'
            .bind 'slimscrolling', (e, pos) =>
                @checkPagination()

        $ window
            .scroll () =>
                @checkPagination()

        $ ".slimScrollBar"
            .hide()

        options = 
            'skipSubprotocolCheck': true
            'maxRetries': 60
            'retryDelay': 2000

        new ab.connect websocketUrl, @__onWsConnected, @__onWsClosed, options

    getMarkup: () ->
        return @$markup

    checkPagination: () ->
        if @$nextLoader.is(':within-viewport') && !@$nextLoader.data('loading')
            url = $ 'a', @$nextLoader
                    .prop 'href'

            @__loadMore url

    __onWsConnected: (session) =>
        console.info 'WebSocket connection connected';
        session.subscribe 'notification', (topic, data) =>
            notification = data.notification

            @$wrapper.prepend this.__buildMarkup notification
            $.notify
                message: notification.message
                url: '/admin/inoplate-notification/notifications'
                target: '_blank'
            ,
                type: 'info'
                placement:
                    from: 'bottom'
                    align: 'left'

            current  = $ '.notif-count'
                            .text()

            current = current || 0

            $ '.notif-count'
                .text parseInt(current) + 1

        session.subscribe 'notification.count', (topic, data) =>
            count = if data.count > 0 then data.count else ''

            $ '.notif-count'
                .text count

    __onWsClosed: () ->
        console.warn 'WebSocket connection closed'

    __loadMore: (url, payloads = {}, callback) ->
        payloads = this.__normalizePayloads payloads
        url = "#{url}#{payloads}"
        items = []
        @$element.trigger "notifications.notification.navbar.loading"
        @$nextLoader.data 'loading', true

        $.get url, (result) =>
            if typeof callback == 'function'
                callback.apply this

            $ 'li.loader', @$wrapper
                .before this.__buildMarkup item for item in result.notifications.data

            if result.notifications.next_page_url
                $ 'a', @$nextLoader
                    .prop 'href', result.notifications.next_page_url

                @$nextLoader
                    .data 'loading', false

                $ @endInfo, @$element
                    .addClass 'hide'

                @checkPagination()
            else
                @$nextLoader
                    .addClass 'hide'

                $ @endInfo, @$element
                    .removeClass 'hide'

            @$element.trigger "notifications.notification.navbar.loaded"
        ,
            'json'

    __normalizePayloads: (payloads) ->
        globalPayloads = @payloads

        if typeof payloads == 'function'
            payloads = payloads.apply this

        if typeof @payloads == 'function'
            globalPayloads = @payloads.apply this

        payloads = $.extend globalPayloads, payloads, true
        payloads = this.__stringifyPayload payloads

        payloads


    __getDefaultMarkup: () ->
         $ "<li class=\"clearfix\">
                <div class=\"message\"></div>
            </li>"

    __buildMarkup: (data) ->
        $markup = @getMarkup().clone()

        if $markup instanceof $
            $ '.message', $markup
                .text data.message

            $markup.data 'id', data.id
            $markup.data 'viewed', data.viewed

        else if typeof $markup == 'function'
            $markup = $markup.apply this, [data]

        $markup    

    __stringifyPayload: (payloads) ->
        string = ""

        for k,v of payloads
            string = "#{string}&#{k}=#{v}"

        string

# PLUGIN DEFINITION
# ============================

$.fn.notificationNavbar = (option) ->
    args = arguments
    defaults = 
        height: "100px"

    this.each () ->
        $this = $ this
        data = $this.data('notification.navbar')
        options = $.extend {}, defaults, $this.data(), typeof option == 'object' && option
        if !data 
            $this.data 'notification.navbar', (data = new NotificationNavbar $this, options)

        if typeof option == 'string'
            argsToSent = []

            for k,v of args
                if k > 0
                    argsToSent.push v

            data[option].apply(data, argsToSent)

    this

navbar = $ '.notifications-menu'
            .notificationNavbar
                wrapper: "ul.notifications-wrapper"
                height: "200px"

navbar.on 'click', () ->
    $this = $ this
    count = $ '.notif-count', this
                .text()

    count = count || 0

    if !$this.hasClass('open')
        navbar.notificationNavbar 'checkPagination'

        if parseInt(count) > 0

            token = $ 'meta[name="csrf-token"]'
                .attr 'content'

            data = 
                _token: token
                _method: 'put'

            $.post('/admin/inoplate-notification/notifications/mark-as-viewed', data);