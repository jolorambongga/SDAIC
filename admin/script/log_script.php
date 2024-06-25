<script>
    function logAction(user_id, category, action, details) {
        var ua = navigator.userAgent;

        var device = (function() {
            if (/Android/.test(ua)) {
                return 'android';
            }

            if (/iPad/.test(ua)) {
                return 'ipad';
            }

            if (/iPhone/.test(ua)) {
                return 'iphone';
            }

            if (/Mobile/.test(ua)) {
                return 'mobile';
            }

            if (/Tablet/.test(ua)) {
                return 'tablet';
            }

            return 'desktop';
        })();

        var browser_info = bowser.getParser(navigator.userAgent);
        var browser_name = browser_info.getBrowserName();
        var browser_version = browser_info.getBrowserVersion();

        var device_model = '';

        if (device === 'android') {
            var match = ua.match(/Android\s([^\s;]+)/);
            if (match) {
                device_model = match[1];
            }
        } else if (device === 'ipad' || device === 'iphone') {
            var match = ua.match(/(iPad|iPhone);.*CPU.*OS (\d+)/);
            if (match) {
                device_model = match[1] + ' ' + match[2];
            }
        }

        var time_stamp = new Date().toISOString().slice(0, 19).replace('T', ' ');

        $.ajax({
            type: 'POST',
            url: '../admin/handles/logs/create_log.php',
            data: {
                user_id: user_id,
                category: category,
                action: action,
                details: details,
                device: device,
                device_model: device_model,
                browser: browser_name + ' ' + browser_version,
                time_stamp: time_stamp
            },
            success: function(response) {
                console.log('Log action success:', response);
            },
            error: function(error) {
                console.error('Log action error:', error);
            }
        });
    }
</script>
