    var lefttime = endtime - now; //ページを開いた時の残り時間

	function timeConversion() {
		var days = Math.floor(lefttime / 60 / 60 / 24);
        var hours = Math.floor(lefttime / 60 / 60) % 24;
        var min = Math.floor(lefttime / 60) % 60;
        var sec = Math.floor(lefttime % 60);
        var count = [days, hours, min, sec];

        return count;
    }

	function countdown() {
		// 残り時間が0になったら終了メッセージを表示
		if (!(lefttime > 0)) {
			lefttime = 'このオークションは終了しました。';
			document.getElementById('timer').textContent = lefttime;
		} else { // それ以外の場合はカウントダウンを表示

			// 残り時間を1秒減らす処理
			lefttime--;
			// 残り時間を表示
			var counter = timeConversion();
			var time = (counter[0] + '日' + counter[1] + '時間' + counter[2] + '分' + counter[3] + '秒');
			document.getElementById('timer').textContent = time;
		}
	}

	// １秒ごとに実行
	setInterval('countdown()', 1000);
