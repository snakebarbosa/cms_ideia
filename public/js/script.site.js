var CMPSITE = {

	YouTubePlaylist : function(){
		if ($('#praiaplay')[0]) {
			$('#praiaplay').rypp( 'AIzaSyB0zC7AlGSFetqwx0Tmbeecp5Mq_WzEnuM' ,{
		    	update_title_desc: true, // Default false
		      	autoplay: false,
		      	autonext: true,
		      	loop: false,
		      	mute: true,
		      	debug: false
		    });
	    }
	},

	init : function() {
		if($(window).height() < $('.content-home').height()+500){
			$('body').addClass('b-relative');
			$('.content-footer').addClass('lg');
		}

		$('.cmp-overlay-menu').on('click',function(){
			$('.menu-bar').click();
		});

		CMPSITE.YouTubePlaylist();
	}
};

$(function(){
	CMPSITE.init();
});