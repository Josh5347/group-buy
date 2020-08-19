// jquery ui的dialog在IE時 autosize功能會失效。手動將寬度自訂，使IE正常顯示dialog
function widthAdjust(){
	
	//console.log("Browser:"+BrowserDetect.browser);
	//console.log("Verson:"+BrowserDetect.version);

	if (BrowserDetect.browser == 'Explorer'){
		if ($(window).width() >= 992){
			return 992 * 0.8;
		}else{
			return $(window).width();
		}
	}else{
		return 'auto';
	}
};

//取得網址中的檔名
function getFileName(){
	return location.pathname.match(/[-_\w]+[.][\w]+$/i)[0];
}
//取得網址路徑
function getSiteRoot(){//window.location.protocol   http: ; window.location.host  www.子網域.XXX
	var rootPath = window.location.protocol + "//" + window.location.host + "/";
	/* if (window.location.hostname == "localhost"){
		var path = window.location.pathname;
		console.log("path="+path);
		if (path.indexOf("/") == 0){
			path = path.substring(1);
		}
		path = path.split("/", 1);
		if(path != ""){
			rootPath = rootPath + path + "/";
		}
	} */
	return rootPath;
}

function checkPid( pid ) {
 
	if ( pid.length !== 10 ) return '身分證字號長度不正確';
	if ( !(/(^[A-Za-z][12][\d]{8}$|[A-Za-z][A-Da-d][\d]{8}$)/.test(pid))) return '身分證字號含不合法字元，請檢查';   // PREG 驗證
	if ( (/^[A-Za-z][\d]{9}$/.test(pid))) {     // 此為身分證字號
	  pid = pid.toUpperCase();                 // 即使輸入小寫字元，也將它轉成大寫字元
	  var codes = '0123456789ABCDEFGHJKLMNPQRSTUVXYWZIO';        // 注意英文字母順序
	  var pidCodes = {};
	  $(codes.split('')).each( function( index, elem) {
		pidCodes[elem] = index;                                  // 建立字母vs數字對照表
	  });
	  // 依據前9碼權重總合與最後檢核碼比較
	  var sum = 0;
	  for ( var i=8; i>0; i--) {
		sum += parseInt(pidCodes[pid.charAt(i)]) * (9-i);
		//console.log( sum + '- ' + pid.charAt(i) + '= ' + parseInt(pidCodes[pid.charAt(i)]) * (9-i));
	  }
	  var checkDigit = 10 - (sum + parseInt(pidCodes[pid.charAt(0)])%10*9 + parseInt(parseInt(pidCodes[pid.charAt(0)]/10)))%10;
	  return checkDigit === parseInt(pid.slice(-1)) ? '正確' : '身分證字號檢核不正確';
	} else {
		if (/^[A-Za-z][A-Da-d][\d]{8}$/.test(pid)) {      // 此為居留證證號
			return '此為居留證證號';
		// 程式內容待補充
	}
	}
}