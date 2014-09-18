// ==UserScript==
// @name        LDR KeyHackkey Touch All
// @namespace   http://terai.xrea.jp/
// @include     http://reader.livedoor.com/reader/*
// @include     http://fastladder.com/reader/*
// @version     0.2
// ==/UserScript==
(function() {
    var w = (typeof unsafeWindow == 'undefined') ? window : unsafeWindow;
    var _onload = w.onload;
    var onload = function() {with(w) {
        Keybind.add('d', function() {
            var feed = get_active_feed(true);
            if(feed) {
                touch_all(feed.subscribe_id);
            }
        });
        Keybind.add('t', function() {
            var queue = new Queue();
            get_active_feed().items.forEach(function(item) {
                queue.push(function() {
                    //GM_openInTab(item.link);
                    w.open(item.link);
                });
            });
            queue.interval = 200;
            queue.exec();
        });
        Keybind.add('q', function() {
            var logout = '/reader/logout';
            w.open(logout, '_self');
        });
    }}
    w.onload = function() {
        _onload();
        onload();
    }
}) ();
