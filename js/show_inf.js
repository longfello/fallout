function getScrollXY() {
	var ret = [0, 0],
		body = document.body,
		win = window,
		docEl = document.documentElement;
	if (typeof(win.pageYOffset) == 'number') {
		ret = [win.pageXOffset, win.pageYOffset];
	} else if (body && (body.scrollLeft || body.scrollTop)) {
		ret = [body.scrollLeft, body.scrollTop];
	} else if (docEl && (docEl.scrollLeft || docEl.scrollTop)) {
		ret = [docEl.scrollLeft, docEl.scrollTop];
	}
	return ret;
}
function getMouseXY (e) {
	var ret = [0,0];
	e = e || window.event;
	if (e) {
		if (e.pageX || e.pageY) {
			ret=[e.pageX, e.pageY];
		} else if (e.clientX || e.clientY) {
			var s = getScrollXY();
			ret = [e.clientX + s[0], e.clientY + s[1]];
		}
	}
	return ret;
}
function MoveHint(event) {
	if ($('#hint1').is(':visible')) {
		SetPosition(event);
	}
	return(true);
}
function SetPosition(event) {
	var pos = getMouseXY(event);
    
  var height_hint_table = $('#hint1').height();
  var width_hint_table = $('#hint1').width();

	$('#hint1').css('left', pos[0] - $('#hint1').width() + 20 + width_hint_table + 'px');
	$('#hint1').css('top', pos[1] + 'px');
    
    var full_window_height = document_working_heihgt();
                                         
    if (event.screenY - 100 + height_hint_table > full_window_height) {
        if (event.screenY - 100 - height_hint_table < 0) {
					$('#hint1').css('top', pos[1] - (height_hint_table / 2) + 'px');
        }
        else {
					$('#hint1').css('top', pos[1] - height_hint_table - 20 + 'px');
        }
    }

	  if (pos[0] + width_hint_table + 20  > $(window).width()) {
			$('#hint1').css('left', pos[0] - width_hint_table - 20 + 'px');
		}
}
function hint(text, event) {
	var s;
	s='<table cellpadding=0 cellspacing=0 border=0>';
	s+='<tr><td width=5><img src=/images/pod_l.gif></td><td background=/images/pod_bg.gif>'+text+'</td><td width=5><img src=/images/pod_r.gif></tr>';
	s+='</table>';
	$('#hint1').html(s);
	SetPosition(event);
	$('#hint1').css('visibility', 'visible');
}
function hint_no_bg(text, event) {
	var s;
	s=text;
	$('#hint1').html(s);
	SetPosition(event);
	$('#hint1').css({
		visibility:'visible',
		background:'none',
		border:'none'
	});
}
function formmsg(text, url, event) {
	var s;

	var this_js_script = $("script[src*='show_inf.js']"); // or better regexp to get the file name..

	var t_yes = lang_i18n.yes;
	var t_no = lang_i18n.no;

	s='<table cellpadding=0 cellspacing=0 border=0>';
	s+='<tr><td width=5><img src=images/pod_l.gif></td><td background=images/pod_bg.gif style="text-align:center;">';
	s+=text;
	s+='<br><br>';
	if (url.search('formmsg') >= 0) {
		//если передан id формы
		s+='<form method=post action=""><input id="submit_yes" type="submit" value="'+t_yes+'" onclick="document.forms[\''+url+'\'].submit(); return false;">';
	} else {
		//если передан url
		s+='<form method=post action="'+url+'"><input id="submit_yes" type="submit" value="'+t_yes+'">';
	}
	s+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="formmsg_c();" value="'+t_no+'"></form>';
	s+='</td><td width=5><img src=images/pod_r.gif></tr>';
	s+='</table>';
	$('#formmsg1').html(s);
	var pos = getMouseXY(event);
	$('#formmsg1').css({
		left: pos[0] - $('#formmsg1').width() + 150 + 'px',
		top: pos[1] + 20 + 'px',
		visibility: 'visible'
	});
	document.getElementById('submit_yes').focus();
	return false;
}
function formmsg_c() {
	$('#formmsg1').css({visibility:'hidden'});
}
function c() {
  $('#hint1').css('visibility', 'hidden');
}
document.onmousemove=MoveHint;

// Высота рабочей области браузера
function document_working_heihgt() { 
    //return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight : document.body.clientHeight;
    return $(window).height();
}

// Высота всей области браузера
function document_full_height() {
	return Math.max(
    	document.documentElement["clientHeight"],
    	document.body["scrollHeight"], document.documentElement["scrollHeight"],
    	document.body["offsetHeight"], document.documentElement["offsetHeight"]
    );
}

$(document).ready(function(){
  $('.hintnobg').hover(function(event){
    var info = $('#hint-'+$(this).data('hint'));
    var s = $('.hintnobg-template .hint-blok-wrapper').clone();
    $('img', s).attr('src', $(info).data('src'));
    $('img', s).attr('alt', $(info).data('title'));
    $('.title', s).html($(info).data('title'));
    $('.text-content', s).html($(info).html());
    $('#hint1').empty().append(s);
    SetPosition(event);
		$('#hint1').css({
			visibility: 'visible',
			background: 'none',
			border: 'none'
		}).show();
  }, function(){
    $(hint1).hide();
  });
});
