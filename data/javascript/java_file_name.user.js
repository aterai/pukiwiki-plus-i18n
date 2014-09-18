// ==UserScript==
// @name        java_file_name
// @namespace   http://terai.xrea.jp/
// @_include     http://stackoverflow.com/*
// @include     http://*
// @description get ".java" file name
// @version     1.0.4
// ==/UserScript==

(function() {
//   function getFileName(code) {
//     var ext = '.java', f = false, name = 'Unknown',
//        array = code.split(/[{\s\r\n]+/),
//        i, len = array.length;
//     for(i=0;i<len;i++) {
//       switch(array[i]) {
//         case 'public':
//           f = true;
//           continue;
//         case 'class':
//         case 'interface':
//         case 'enum':
//           name = array[i+1];
//           if(f) break;
//           f = false;
//           continue;
//         case 'final':
//           continue;
//         default:
//           f = false;
//       }
//     }
//     return name+ext;
//   }
  function getFileName(code) {
    var r = code.match(/public(?:\s|final)+(?:class|interface|enum)\s+([^{\s]+)/m);
    return (r ? r[1] : 'Unknown') + '.java';
  }
  function pre2text(pre) {
    var div = document.createElement('div'); //dummy div
    div.innerHTML = pre.innerHTML.replace(/<br[ \/]*>/ig, '\n').replace(/<.*?>/mg, '');
    return div.childNodes[0].nodeValue.replace(/\xA0/g, ' '); //replace &nbsp;
  }
  var listener = function(e) {
    alert(getFileName(pre2text(this)));
  },
  pre = document.getElementsByTagName('pre'),
  i = 0, len = pre.length;
  for(; i<len; i++) {
    pre[i].addEventListener('dblclick', listener, false);
  }
}());
