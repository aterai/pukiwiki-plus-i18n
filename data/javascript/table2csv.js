// ==UserScript==
// @name        <table> to CSV
// @namespace   https://ateraimemo.com/
// @include     http://*
// @description Html table -> CSV(textarea)
// @grant       none
// @version     1.0.6
// ==/UserScript==
//- [JavaScriptでHtmlのtable要素をCSVに変換する](https://ateraimemo.com/JavaScript/table2csv.html)
(function() {
  function table2csv(table) {
    var tr = table.getElementsByTagName('tr'), i, j, k, l, xoff, text, cells, td, array = [], lenr = tr.length, lenc;
    for(i=0; i<lenr; i++) {
      //前行のセルのcolspanで、すでにこの行は初期化されている場合がある
      array[i] = array[i] || [];
      cells = tr.item(i).cells;
      lenc = cells.length;
      for(j=0; j<lenc; j++) {
        td = cells.item(j);
        //タグの削除、タブをスペースに置換、両側trim、ダブルクオートの二重化
        text = td.innerHTML.replace(/<.*?>/mg, '').replace(/\t/g,' ').replace(/(^\s+)|(\s+$)/g, '').replace(/\"/, '""');
        //前の行のrowspanですでにこのセルが使用されている場合はxoffだけ移動
        xoff = 0;
        while(array[i][j+xoff] != null) {
          xoff++;
        }
        array[i][j+xoff] = text;
        for(k=1; k<td.colSpan; k++) {
          array[i][j+xoff+k] = text;
        }
        for(l=1; l<td.rowSpan; l++) {
          array[i+l] = array[i+l] || [];
          for(k=0; k<td.colSpan; k++) {
            array[i+l][j+xoff+k] = text; //同文字列 '〃';
          }
        }
      }
    }
    return convertArray2CSV(array);
  }
  function convertArray2CSV(array) {
      var lenr = array.length,
          lenc = array[0].length,
          csv  = '', csvRow, k, l;
      for(k=0; k<lenr; k++) {
          csvRow = '';
          for(l=0; l<lenc; l++) {
              //内容をダブルクオートで囲んでタブ区切りで追加
              csvRow += '\t"'+array[k][l]+'"';
          }
          if(csvRow != '') {
              csvRow = csvRow.substring(1,csvRow.length);
          }
          csv += csvRow+'\n';
      }
      return csv;
  }
  var listener = function(e) {
    var tx = document.createElement('textarea');
    tx.value = table2csv(this);
    //textareaのサイズは適当(コピペするだけなので)
    tx.style.width  = '80%';
    tx.style.height = '240px';
    tx.originalTable  = this;
    this.tx = tx;
    this.parentNode.replaceChild(this.tx, this);
    tx.addEventListener('dblclick', function(e) {
      this.parentNode.replaceChild(this.originalTable, this);
    }, false);
  },
  table = document.getElementsByTagName('table'), i = 0, len = table.length;
  for(; i<len; i++) {
    //tableがネストしている(子tableが存在する)場合は、クリックしても無視する
    if(table[i].getElementsByTagName('table').length>0) continue;
    //レイアウト目的の<table>は、無視する(例: <table role="presentation">, <table border="0">
    //console.log("== : " + (table[i].getAttribute('border')==0));
    //console.log("===: " + (table[i].getAttribute('border')===0));
    if(table[i].getAttribute('role')==='presentation' || (table[i].getAttribute('border')==='0')) continue;
    table[i].addEventListener('dblclick', listener, false);
  }
}());
