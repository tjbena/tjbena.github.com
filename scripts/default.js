;(function($){
$(document).ready(function(){

// Code goes here, yo!

var enableFunStuff = function() {
	$('#sidebar').css({
		position: 'fixed',
		left: '50%',
		'margin-left': '-480px'
	});
};

var disableFunStuff = function() {
	$('#sidebar').css({
		position: 'absolute',
		left: 0,
		'margin-left': 0
	});
};

var sizeUp = function() {
  winwidth = $(window).width();
  winheight = $(window).height();
  sidebarheight = 0;
  
  $('#sidebar .block').each(function(){
  	sidebarheight += $(this).outerHeight();
  });
  
  
	if(winwidth >= 960) {
		enableFunStuff();
	} else {
		disableFunStuff();
	}
	
	if(winheight < sidebarheight) {
		disableFunStuff();
	}
}

sizeUp();

$(window).resize(function(){
	sizeUp();
});

});
})(jQuery);