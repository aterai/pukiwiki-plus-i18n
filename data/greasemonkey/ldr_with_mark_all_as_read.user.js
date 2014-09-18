// ==UserScript==
// @name        LDR with Touch All
// @namespace   http://terai.xrea.jp/
// @include     http://reader.livedoor.com/reader/*
// @include     http://fastladder.com/reader/*
// @version     0.1
// ==/UserScript==

(function(){
    var w = (typeof unsafeWindow == 'undefined') ? window : unsafeWindow;
    var description = "\u5168\u90e8\u65e2\u8aad\u306b\u3057\u307e\u3059";
    var label       = "\u5168\u90e8\u65e2\u8aad\u306b\u3059\u308b";
    w.entry_widgets.add('test_touch_all', function(feed) {
        //if(Config.touch_when != "manual") return "";
        return [
            "<span class='button' onclick='touch_all(\"",
            feed.subscribe_id,
            "\")'>",
            label,
            "</span>"
        ].join("");
    }, description);
})();
