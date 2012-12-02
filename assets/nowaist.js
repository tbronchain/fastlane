$(document).ready(function(){
	function toHHMMSS(currentTimestamp) {
	    var date = new Date();
	    timestamp = parseInt(date.getTime()/1000) - currentTimestamp;
	    
		sec_numb    = parseInt(timestamp);
	    var hours   = Math.floor(sec_numb / 3600);
	    var minutes = Math.floor((sec_numb - (hours * 3600)) / 60);
	    var seconds = sec_numb - (hours * 3600) - (minutes * 60);
	
	    if (hours   < 10) {hours   = "0"+hours;}
	    if (minutes < 10) {minutes = "0"+minutes;}
	    if (seconds < 10) {seconds = "0"+seconds;}
	    var time    = hours+':'+minutes+':'+seconds;
	    return time;
	}
	
	function getTimeToGo(timestamp, estimatedTime) {
		var currentTimestamp = new Date();
		var hours = parseInt(currentTimestamp.getHours());
		var minutes = parseInt(currentTimestamp.getMinutes()) + parseInt(estimatedTime);
		var seconds = parseInt(currentTimestamp.getSeconds());
		
		if (minutes >= 60) {
			minutes -= 60;
			hours++;
		}
		
		if (hours   < 10) {hours   = "0"+hours;}
	    if (minutes < 10) {minutes = "0"+minutes;}
	    if (seconds < 10) {seconds = "0"+seconds;}
	    var time    = hours+':'+minutes+':'+seconds;
	    
	    return time;
	}
	
	function fillTemplate(key, animate) {
		var line = list[key];
		line['position'] = currentPos;
		line['estimated_time'] = currentPos * 5;
		line['waited_time'] = toHHMMSS(line['time']);
		line['time_to_go'] = getTimeToGo(line['time'], line['estimated_time']);
		line['time'] = undefined;
		line['estimated_time'] += 'mn';
		for (lineKey in line) {
			template.find('.'+lineKey).html(line[lineKey]);
		}
		contentList.append(template.html());
		if (animate) {
			var last = contentList.find('.ticket:last-child');
			last.hide().show( 'blind', {}, 1000);
		}
	}
	
	function refreshEstimatedTime() {
		var it = 1;
		$('.ticket').each(function(){
			$(this).find('.estimated_time').html((it * 5)+'mn');
			it++;
		});
	}
	
	var template = $('.template');
	var contentList = $('.list_content');
	var i = 0;
	var currentPos = 1;
	var list = '';
	var getListUrl = 'http://192.168.0.101/angelhack/shittyhub/API/api.php?mode=get_list';
	var validateClientUrl = 'http://192.168.0.101/angelhack/shittyhub/API/api.php?mode=validate_client';
	$.ajax({
		url: getListUrl,
		success: function(data){
			list = eval(data);
			for (key in list) {
				fillTemplate(key, false);
				i++;
				currentPos++;
			}
		}
	});
	setInterval(function() {
		$.ajax({
			url: getListUrl,
			success: function(data){
				newList = eval(data);
				lastKey = 0;
				var j = 0;
				for (key in newList) {
					lastKey = key;
					j++;
				}
				if (i != j) {
					list = newList;
					if (i < j) {
						fillTemplate(lastKey, true);
						currentPos++;
						refreshEstimatedTime()
					}
					i = j;
				}
			}
			
		});
	}, 2000);
	setInterval(function() {
		$('.waited_time').each(function(){
			var explode = $(this).html().split(':');
			explode[0] = parseInt(explode[0]);
			explode[1] = parseInt(explode[1]);
			explode[2] = parseInt(explode[2]);
			explode[2] += 1;
			if (explode[2] >= 60) {
				explode[2] -= 60;
				explode[1] += 1;
			}
			if (explode[0] < 10) {explode[0] = "0"+explode[0];}
		    if (explode[1] < 10) {explode[1] = "0"+explode[1];}
		    if (explode[2] < 10) {explode[2] = "0"+explode[2];}
		    var time    = explode[0]+':'+explode[1]+':'+explode[2];
			$(this).html(time);
		});
	}, 1000);
	$('.ticket_content').live('click', function() {
		var validateBtn = $(this).parents('.ticket').find('.pop_validate');
		if (validateBtn.css('display') == 'none') {
			$('.pop_validate').fadeOut()
			validateBtn.fadeIn();
		}
	});
	$('.pop_validate').live('click', function(){
		var ticket = $(this).parents('.ticket');
		var phoneNumber = ticket.find('.phone_number').html();
		ticket.fadeOut('slow');
		ticket.remove();
		var data = {
			phone_number: phoneNumber,
			mode: ""
		}
		$.ajax({
			type: "POST",
			url: validateClientUrl,
			data: {data: JSON.stringify(data)},
			dataType: "json"
		});
		refreshEstimatedTime();
	});
	
});
