// ==UserScript==
// @name        <pre> to <textarea>
// @namespace   http://terai.xrea.jp/
// @include     http://*
// @exclude     http://*.google.*
// @description pre <-> textarea
// @version     1.0.5
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
          this.removeEventListener("dragstart", dndlistener, false);
          this.removeEventListener('dragend',   dndlistener, false);
          this.parentNode.removeChild(this);
      }
    };
    a.addEventListener("dragstart", dndlistener, false);
    a.addEventListener('dragend',   dndlistener, false);
    a.innerHTML = 'Download';
    a.setAttribute('download', fileName);

//     a.href = "javascript:void(0)";
//     a.setAttribute("onclick", "window.saveAs(blob, 'tempfile.java');");
//     a.setAttribute("style", "cursor: pointer");

    if(window.createObjectURL) {
      a.href = window.createObjectURL(blob);
    }else if(window.createBlobURL) {
      a.href = window.createBlobURL(blob);
    }else if(window.URL && window.URL.createObjectURL) {
      a.href = window.URL.createObjectURL(blob);
    }else if(window.webkitURL && window.webkitURL.createObjectURL) {
      a.href = window.webkitURL.createObjectURL(blob);
    }
    return a;
  }
  var listener = function(e) {
    var tx = document.createElement("textarea"),
        div = document.createElement("div"),
        fileName = '',
    closeListener = function(e) {
      switch(e.type) {
      case 'copy':
      case 'cut':
        var dataTransfer = e.clipboardData;
        dataTransfer.items.add(this.innerText, 'text/plain');
        this.parentNode.replaceChild(this.originalPre, this);
        e.preventDefault();
        break;
      case 'keydown':
        if(e.keyCode=="27") { // ESC
          this.parentNode.replaceChild(this.originalPre, this);
        }
        break;
      case 'dblclick':
          this.parentNode.replaceChild(this.originalPre, this);
      }
      //???: removeAllEventListeners
      this.removeEventListener('copy',     closeListener, false);
      this.removeEventListener('cut',      closeListener, false);
      this.removeEventListener('keydown',  closeListener, false);
      this.removeEventListener('dblclick', closeListener, false);
    };
    tx.addEventListener('copy',     closeListener, false);
    tx.addEventListener('cut',      closeListener, false);
    tx.addEventListener("keydown",  closeListener, false);
    tx.addEventListener("dblclick", closeListener, false);

    tx.style.width  = parseInt(getComputedStyle(this,"").width).toString()+"px";
    tx.style.height = "240px";
    tx.originalPre  = this;
    div.innerHTML = this.innerHTML.replace(/<br[ \/]*>/ig, "\n").replace(/<.*?>/mg, "");
    tx.value = div.childNodes[0].nodeValue.replace(/\xA0/g, ' ');
    this.tx = tx;
    this.parentNode.replaceChild(this.tx, this);
    this.tx.focus();
    this.tx.select();
    tx.value.match(/public\s+class\s+([^{\s]+)/m);
    //alert( RegExp.$1+'.java' );
    tx.parentNode.appendChild(makeDragDownloadLink(tx.value, 'text/plain', RegExp.$1+'.java'));
  },
  pre = document.getElementsByTagName("pre"),
  i = 0, len = pre.length;
  for(; i<len; i++) {
    pre[i].addEventListener("dblclick", listener, false);
  }
}());

// (function() {
//   var pre = document.getElementsByTagName("pre"),
//       i = 0, len = pre.length,
//       listener = function(e) {
//     var tx = document.createElement("textarea"),
//         div = document.createElement("div");
//     tx.style.width  = parseInt(getComputedStyle(this,"").width).toString()+"px";
//     tx.style.height = "240px";
//     tx.originalPre  = this;
//     tx.addEventListener("keydown", function(e) {
//       if(e.keyCode=="27") { // ESC
//         this.parentNode.replaceChild(this.originalPre, this);
//       }
//     }, false);
//     tx.addEventListener("dblclick", function(e) {
//       this.parentNode.replaceChild(this.originalPre, this);
//     }, false);
//     div.innerHTML = this.innerHTML.replace(/<br[ \/]*>/ig, "\n").replace(/<.*?>/mg, "");
//     tx.value = div.childNodes[0].nodeValue.replace(/\xA0/g, ' ');
//     this.tx = tx;
//     this.parentNode.replaceChild(this.tx, this);
//     this.tx.focus();
//     this.tx.select();
//   };
//   for(; i<len; i++) {
//     pre[i].addEventListener("dblclick", listener, false);
//   }
// }());
//
// javascript:(function(){var d=document.getElementsByTagName("pre"),b=0,a=d.length,c=function(g){var f=document.createElement("textarea"),h=document.createElement("div");f.style.width=parseInt(getComputedStyle(this,"").width).toString()+"px";f.style.height="240px";f.originalPre=this;f.addEventListener("keydown",function(i){if(i.keyCode=="27"){this.parentNode.replaceChild(this.originalPre,this)}},false);f.addEventListener("dblclick",function(i){this.parentNode.replaceChild(this.originalPre,this)},false);h.innerHTML=this.innerHTML.replace(/<br[ \/]*>/ig,"\n").replace(/<.*?>/mg,"");f.value=h.childNodes[0].nodeValue.replace(/\xA0/g," ");this.tx=f;this.parentNode.replaceChild(this.tx,this);this.tx.focus();this.tx.select()};for(;b<a;b++){d[b].addEventListener("dblclick",c,false)}}());

// (function() {
//     var listener =  function(e) {
//         var tx = document.createElement("textarea");
//         tx.style.width  = "100%"; tx.style.height = "100%";
//         if (typeof this.innerText != "undefined") { // Opera
//             tx.value = this.innerText.replace(/\xA0/g, ' ');
//         } else { // FireFox
//             var div = document.createElement("div");
//             div.innerHTML = this.innerHTML.replace(/<br[ \/]*>/ig, "\n").replace(/<.*?>/mg, "");
//             tx.value = div.childNodes[0].nodeValue;
//         }
//         var w = 640, h = 480;
//         var l = Number((window.innerWidth -w)/2);
//         var t = Number((window.innerHeight-h)/2);
//         var s = ["scrollbars=yes,width=",w,",height=",h,",left=",l,",top=",t].join("");
//         window.open("", "", s).document.body.appendChild(tx);
//         tx.select();
//     };
//     var pre = document.getElementsByTagName("pre");
//     for (var i = 0, len = pre.length; i < len; i++) {
//         pre[i].addEventListener("dblclick", listener, false);
//     }
// }());


// (function() {
//     var pre = document.getElementsByTagName("pre");
//     for(var i=0,len=pre.length; i<len; i++) {
//         pre[i].addEventListener("dblclick", function(e) {
//             if(!this.tx) {
//                 var tx = document.createElement("textarea");
//                 tx.style.width  = getWidth(this);
//                 tx.style.height = getHeight(this);
//                 tx.originalPre  = this;
//                 tx.addEventListener("keydown", function(e) {
//                     if(e.keyCode=="27") { // ESC
//                         this.parentNode.replaceChild(this.originalPre, this);
//                     }
//                 }, false);
//                 tx.addEventListener("dblclick", function(e) {
//                     this.parentNode.replaceChild(this.originalPre, this);
//                 }, false);
//                 if(typeof this.innerText != "undefined") { // Opera
//                     tx.value = this.innerText.replace(/\xA0/g, ' ');
//                 }else{ // FireFox
//                     // for html entities
//                     var div = document.createElement("div");
//                     //div.innerHTML = this.innerHTML.replace(/<.*?>/mg, "");
//                     div.innerHTML = this.innerHTML.replace(/<br[ \/]*>/ig, "\n").replace(/<.*?>/mg, "");
//                     tx.value = div.childNodes[0].nodeValue;
//                     //tx.value = this.textContent;
//                 }
//                 this.tx = tx;
//             }
//             this.parentNode.replaceChild(this.tx,this);
//             this.tx.focus();
//             this.tx.select();
//         },false);
//     }
//     function getWidth(aNode) {
//         return (Number(getComputedStyle(aNode, "").width) +
//                 Number(getComputedStyle(aNode, "").paddingLeft) +
//                 Number(getComputedStyle(aNode, "").paddingRight)).toString() + "px";
//     }
//     function getHeight(aNode) {
//         return "240px";
//     }
// }());
// (function() {
//     var listener = function(e) {
//         var tx = document.createElement("textarea");
//         tx.style.width  = getWidth(this);
//         tx.style.height = getHeight(this);
//         tx.originalPre  = this;
//         tx.addEventListener("keydown", function(e) {
//             if(e.keyCode=="27") { // ESC
//                 this.parentNode.replaceChild(this.originalPre, this);
//             }
//         }, false);
//         tx.addEventListener("dblclick", function(e) {
//             this.parentNode.replaceChild(this.originalPre, this);
//         }, false);
//         // for html entities
//         var div = document.createElement("div");
//         div.innerHTML = this.innerHTML.replace(/<br[ \/]*>/ig, "\n").replace(/<.*?>/mg, "");
//         tx.value = div.childNodes[0].nodeValue.replace(/\xA0/g, ' ');
//         this.tx = tx;
//         this.parentNode.replaceChild(this.tx, this);
//         this.tx.focus();
//         this.tx.select();
//     };
//     var pre = document.getElementsByTagName("pre");
//     for(var i=0,len=pre.length; i<len; i++) {
//         pre[i].addEventListener("dblclick", listener,false);
//     }
//     function getWidth(aNode) {
//         return (Number(getComputedStyle(aNode, "").width) +
//                 Number(getComputedStyle(aNode, "").paddingLeft) +
//                 Number(getComputedStyle(aNode, "").paddingRight)).toString() + "px";
//     }
//     function getHeight(aNode) {
//         return "240px";
//     }
// }());
