// ==UserScript==
// @name        <pre> to <textarea>
// @namespace   http://terai.xrea.jp/
// @include     http://*
// @exclude     http://*.google.*
// @description pre <-> textarea
// @version     1.0.6
// ==/UserScript==
// Double Click <PRE>, open <TEXTAREA>
// Test: Clipboard API and events

// Fork of <pre> to <textarea> http://userscripts.org/scripts/show/11438
// original author is Sybian http://d.hatena.ne.jp/Sybian/

// _@name        <pre> to <textarea>
// _@namespace   http://d.hatena.ne.jp/Sybian/
// _@include     http://*
// _@description pre <-> textarea
// _@version     1.0.2
// Ctrl+Click <PRE>, become <TEXTAREA>
// ESC or Ctrl+[ or Ctrl+Shift+Click in <TEXTAREA>, restore <PRE>

(function() {
  function makeDragDownloadLink(text, mimeType, fileName) {
    var blob = new Blob([text], {type: mimeType+';charset=UTF-8'}),
        a = document.createElement('a'),
        dndlistener = function(e) {
      switch(e.type) {
      case 'dragstart':
          e.dataTransfer.setData("DownloadURL", [mimeType,fileName,this.href].join(':'));
          break;
      case 'dragend':
          this.parentNode.removeChild(this);
      }
    };
    a.addEventListener("dragstart", dndlistener, false);
    a.addEventListener('dragend',   dndlistener, false);
    a.innerHTML = 'Download: ' + fileName;
    a.setAttribute('download', fileName);

//     a.href = "javascript:void(0)";
//     a.setAttribute("onclick", "window.saveAs(blob, 'tempfile.java');");

    window.URL = window.URL || window.webkitURL;
    a.href = window.URL.createObjectURL(blob);

//     if(window.createObjectURL) {
//       a.href = window.createObjectURL(blob);
//     }else if(window.createBlobURL) {
//       a.href = window.createBlobURL(blob);
//     }else if(window.URL && window.URL.createObjectURL) {
//       a.href = window.URL.createObjectURL(blob);
//     }else if(window.webkitURL && window.webkitURL.createObjectURL) {
//       a.href = window.webkitURL.createObjectURL(blob);
//     }
    return a;
  }
  function getFileName(code) {
    var r = code.match(/public(?:\s|final)+(?:class|interface|enum)\s+([^{\s]+)/m);
    return (r ? r[1] : 'Unknown') + '.java';
  }
  function pre2text(pre) {
    var div = document.createElement('div'); //dummy div
    div.innerHTML = pre.innerHTML.replace(/<br[ \/]*>/ig, '\n').replace(/<.*?>/mg, '');
    return div.childNodes[0].nodeValue.replace(/\xA0/g, ' '); //replace &nbsp;
  }
  function cleanup(tx) {
    var a = tx.downloadLink;
    tx.parentNode.replaceChild(tx.originalPre, tx);
    if(a!=null && a.parentNode) {
      a.parentNode.removeChild(a);
    }
  }
  var listener = function(e) {
    var tx = document.createElement("textarea"),
      rect = this.getBoundingClientRect(),
      name, ret,
    closeListener = function(e) {
      switch(e.type) {
      case 'copy':
      case 'cut':
        var dataTransfer = e.clipboardData;
        dataTransfer.items.add(this.innerText, 'text/plain');
        cleanup(this);
        e.preventDefault();
        break;
      case 'keydown':
        if(e.keyCode=="27") { // ESC
          cleanup(this);
        }
        break;
      case 'dblclick':
        cleanup(this);
      }
    };
    tx.addEventListener('copy',     closeListener, false);
    tx.addEventListener('cut',      closeListener, false);
    tx.addEventListener("keydown",  closeListener, false);
    tx.addEventListener("dblclick", closeListener, false);

    //parseInt(getComputedStyle(this,"").width).toString()+"px";
    tx.style.width  = rect.width+"px";
    tx.style.height = (rect.height>240 ? 240 : rect.height) + "px";
    tx.originalPre  = this;
    tx.value = pre2text(this);
    this.tx = tx;
    this.parentNode.replaceChild(this.tx, this);
    this.tx.focus();
    this.tx.select();

    //chrome ext???
    //alert(document.execCommand("copy", false, null));

    name = getFileName(tx.value),
    name = ((ret = prompt("FileName", name))!=null) ? ret : name;

    tx.downloadLink = makeDragDownloadLink(tx.value, 'text/plain', name);
    tx.parentNode.insertBefore(tx.downloadLink, tx.nextSibling);
  },
  pre = document.getElementsByTagName("pre"),
  i = 0, len = pre.length;
  for(; i<len; i++) {
    pre[i].addEventListener("dblclick", listener, false);
  }
}());
