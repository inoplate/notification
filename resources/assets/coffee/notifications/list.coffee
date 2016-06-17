class NotificationList
    constructor: (@$element, options) ->
        @$markup = options.markup || @__getDefaultMarkup()
        @payloads = options.payloads || {}
        @endInfo = options.endInfo || '.end-info'
        @wrapper = options.holder || 'ul'
        @holder = options.holder || 'li'
        @nextLoader = options.nextLoader || '.loader'
        @$nextLoader = $ @nextLoader, @$element
        @$wrapper = $ @wrapper, @$element
        @markAsReadUrl = options.markAsReadUrl
        websocketUrl = @$element.data 'websocket'

        $ window
            .scroll () =>
                @checkPagination()

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
        console.info 'connected';
        session.subscribe 'notification', (topic, data) =>
            @$wrapper.prepend this.__buildMarkup data.notification

        session.subscribe 'notification.count', (topic, data) =>
            console.log data

    __onWsClosed: () ->
        console.warn 'WebSocket connection closed'

    __loadMore: (url, payloads = {}, callback) ->
        payloads = this.__normalizePayloads payloads
        url = "#{url}#{payloads}"
        items = []
        @$element.trigger "notifications.notification.list.loading"
        @$nextLoader.data 'loading', true

        $.get url, (result) =>
            if typeof callback == 'function'
                callback.apply this

            @$wrapper.append this.__buildMarkup item for item in result.notifications.data

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

            @$element.trigger "notifications.notification.list.loaded"
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
                <span class=\"pull-left message\"></span>
                <span class=\"pull-right datetime\"></span>
            </li>"

    __buildMarkup: (data) ->
        $markup = @getMarkup().clone()

        if $markup instanceof $
            datetime = moment(data.created_at);
            now = moment(Date.now());

            dayDiff = now.diff datetime, 'days';

            if(dayDiff > 1) 
                datetime = moment data.created_at
                            .format 'LL'
            else
                datetime = moment data.created_at
                            .fromNow()

            $ '.message', $markup
                .text data.message

            $ '.datetime', $markup
                .text datetime

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

$.fn.notificationList = (option) ->
    args = arguments
    defaults = 
        height: "450px"

    this.each () ->
        $this = $ this
        data = $this.data('notification.list')
        options = $.extend {}, defaults, $this.data(), typeof option == 'object' && option
        if !data 
            $this.data 'notification.list', (data = new NotificationList $this, options)

        if typeof option == 'string'
            argsToSent = []

            for k,v of args
                if k > 0
                    argsToSent.push v

            data[option].apply(data, argsToSent)

    this