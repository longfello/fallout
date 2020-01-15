// Constructor. 
// Create new Dklab_Realplexor object.
function Dklab_Realplexor(fullUrl, namespace, viaDocumentWrite)
{
  "use strict";
	// Current JS library version.
	var VERSION = "1.32";

	// Detect current page hostname.
	var host = document.location.host;
	
	// Assign initial properties.
	if (!this.constructor._registry) {
    this.constructor._registry = {};
  } // all objects registry
	this.version = VERSION;
	this._map = {};
	this._realplexor = null;
	this._namespace = namespace;
	this._login = null;
	this._iframeId = "mpl" + (new Date().getTime());
	this._iframeTag =
		'<iframe' +
		' id="' + this._iframeId + '"' +
		' onload="' + 'Dklab_Realplexor' + '._iframeLoaded(&quot;' + this._iframeId + '&quot;)"' +
		' src="' + fullUrl + '?identifier=IFRAME&amp;HOST=' + host + '&amp;version=' + this.version + '"' +
		' style="position:absolute; visibility:hidden; width:200px; height:200px; left:-1000px; top:-1000px"' +
		'></iframe>';
	this._iframeCreated = false;
	this._needExecute = false;
	this._executeTimer = null;
	
	// Register this object in the registry (for IFRAME onload callback).
	this.constructor._registry[this._iframeId] = this;
	
	// Validate realplexor URL.
	if (!fullUrl.match(/^\w+:\/\/([^/]+)/)) {
		throw 'Dklab_Realplexor constructor argument must be fully-qualified URL, ' + fullUrl + ' given.';
	}
	var mHost = RegExp.$1;
	if (mHost !== host && mHost.lastIndexOf("." + host) !== mHost.length - host.length - 1) {
		throw 'Due to the standard XMLHttpRequest security policy, hostname in URL passed to Dklab_Realplexor (' + mHost + ') must be equals to the current host (' + host + ') or be its direct sub-domain.';
	} 
	
	// Create IFRAME if requested.
	if (viaDocumentWrite) {
		document.write(this._iframeTag);
		this._iframeCreated = true;
	}
	
	// Allow realplexor's IFRAME to access outer window.
	document.domain = host;
}

// Static function. 
// Called when a realplexor iframe is loaded.
Dklab_Realplexor._iframeLoaded = function(id)
{
  "use strict";
	var th = this._registry[id];
	// use setTimeout to let IFRAME JavaScript code some time to execute.
	setTimeout(function() {
		var iframe = document.getElementById(id);
		th._realplexor = iframe.contentWindow.Dklab_Realplexor_Loader;
		if (th.needExecute) {
			th.execute();
		}
	}, 50);
};

// Set active login.
Dklab_Realplexor.prototype.logon = function(login) {
  "use strict";
	this._login = login;
};

// Set the position from which we need to listen a specified ID.
Dklab_Realplexor.prototype.setCursor = function(id, cursor) {
  "use strict";
	if (!this._map[id]) {
    this._map[id] = { cursor: null, callbacks: [] };
  }
	this._map[id].cursor = cursor;
	return this;
};

// Subscribe a new callback to specified ID.
// To apply changes and reconnect to the server, call execute()
// after a sequence of subscribe() calls.
Dklab_Realplexor.prototype.subscribe = function(id, callback) {
  "use strict";
	if (!this._map[id]) {
    this._map[id] = { cursor: null, callbacks: [] };
  }
	var chain = this._map[id].callbacks;
	for (var i = 0; i < chain.length; i++) {
		if (chain[i] === callback) {
      return this;
    }
	}
	chain.push(callback);
	return this;
};

// Unsubscribe a callback from the specified ID.
// You do not need to reconnect to the server (see execute()) 
// to stop calling of this callback.
Dklab_Realplexor.prototype.unsubscribe = function(id, callback) {
  "use strict";
	if (!this._map[id]) {
    return this;
  }
	if (callback == null) {
		this._map[id].callbacks = [];
		return this;
	}
	var chain = this._map[id].callbacks;
	for (var i = 0; i < chain.length; i++) {
		if (chain[i] === callback) {
			chain.splice(i, 1);
			return this;
		}
	}
	return this;
};

// Reconnect to the server and listen for all specified IDs.
// You should call this method after a number of calls to subscribe().
Dklab_Realplexor.prototype.execute = function() {
  "use strict";
	// Control IFRAME creation.
	if (!this._iframeCreated) {
    $('<div class="hidden"></div>').html(this._iframeTag).appendTo('body').append('<div id="hidden_content"></div>');
//		document.body.appendChild(div);
		this._iframeCreated = true;
	}
	
	// Check if the realplexor is ready (if not, schedule later execution).
	if (this._executeTimer) {
		clearTimeout(this._executeTimer);
		this._executeTimer = null;
	}
	var th = this;
	if (!this._realplexor) {
		this._executeTimer = setTimeout(function() { th.execute(); }, 30);
		return;
	}
	
	// Realplexor loader is ready, run it.
	this._realplexor.execute(
		this._map, 
		this.constructor._callAndReturnException, 
		(this._login != null? this._login + "_" : "") + (this._namespace != null? this._namespace : "")
	);
};

// This is a work-around for stupid IE. Unfortunately IE cannot
// catch exceptions which are thrown from the different frame
// (in most cases). Please see
// http://blogs.msdn.com/jaiprakash/archive/2007/01/22/jscript-exceptions-not-handled-thrown-across-frames-if-thrown-from-a-expando-method.aspx

Dklab_Realplexor._callAndReturnException = function(func, args) {
  "use strict";
	try {
		func.apply(null, args);
		return null;
	} catch (e) {
		return "" + e;
	}
};

window.rpl = {
  realplexor: null,
  init_check: null,
  rpl_connected : false,
  timeout : 100,
  uid: 0,
  startup: function(){
    "use strict";
    rpl.wait_uid();
  },
  wait_uid: function(){
    "use strict";
    if (!rpl.uid) {
      rpl.uid = $('body').data('uid');
      $('body').data('uid', '');
      rpl.init();
    } else {
      rpl.timeout = 2*rpl.timeout;
      setTimeout(rpl.wait_uid, rpl.timeout);
    }
  },
  subscribe: function(cmd){
    rpl.realplexor.subscribe(cmd, function (result, id){
      var data = $.parseJSON(result);
//      console.log(data);
      $('body').trigger(data.cmd, data.data);
    });
    // Apply subscriptions. Сallbacks are called asynchronously on data arrival.
    rpl.realplexor.execute();
  },
  init : function() {
    "use strict";
    rpl.realplexor = new Dklab_Realplexor(
      location.protocol + "//rpl."+window.location.host,  // Realplexor's engine URL; must be a sub-domain
      // "http://rpl."+window.location.host+'/commet',  // Realplexor's engine URL; must be a sub-domain
      "fallout_"                                                  // namespace
    );

    rpl.subscribe("cmd_"+rpl.uid);
    $('body').on({
      logoutPlayer: function(){
        setTimeout(function(){
          document.location.href="/logout.php";
        }, 1000);
      },
      popupClose: function(){
        $('#popup-message').dialog( "close" );
      },
      really_started: function(e, data){
        if (!rpl.rpl_connected) {
          rpl.rpl_connected = true;
          $('body').trigger('rpl_connected');
        }
      },
      btn_user_invite : function(e){
          e.preventDefault();
          document.location.href = "/player/invite";
      },
      btn_user_invite_remind : function(e){
          e.preventDefault();
          $.get('/ajax_first_login.php', {'action' : 'invite_remind'}, function() {
              $('#popup-message').dialog( "close" );
          });
      },
      btn_user_invite_delete : function(e){
          e.preventDefault();
          $.get('/ajax_first_login.php', {'action' : 'invite_delete'}, function() {
              $('#popup-message').dialog( "close" );
          });
      },
      popup: function(e, data){
        $('#popup-message').remove();
        var template = '<div id="popup-message" title="'+data.title+'">'+
            '<div class="popup-content">'+data.text+'</div>'+
            '</div>';
        $('body').append(template);

        var buttons = { 'Ok': function() { $( this ).dialog( "close" ); }};
        if (data.buttons) {
          buttons = {};
          for(var i in data.buttons){
            (function(foo){
                buttons[i] = {
                  click: function(){
                      $('body').trigger(foo);
                  },
                  'class' : 'cmd-'+data.buttons[i],
                  text: i
              };
            })(data.buttons[i]);
          }
        }

        $( "#popup-message" ).dialog({
          modal: true,
          buttons: buttons
        });
        if (data.url) {
          $('.popup-content').load(data.url);
        }
      },
      combatManual: function(e, data){
        $.get('/combat/control/manual', function(){
          document.location.href = '/combat';
        });
      },
      combatAutomove: function(e, data){
        $.get('/combat/control/automove');
        $('body').trigger('popupClose');
      }
    });
    $('body').trigger('rpl_subscribe');
  }
};

$(document).ready(function(){
  "use strict";
  rpl.startup();
});
