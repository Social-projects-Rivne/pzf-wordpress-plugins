// On window load. This waits until images have loaded which is essential
/*global jQuery:false, my_ajax:false, on_resize:false */
/*jshint unused:false */
jQuery(window).load(function() {
	"use strict";

	jQuery('.wpb_thumbnails-fluid').isotope();
	jQuery('.animals-places-list').isotope();

	jQuery('.post_slider_main .post_slider_prev').click(function() {
		eval('revapi'+rev_slider.apiid).revprev();
	});
	
	jQuery('.post_slider_main .post_slider_next').click(function() {
		eval('revapi'+rev_slider.apiid).revnext();
	});

	jQuery('.open-blog-isocial .fb_iframe_widget iframe').hide();


	var loader = new SVGLoader( document.getElementById( 'loader' ), { speedIn : 400, easingIn : mina.easeinout } );
	setTimeout( function() {
		jQuery('.overlay-hide').hide();
		loader.hide();
		jQuery('.pageload-overlay').hide();
	}, 1000 );

	// jQuery('div[id=^="tribe-events-tooltip]').each(function(){
	// 	jQuery(this).css('color', 'blue');
	// });

});

/*
Plugin: jQuery Parallax
Version 1.1.3
Author: Ian Lunn
Twitter: @IanLunn
Author URL: http://www.ianlunn.co.uk/
Plugin URL: http://www.ianlunn.co.uk/plugins/jquery-parallax/

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html
*/

jQuery( document ).ajaxStop(function() {
	jQuery('#post_carousel_content ul').fadeTo(300,1);
	jQuery('.slider_loading').hide();

	jQuery('.tt-grid.tt-effect-3dflip').fadeTo(300,1);
	jQuery('.animal_slider_loading').hide();

	jQuery('#donations_list_content').fadeTo(300,1);
	jQuery('.donations_loading').css('opacity', '0');

	jQuery('.wpcf7-form').each(function(){
		if ( jQuery(this).hasClass('sent') ) {
			var form_class = jQuery(this).parent().parent().attr('class');
			form_class = form_class.split('-')['3'].split(' ')['0']
			
			jQuery('.contact-form-id').each(function(){
				if ( jQuery(this).val() == form_class ) {
					jQuery(this).parent().find('.contacts-member-send').addClass('sent');
					jQuery(this).parent().find('.contacts-member-send').html('Sent!');
				};
			});
			
		};
	});

	jQuery('.twitter_container').jcarousel();

	jQuery('.twitter-prev').click(function(e) {
		e.preventDefault();
		jQuery('.twitter_container').jcarousel('scroll', '-=1');
	});

	jQuery('.twitter-next').click(function(e) {
		e.preventDefault();
		jQuery('.twitter_container').jcarousel('scroll', '+=1');
	});

});

jQuery(document).ready(function($) {
	"use strict";

	var loader = new SVGLoader( document.getElementById( 'loader' ), { speedIn : 400, easingIn : mina.easeinout } );
	loader.show();

	// Perform AJAX login on form submit
	jQuery('form#login').on('submit', function(e) {
		jQuery('form#login p.status').show().text(ajax_login_object.loadingmessage);
		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_login_object.ajaxurl,
			data: { 
				'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
				'username': jQuery('form#login #username').val(), 
				'password': jQuery('form#login #password').val(), 
				'security': jQuery('form#login #security').val()
			},
			success: function(data) {
				jQuery('form#login p.status').text(data.message);
				if (data.loggedin == true) {
					document.location.href = ajax_login_object.redirecturl;
				}
			}
		});
		e.preventDefault();
	});

	var $isotope_container = jQuery(".blog .wpb_thumbnails");

	$isotope_container.isotope({ straightAcross : true });

	// update columnWidth on window resize
	jQuery(window).bind("debouncedresize", function() {
		$isotope_container.isotope({

			// update columnWidth to a percentage of container width
			masonry: { columnWidth: $isotope_container.width() / 2 }
		});

		if ( jQuery(window).width() <= 767 ) {
			jQuery(".video-module-title").each(function(i, val) { console.log(jQuery(this).val());
				if (jQuery(this).val() == '&nbsp;') {
					jQuery(this).hide();
				}
			});
		}
		
	});

	jQuery(".scroll-to-top").click(function() {
		jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	});

	jQuery(".donations-thank-you-right a").click(function(e) {
		e.preventDefault();
		jQuery('html,body').animate({scrollTop: jQuery("#donation_list").offset().top-50});
	});

	jQuery('.donations_loading').css('opacity', '1');

	// jQuery('.open-animals-info-container').isotope();

	if ( jQuery('.image_wrapper').hasClass('carousel') && jQuery(".image_wrapper.carousel li").length >= 2 ) {

		[].slice.call( document.querySelectorAll( ".dotstyle > ul" ) ).forEach( function( nav ) {
			new DotNav( nav, {
				callback : function( idx ) {
				}
			});
		});
		jQuery(".imageSliderExt ul li").eq(0).addClass("current");

		var carousel_10680 = jQuery(".image_wrapper.carousel").on("jcarousel:animate", function(event, carousel) {
				jQuery(".donation-open-carousel li").addClass("active");
			}).on("jcarousel:animateend", function(event, carousel) {
				jQuery(".donation-open-carousel li").removeClass("active");
			}).jcarousel({
			wrap: "circular",
			animation: {
				easing: "linear",
		        duration:1000
		    }
		}).jcarouselAutoscroll({
			autostart: true,
		 target: "+=1",
		 interval: 4000
		});

		jQuery(".image_wrapper.carousel, .imageSliderExt").hover(function() {
			jQuery(".image_wrapper.carousel").jcarouselAutoscroll("stop");
		}, function() {
			jQuery(".image_wrapper.carousel").jcarouselAutoscroll("start");
		});

		jQuery(".imageSliderExt ul").on("jcarouselpagination:active", "li", function() {
			jQuery(this).addClass("current");
		}).on("jcarouselpagination:inactive", "li", function() {
			jQuery(this).removeClass("current");
		}).jcarouselPagination({
			carousel: carousel_10680,
			perPage: 1,
			"item": function(page, carouselItems) {
				return '<li><a href="#' + page + '"></a></li>';
			}
		});

		jQuery(".imageSliderExt ul").append("<li><!-- dummy dot --></li>");

		jQuery(window).bind("debouncedresize", function() {
			jQuery(".imageSliderExt ul").append("<li><!-- dummy dot --></li>");
			jQuery(".dotstyle li").removeClass("current");
			jQuery(".dotstyle li:first-child").addClass("current");
		});

		jQuery(".donation-open-right, .animals-open-right, .events-open-right").click(function() {
			jQuery(".image_wrapper.carousel").jcarousel("scroll", "+=1");
		});

		jQuery(".donation-open-left, .animals-open-left, .events-open-left").click(function() {
			jQuery(".image_wrapper.carousel").jcarousel("scroll", "-=1");
		});
	};

	jQuery('.package_button').click(function(e) {
		e.preventDefault();
		jQuery(this).parent().parent().find('input:radio').prop('checked', true);
		jQuery('#job_package_selection').submit();
	});

	// header_size();

	jQuery('.header_search .sb-icon-search').click(function() {
		if ( !jQuery('.header_search').hasClass('active') ) {
			jQuery('.header_search').css('width', '0%');
			jQuery('.header_search').animate({
				width: "18.5%",
			}, 300, function() {
				jQuery('.header_search').addClass('active');
				jQuery('.header_search .sb-icon-search').addClass('active');
			});
		};
	});

	var times_changed = 0;
	jQuery('.header_search .sb-icon-search').click(function() {
		jQuery('.header_search .footer_search_input').change(function() {
			times_changed = times_changed + 1;
		});

		if ( times_changed != 0 ) {
			jQuery(this).parent().submit();
			jQuery.cookie('vh_search_open', '1', { path: '/' });
		} else {
			if ( jQuery('.header_search').hasClass('active') ) {
				jQuery('.header_search').animate({
					width: "0%",
				}, 300, function() {
					jQuery('.header_search').removeClass('active');
				});
			};
			jQuery.cookie('vh_search_open', '0', { path: '/' });
		}
	});

	if ( jQuery.cookie('vh_search_open') == null ) {
		jQuery.cookie('vh_search_open', '0', { path: '/' });
	};

	if ( jQuery.cookie('vh_search_open') == '1' ) {
		jQuery('.header_search').addClass('active');
		jQuery('.header_search').css('width', '18.5%');
	};

	jQuery(".header-icon").mouseenter(function() {
		jQuery(this).css({'width': 'auto', 'max-width': 'none'});
		// jQuery(this).find('a').css({'padding-right': '22px', 'padding-left': '6px'});
		jQuery(this).addClass('hovered');
		// jQuery(this).find('span').show();
		// jQuery(this).find('a').animate({
		// 		'border-width': 3,
		// 	}, 250, function() {

		// 	});
		var outer_width = jQuery(this).outerWidth();
		jQuery(this).css({'max-width': '70px'});
		jQuery(this).animate({
				'max-width': outer_width+5,
			}, 250, function() {

			});
	});

	// jQuery(".header-icon").mouseleave(function() {
	// 	jQuery(this).find('a').css({'padding-right': '0', 'padding-left': '0'});
	// 	var outer_width = jQuery(this).outerWidth();
	// 	jQuery(this).animate({
	// 			'max-width': '70px',
	// 		}, 100, function() {
	// 			jQuery(this).css({'width': '70px', 'line-height': '70px'});
	// 			jQuery(this).removeClass('hovered');
	// 			jQuery(this).find('span').hide();
	// 			// jQuery(this).find('a').css('border-width', '0px');
	// 		});
	// });



	jQuery('.post_slider_main .post_slider_content').dotdotdot({
		height: 75
	});

	function vh_ajax_get_posts(type,categories,limit,ajaxurl) {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "load-filter", post_type: type, post_categories: categories, post_limit: limit },
			success: function(response) {
				jQuery("#post_carousel_content").html(response);

				var carousel_speed = parseInt(jQuery('#post_slider_speed').val());

				// [].slice.call( document.querySelectorAll( ".dotstyle.number_"+jQuery('#post_slider_random').val()+" > ul" ) ).forEach( function( nav ) {
				// 	new DotNav( nav, {
				// 		callback : function( idx ) {
				// 		}
				// 	});
				// });
				// jQuery(".imageSliderExt.number_"+jQuery('#post_slider_random').val()+" ul li").eq(0).addClass("current");


				var carousel_posts = jQuery("#post_carousel_content").on("jcarousel:animate", function(event, carousel) {
						jQuery(".post_slider_image").addClass("active");
					}).on("jcarousel:animateend", function(event, carousel) {
						jQuery(".post_slider_image").removeClass("active");
					}).jcarousel({
					wrap: "circular",
					animation: {
						easing: "swing",
						duration: carousel_speed
					}
				}).jcarouselAutoscroll({
					autostart: false
				});

				if ( jQuery('#post_slider_auto').val() != 'false' ) {

					jQuery("#post_carousel_content, .imageSliderExt.number_"+jQuery('#post_slider_random').val()).hover(function() {
						jQuery("#post_carousel_content").jcarouselAutoscroll("stop");
					}, function() {
						jQuery("#post_carousel_content").jcarouselAutoscroll("start");
					});
				}

				if ( jQuery(".imageSliderExt.number_"+jQuery('#post_slider_random').val()+" ul li").length ) {
					jQuery(".imageSliderExt.number_"+jQuery('#post_slider_random').val()+" ul").jcarouselPagination('destroy');
				};

				jQuery(".imageSliderExt.number_"+jQuery('#post_slider_random').val()+" ul").on("jcarouselpagination:active", "li", function() {
					jQuery(this).addClass("current");
				}).on("jcarouselpagination:inactive", "li", function() {
					jQuery(this).removeClass("current");
				}).jcarouselPagination({
					perPage: 1,
					"item": function(page, carouselItems) {
						return '<li><a href="#' + page + '"></a></li>';
					}
				});

				jQuery(".imageSliderExt.number_"+jQuery('#post_slider_random').val()+" ul").append("<li><!-- dummy dot --></li>");

				jQuery(window).bind("debouncedresize", function() {
					jQuery(".imageSliderExt.number_"+jQuery('#post_slider_random').val()+" ul").append("<li><!-- dummy dot --></li>");
					jQuery(".dotstyle.number_"+jQuery('#post_slider_random').val()+" li").removeClass("current");
					jQuery(".dotstyle.number_"+jQuery('#post_slider_random').val()+" li:first-child").addClass("current");
				});

				jQuery(".post_slider_main_prev").click(function(e) {
					e.preventDefault();
					carousel_posts.jcarousel("scroll", "-=1");
				});

				jQuery(".post_slider_main_next").click(function(e) {
					e.preventDefault();
					carousel_posts.jcarousel("scroll", "+=1");
				});
				return false;
			}
		});
	}

	jQuery(window).bind("debouncedresize", function() {
		jQuery('.wpb_thumbnails').isotope();
	});

	function vh_ajax_get_animals(type,categories,limit,link,ajaxurl) {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "load-animals", animal_type: type, animal_categories: categories, animal_limit: limit, animals_link: link },
			success: function(response) {
				jQuery("#animal_main_container").html(response);
				return false;
			}
		});
	}

	vh_ajax_get_animals('animals', jQuery('#animal_places_animals').val(), jQuery('#animal_places_limit').val(), jQuery('#animal_places_link').val(), my_ajax.ajaxurl);

	jQuery(".animal_slider_animals").live('click', function(e) {
		e.preventDefault();
		jQuery('.tt-grid.tt-effect-3dflip').fadeTo(200,0);
		jQuery('.animal_slider_loading').show();
		vh_ajax_get_animals('animals', jQuery('#animal_places_animals').val(), jQuery('#animal_places_limit').val(), jQuery('#animal_places_link').val(), my_ajax.ajaxurl);
		jQuery('.animal_slider_places').removeClass('active');
		jQuery(this).addClass('active');
	});

	jQuery(".animal_slider_places").live('click', function(e) {
		e.preventDefault();
		jQuery('.tt-grid.tt-effect-3dflip').fadeTo(200,0);
		jQuery('.animal_slider_loading').show();
		vh_ajax_get_animals('places', jQuery('#animal_places_places').val(), jQuery('#animal_places_limit').val(), jQuery('#animal_places_link').val(), my_ajax.ajaxurl);
		jQuery('.animal_slider_animals').removeClass('active');
		jQuery(this).addClass('active');
	});

	jQuery('.contacts-member-send').click(function(e) {
		e.preventDefault();
		jQuery('.contacts-member-send').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('.contact_border_right').css('top', '-25px');
		jQuery('.contact-form-send > div').hide();
		jQuery('.contact-form-send > div').css('opacity', '0');
		if ( !jQuery('.contact-form-container-'+jQuery(this).parent().find('input[type=hidden]').val()).hasClass('name') ) {
			jQuery('.contact-form-container-'+jQuery(this).parent().find('input[type=hidden]').val()).find('h3').html(jQuery('.contact-form-container-'+jQuery(this).parent().find('input[type=hidden]').val()).find('h3').html()+jQuery(this).parent().parent().find('.member-name').html().replace(/\s+/, ""))
			jQuery('.contact-form-container-'+jQuery(this).parent().find('input[type=hidden]').val()).addClass('name');
		};
		jQuery('.contact-form-container-'+jQuery(this).parent().find('input[type=hidden]').val()).show().animate({
			opacity: 1
		  }, 500, function() {
			// Animation complete.
		});
	});


	vh_ajax_get_posts('recent', jQuery('#post_slider_categories').val(), jQuery('#post_slider_limit').val(), my_ajax.ajaxurl);

	jQuery(".post_slider_recent").click(function(e) {
		e.preventDefault();
		jQuery('#post_carousel_content ul').fadeTo(200,0);
		jQuery('.slider_loading').show();
		vh_ajax_get_posts('recent', jQuery('#post_slider_categories').val(), jQuery('#post_slider_limit').val(), my_ajax.ajaxurl);
		jQuery('.post_slider_popular').removeClass('active');
		jQuery(this).addClass('active');
	});

	jQuery(".post_slider_popular").click(function(e) {
		// jQuery("#post_carousel_content").jcarousel();
		e.preventDefault();
		jQuery('#post_carousel_content ul').fadeTo(200,0);
		jQuery('.slider_loading').show();
		vh_ajax_get_posts('popular', jQuery('#post_slider_categories').val(), jQuery('#post_slider_limit').val(), my_ajax.ajaxurl);
		jQuery('.post_slider_recent').removeClass('active');
		jQuery(this).addClass('active');
	});

	function vh_get_donations(keywords,types,times,ajaxurl) {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "donations-filter", keyword: keywords, type: types, time: times},
			success: function(response) {
				jQuery("#donations_list_content").html(response);

				return false;
			}
		});
	}

	vh_get_donations('', 'all', 'first', my_ajax.ajaxurl);

	jQuery('#donation-filter-submit').live('click', function(e) {
		e.preventDefault();
		jQuery('#donations_list_content').css('opacity', '0');
		jQuery('.donations_loading').css('opacity', '1');
		vh_get_donations(jQuery('#donation_search').val(), jQuery('.donation-type select').val(), '', my_ajax.ajaxurl);
	});

	// jQuery(".tagcloud").each(function(index){
	// 	var otags_a = jQuery(this).find("a"),
	// 	otags_number = otags_a.length,
	// 	otags_increment = 1 / otags_number,
	// 	otags_opacity = "";

	// 	jQuery(otags_a.get().reverse()).each(function(i,el) {
	// 	el.id = i + 1;
	// 	otags_opacity = el.id / otags_number - otags_increment;
	// 	if (otags_opacity < 0.2)
	// 		otags_opacity = 0.2;
	// 	jQuery(this).css({ backgroundColor: 'rgba(150,150,150,'+otags_opacity+')' });
	// 	});
	// });

	jQuery('.tribe-events-month-event-title a').live('mouseenter', function(e) {
		jQuery(this).parent().addClass('active');
	});

	jQuery('.tribe-events-tooltip').live('mouseleave', function(e) {
		jQuery('.tribe-events-month-event-title a').parent().removeClass('active');
	});

	if ( jQuery(window).width() >= 767 ) {
		jQuery("a.menu-trigger").click(function() {
			jQuery(".mp-menu").css({top: jQuery(document).scrollTop() });

			return false;
		});
	}

	jQuery(".fixed_menu .social-container").css({ 'top' : (jQuery(window).height()) - ( jQuery(".fixed_menu .social-container").height() + 60 ) });

	jQuery(".gallery-icon a").attr('rel', 'prettyphoto');

	jQuery("a[rel^='prettyPhoto']").prettyPhoto();

	// Opacity hover effect
	jQuery(".opacity_hover").mouseenter(function() {
		var social = this;
		jQuery(social).animate({ opacity: "0.8" }, 80, function() {
			jQuery(social).animate({ opacity: "1.0" }, 80);
		});
	});

	var $window = $(window);
	var windowHeight = $window.height();

	$window.resize(function () {
		windowHeight = $window.height();
		jQuery(".fixed_menu .social-container").css({ 'top' : (jQuery(window).height()) - ( jQuery(".fixed_menu .social-container").height() + 60 ) });
	});

	/**
	 * jQuery.LocalScroll - Animated scrolling navigation, using anchors.
	 * Copyright (c) 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
	 * Dual licensed under MIT and GPL.
	 * Date: 3/11/2009
	 * @author Ariel Flesler
	 * @version 1.2.7
	 **/
	;(function($){var l=location.href.replace(/#.*/,'');var g=$.localScroll=function(a){$('body').localScroll(a)};g.defaults={duration:1e3,axis:'y',event:'click',stop:true,target:window,reset:true};g.hash=function(a){if(location.hash){a=$.extend({},g.defaults,a);a.hash=false;if(a.reset){var e=a.duration;delete a.duration;$(a.target).scrollTo(0,a);a.duration=e}i(0,location,a)}};$.fn.localScroll=function(b){b=$.extend({},g.defaults,b);return b.lazy?this.bind(b.event,function(a){var e=$([a.target,a.target.parentNode]).filter(d)[0];if(e)i(a,e,b)}):this.find('a,area').filter(d).bind(b.event,function(a){i(a,this,b)}).end().end();function d(){return!!this.href&&!!this.hash&&this.href.replace(this.hash,'')==l&&(!b.filter||$(this).is(b.filter))}};function i(a,e,b){var d=e.hash.slice(1),f=document.getElementById(d)||document.getElementsByName(d)[0];if(!f)return;if(a)a.preventDefault();var h=$(b.target);if(b.lock&&h.is(':animated')||b.onBefore&&b.onBefore.call(b,a,f,h)===false)return;if(b.stop)h.stop(true);if(b.hash){var j=f.id==d?'id':'name',k=$('<a> </a>').attr(j,d).css({position:'absolute',top:$(window).scrollTop(),left:$(window).scrollLeft()});f[j]='';$('body').prepend(k);location=e.hash;k.remove();f[j]=d}h.scrollTo(f,b).trigger('notify.serialScroll',[f])}})(jQuery);
});

function header_size() {

	jQuery(window).on('touchmove', function(event) {
		set_height();
	});
	var win    = jQuery(window),
	header     = jQuery('.header .top-header'),
	logo       = jQuery('.header .top-header .logo img'),
	elements   = jQuery('.header, .top-header .header-social-icons div a, .top-header .logo, .top-header .header_search, .header_search .search .gray-form .footer_search_input, .top-header .menu-btn.icon-menu-1'),
	el_height  = jQuery(elements).filter(':first').height(),
	isMobile   = 'ontouchstart' in document.documentElement,
	set_height = function() {
		var st = win.scrollTop(), newH = 0;

		if(st < el_height/2) {
			newH = el_height - st;
			header.removeClass('header-small');
		} else {
			newH = el_height/2;
			header.addClass('header-small');
		}

		elements.css({'height': newH + 'px', 'line-height': newH + 'px'});
		logo.css({'max-height': newH + 'px'});
	}

	if(!header.length) {
		return false;
	}

	win.scroll(set_height);
	set_height();
}

// debulked onresize handler

function on_resize(c,t){
	"use strict";

	var onresize=function(){clearTimeout(t);t=setTimeout(c,100);};return c;
}


function clearInput (input, inputValue) {
	"use strict";

	if (input.value === inputValue) {
		input.value = '';
	}
}

// function moveOffset() {
// 	if( jQuery(".full-width").length ) {
// 		var offset = jQuery(".full-width").position().left;
// 		jQuery(".full-width").css({
// 			width: jQuery('.main').width(),
// 			marginLeft: -offset
// 		});
// 	};
// };

jQuery(document).ready(function() {
	"use strict";

	// Top menu
	if( jQuery(".header .sf-menu").length ) {
		var menuOptions = {
			speed:      'fast',
			speedOut:   'fast',
			hoverClass: 'sfHover',
		}
		// initialise plugin
		var menu = jQuery('.header .sf-menu').superfish(menuOptions);
	}
	// !Top menu

	// Search widget
	jQuery('.search.widget .sb-icon-search').click(function(el){
		el.preventDefault();
		jQuery('.search.widget form').submit();
	});
	// !Seaarch widget

	// Search widget
	jQuery('.search-no-results .main-inner .sb-icon-search').click(function(el){
		el.preventDefault();
		jQuery('.search-no-results .main-inner .search form').submit();
	});
	// !Seaarch widget
	

	// Social icons hover effect
	jQuery(".social_links li a").mouseenter(function() {
		var social = this;
		jQuery(social).animate({ opacity: "0.5" }, 250, function() {
			jQuery(social).animate({ opacity: "1.0" }, 100);
		});
	});
	// !Social icons hover effect

	// Widget contact form - send
	jQuery("#contact_form").submit(function() {
		jQuery("#contact_form").parent().find("#error, #success").hide();
		var str = jQuery(this).serialize();
		jQuery.ajax({
			type: "POST",
			url: my_ajax.ajaxurl,
			data: 'action=contact_form&' + str,
			success: function(msg) {
				if(msg === 'sent') {
					jQuery("#contact_form").parent().find("#success").fadeIn("slow");
				} else {
					jQuery("#contact_form").parent().find("#error").fadeIn("slow");
				}
			}
		});
		return false;
	});
	// !Widget contact form - send

	/* Merge gallery */
	jQuery('.merge-gallery div').mouseenter(function() {
		jQuery(this).find('.gallery-caption').animate({
			bottom: jQuery(this).find('img').height()
		},250);
	}).mouseleave(function() {
		jQuery(this).find('.gallery-caption').animate({
			bottom: jQuery(this).find('img').height() + 150
		},250);
	});
});