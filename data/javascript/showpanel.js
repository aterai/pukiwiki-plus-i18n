//http://d.hatena.ne.jp/hagino_3000/20080601/1212268980
javascript:(function() {
  var listener = function(e) {
    var info = document.createElement('div'),
        animator,
        style = info.style,
        //body = document.getElementsByTagName('body')[0],
        //body = document.body,
        h    = 0;
    style.position = 'absolute';
    style.top = '0px';
    style.left = "50%";
    //style.left = '0px';
    style.width = '50%';
    style.height = '200px';



      var numY = 0,
          numX = 0;
      var obj = this;
      while( obj = obj.offsetParent ) {
          numY += obj.offsetTop;
          //numX += obj.offsetLeft;
          if ( obj.tagName == 'body' ) {
          //if( obj===body ) {
              break;
          }
      }
      style.top = "" + numY + "px";

      //alert(style.top);

      
    style.background = '#000';
    style.opacity = '0.6';
    style.color = '#FCFCFC';
    style.textAlign = 'left';
    style.zIndex = 999;
    info.addEventListener("click", function(e) {
        this.parentNode.removeChild(info);
    }, false);
    //aaaaaaa

      info.innerHTML = '<div style="margin:1em 0 0 2em">' + document.title + '<br />' + location.href + '</div>';


      body.appendChild(info);
      //this.parentNode.insertBefore(info, this);
      
//     animator = setInterval(function() {
//         style.height = (h + 'em');
//         h += 0.5;
//         if(h>6) {
//             clearInterval(animator);
//             info.innerHTML = '<div style="margin:1em 0 0 2em">' + document.title + '<br />' + location.href + '</div>';
//         }
//     }, 10);
  },
  pre = document.getElementsByTagName("pre"),
  i = 0, len = pre.length;
  for(; i<len; i++) {
    pre[i].addEventListener("dblclick", listener, false);
  }
}());
