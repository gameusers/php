
// --------------------------------------------------
//   Service Woker 強制更新
// --------------------------------------------------

// self.addEventListener('install', function(event) {
//     event.waitUntil(self.skipWaiting());
// });


// --------------------------------------------------
//   通知を受けたときの処理
// --------------------------------------------------

self.addEventListener('push', function(event) {

    //console.log('Received a push message 27', event);

    var eventData = event.data ? event.data.text() : 'no payload';
    var payload = JSON.parse(eventData);


// console.log(event.data);
// console.log(event.data.text());
//var payload = {"title":"aaa","body":"bbbbb"};

// console.log(payload);
// console.log(payload.title);
// console.log(payload.body);

    //console.log(payload.icon);


    var title = payload.title;
    var body = payload.body;
    var icon = payload.icon ? payload.icon : 'https://gameusers.org/assets/img/index/gameusers_thumbnail.png';
    var tag = payload.tag ? payload.tag : 'gameusers';
    var url = payload.url ? payload.url : 'https://gameusers.org/';

    // ミリ秒単位で振動と休止の時間を交互に任意の回数だけ配列に格納
    var vibrate = payload.vibrate ? [200, 100, 200] : [];
//console.log('vibrate', vibrate);
//console.log('icon', payload.icon);

    event.waitUntil(
        self.registration.showNotification(title, {
            body: body,
			icon: icon,
			tag: tag,
            data: {
                url: url
            },
			vibrate: vibrate
        })
    );

});


self.addEventListener('notificationclick', function(event) {
    //console.log('On notification click: ', event.notification.tag);
    event.notification.close();
    //console.log('url: ', event.notification.url);

    var notoficationURL = "/";
    if (event.notification.data.url) {
        notoficationURL = event.notification.data.url;
    }

    event.waitUntil(clients.matchAll({type: 'window'}).then(function(clientList) {
        // for (var i = 0; i < clientList.length; i++) {
        //     var client = clientList[i];
        //     if (client.url === '/' && 'focus' in client) {
        //         return client.focus();
        //     }
        // }
        if (clients.openWindow) {
            return clients.openWindow(notoficationURL);
        }
    }));
});
