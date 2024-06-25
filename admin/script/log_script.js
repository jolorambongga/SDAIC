function logAction(user_id, category, action, affected_data, callback) {
    var ua = navigator.userAgent;
    var device = '';
    var device_model = '';

    if (/Android/i.test(ua)) {
        device = 'Android';
        var match = ua.match(/Android\s([^\s;]+)/);
        if (match) {
            device_model = match[1];
        }
    } else if (/iPad|iPhone|iPod/.test(ua) && !window.MSStream) {
        device = 'iOS';
        var match = ua.match(/(iPad|iPhone|iPod);[\w\s]+(?:\s([\w\s]+))?/);
        if (match) {
            device_model = match[2] || match[1];
        }
    } else {
        device = 'Desktop';
    }

    var browser_info = bowser.getParser(ua);
    var browser_name = browser_info.getBrowserName();
    var browser_version = browser_info.getBrowserVersion();

    $.ajax({
        url: 'https://ipinfo.io/json',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var userIP = response.ip;
            var organization = response.org;

            var logData = {
                user_id: user_id,
                category: category,
                action: action,
                affected_data: affected_data,
                device: device,
                device_model: device_model || null,
                browser: browser_name + ' ' + browser_version,
                latitude: response.loc ? parseFloat(response.loc.split(',')[0]) : null,
                longitude: response.loc ? parseFloat(response.loc.split(',')[1]) : null,
                location: response.city + ', ' + response.region + ', ' + response.country,
                ip_address: userIP,
                organization: organization || null,
                time_stamp: new Date().toISOString().slice(0, 19).replace('T', ' ')
            };

            $.ajax({
                url: '../admin/handles/logs/create_log.php',
                type: 'POST',
                data: logData,
                success: function(response) {
                    console.log('Log inserted successfully:', response);
                    if (callback && typeof callback === 'function') {
                        callback();
                    }
                },
                error: function(error) {
                    console.error('Error inserting log:', error);
                    if (callback && typeof callback === 'function') {
                        callback();
                    }
                }
            });
        },
        error: function(error) {
            console.error('Error fetching user IP:', error);
            if (callback && typeof callback === 'function') {
                callback(); 
            }
        }
    });
}
