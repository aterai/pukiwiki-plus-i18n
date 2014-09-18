// ==UserScript==
// @name          java_bug_db_table_border
// @namespace     http://terai.xrea.jp/
// @description   Java Bug Database Detail Table Border Style
// @include       http://bugs.sun.com/*
// ==/UserScript==

(function() {
    //http://lowreal.net/logs/2006/03/16/1
    $X = function (exp, context) {
        if (!context) context = document;
        var resolver = function (prefix) {
            var o = document.createNSResolver(context)(prefix);
            return o ? o : (document.contentType == "text/html") ? "" : "http://www.w3.org/1999/xhtml";
        }
        var exp = document.createExpression(exp, resolver);
        var result = exp.evaluate(context, XPathResult.ANY_TYPE, null);
        switch (result.resultType) {
          case XPathResult.STRING_TYPE : return result.stringValue;
          case XPathResult.NUMBER_TYPE : return result.numberValue;
          case XPathResult.BOOLEAN_TYPE: return result.booleanValue;
          case XPathResult.UNORDERED_NODE_ITERATOR_TYPE: {
              result = exp.evaluate(context, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
              var ret = [];
              for (var i = 0, len = result.snapshotLength; i < len ; i++) {
                  ret.push(result.snapshotItem(i));
              }
              return ret;
          }
        }
        return null;
    }
    var t = $X("/html/body/table[2]/tbody/tr/td[2]/table/tbody/tr/td/table");
    if (t && t.length>0) t[0].border = "1";
})();
