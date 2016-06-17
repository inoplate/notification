options = 
    'skipSubprotocolCheck': true
    'maxRetries': 60
    'retryDelay': 2000

new ab.connect websocketUrl, @__onWsConnected, @__onWsClosed, options