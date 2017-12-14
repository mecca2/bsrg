;(function($, window, document, undefined) {
	var $win = $(window);
	var $doc = $(document);
	var $html = $(document.documentElement);

	$doc.ready(function() {
		var availableTags = [
			"ActionScript",
			"AppleScript",
			"Asp",
			"BASIC",
			"C",
			"C++",
			"Clojure",
			"COBOL",
			"ColdFusion",
			"Erlang",
			"Fortran",
			"Groovy",
			"Haskell",
			"Java",
			"JavaScript",
			"Lisp",
			"Perl",
			"PHP",
			"Python",
			"Ruby",
			"Scala",
			"Scheme"
		];

		var availableTags =[
			{
				"label": "Paris Ave East, 1506 London Avenue, Port Royal, SC 29935",
				"value": "http:\/\/pkostadinov.2create.studio\/projects\/field_gate_media\/bay-street-realty-group\/wp\/property\/paris-ave-east-1506-london-avenue-port-royal-sc-29935\/"
			},
			{
				"label": "Shorts LD\/Caper, 15 Knollwood Lane, Beaufort, SC 29907",
				"value": "http:\/\/pkostadinov.2create.studio\/projects\/field_gate_media\/bay-street-realty-group\/wp\/property\/shorts-ld-caper-15-knollwood-lane-beaufort-sc-29907\/"
			},
			{
				"label": "Frontage-Ribaut, 1536 Ribaut Road, Port Royal, SC 29935",
				"value": "http:\/\/pkostadinov.2create.studio\/projects\/field_gate_media\/bay-street-realty-group\/wp\/property\/frontage-ribaut-1536-ribaut-road-port-royal-sc-29935\/"
			},
			{
				"label": "Ocean Creek\/Vb, 156 Davis Love Drive, Fripp Island, SC 29920",
				"value": "http:\/\/pkostadinov.2create.studio\/projects\/field_gate_media\/bay-street-realty-group\/wp\/property\/156-davis-love-drive\/"
			},
			{
				"label": "Pigeon Pt-Greenlawn, 1512 Sycamore Street, Beaufort, SC 29902",
				"value": "http:\/\/pkostadinov.2create.studio\/projects\/field_gate_media\/bay-street-realty-group\/wp\/property\/1512-sycamore-street\/"
			},
			{
				"label": "Ocean Creek\/Vb, 154 Davis Love, Fripp Island, SC 29920",
				"value": "http:\/\/pkostadinov.2create.studio\/projects\/field_gate_media\/bay-street-realty-group\/wp\/property\/154-davis-love\/"
			},
			{
				"label": "HH\/Off Plantation, 155 Dillon 2520 Road, Hilton Head Island, SC 29926",
				"value": "http:\/\/pkostadinov.2create.studio\/projects\/field_gate_media\/bay-street-realty-group\/wp\/property\/155-dillon\/"
			},
			{
				"label": "South Forest Beach, 15 Deallyon 65 Avenue, Hilton Head Island, SC 29928",
				"value": "http:\/\/pkostadinov.2create.studio\/projects\/field_gate_media\/bay-street-realty-group\/wp\/property\/15-deallyon\/"
			}
		];

		var $searchField = $( '#search-field' );

		$searchField.autocomplete({
			delay: 500,
			appendTo: $('body:not(.home) .form-filter'),
			source: function( request, response ) {
				var formData = $( '#search-field' ).closest( 'form' ).serialize();

				formData += '&action=crb_search_autocomplete';

				$.ajax({
					url: ajax_object.ajax_url,
					dataType: 'json',
					data: formData,
					success: function( data ) {
						$searchField.removeClass( 'ui-loading' );
						response( data );
					},
					error: function( error ) {
						response( [] );
					}
				});
			},
			select: function( event, ui ) {
				if ( ui.item && ui.item.value ) {
					window.location.href = ui.item.value;
				};
			},
			search: function( event, ui ) {
				$searchField.addClass( 'ui-loading' );
			},
			open: function() {
				$searchField.removeClass( 'ui-loading' );
			},
			close: function( event, ui ) {
				$searchField.removeClass( 'ui-loading' );
			}
		});

		//Video Popup
		$('.btn-play').magnificPopup({
			type: 'iframe',
			iframe: {
				markup: '<div class="mfp-iframe-scaler">'+
						'<div class="mfp-close"></div>'+
						'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
						'</div>',
				patterns: {
					youtube: {
						index: 'youtube.com/',
						id: 'v=',
						src: '//www.youtube.com/embed/%id%?autoplay=1'
					},
					vimeo: {
						index: 'vimeo.com/',
						id: '/',
						src: '//player.vimeo.com/video/%id%?autoplay=1'
					},
					gmaps: {
						index: '//maps.google.',
						src: '%id%&output=embed'
					}
				},

				srcAction: 'iframe_src'
			}
		});

		detectBrowserType();
		initClickEvents();
		propertyGalleryInit();
		selectFilters();

		//Magnific Popup slider slideshow
		$('.slider--slideshow .ico-full-screen').magnificPopup({
			type: 'image',
			closeOnContentClick: true,
			mainClass: 'mfp-img-mobile mfp-full-width',
			image: {
				verticalFit: true
			}
		});
	});

	$win.on('load', function() {
		sliderInit();
		equalizeHeights($('.slider--main .slider__slide'));
		equalizeHeights($('.slider--main .slider__slide-image img'));
		equalizeHeights($('.tiles.owl-carousel .tile'));
		equalizeHeights($('.tiles.owl-carousel .tile img'));
	});

	function propertyGalleryInit() {
		var items = [];
		if ( $('.source-gallery').length ) {
			items = $('.source-gallery').data( 'items' );
			items = $.parseJSON( urlDecode( items ) );
		}

		$('.section__gallery .background-image a').on( 'click', function() {
			var index = $(this).data( 'index' );

			$.magnificPopup.open({
				type: 'image',
				items: items,
				gallery: {
					enabled: true
				},
			}, index);
		} );
	}

	// Set class for touch enabled/disabled devices
	function detectBrowserType() {
		if ( /Mobi/i.test(navigator.userAgent) || /Android/i.test(navigator.userAgent) ) {
			$html.addClass('touch');
		}

		else {
			$html.addClass('no-touch');
		}
	}

	//Equalize Heights
	function equalizeHeights($item) {
		var height = 0;
		$item.removeAttr('style');

		$item.each(function() {
			var elHeight = $(this).innerHeight();

			if ( height < elHeight) {
				height = elHeight;
			}
		});

		$item.css('height', height);
	}

	//Slider Init
	function sliderInit() {
		$('.slider--communities .owl-carousel').owlCarousel({
			items: 1,
			nav: true,
			navText: ['<i class="ico-arrow-left"></i>', '<i class="ico-arrow-right"></i>']
		});

		$('.slider--main .owl-carousel').owlCarousel({
			nav: true,
			navText: ['<i class="ico-arrow-left"></i>', '<i class="ico-arrow-right"></i>'],
			margin: 30,
			responsive: {
				0: {
					items: 1
				},

				479: {
					items: 2
				},

				768: {
					items: 3
				}
			}
		});

		$('.slider--testimonials .owl-carousel').owlCarousel({
			items: 1,
			nav: true,
			navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
			dots: true
		});

		$('.slider--slideshow .owl-carousel').owlCarousel({
			items: 1,
			nav: true,
			loop: true,
			autoplay: true,
			navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>']
		});

		$('.tiles--1of3.owl-carousel').owlCarousel({
			nav: true,
			margin: 30,
			navText: ['<i class="ico-arrow-left"></i>', '<i class="ico-arrow-right"></i>'],
			responsive: {
				0: {
					items: 1
				},

				768: {
					items: 2
				},

				1024: {
					items: 3
				}
			}
		});
	}

	//Click events
	function initClickEvents() {
		//Accordion show/hide
		$('.accordion .accordion__head').on('click', function(event) {
			event.preventDefault();

			$(this).parent().toggleClass('active');
			$(this).parent().siblings().removeClass('active');
		});

		//Mobile menu show/hide
		$('.btn-menu').on('click', function(event) {
			event.preventDefault();

			$('body').toggleClass('show-nav-main');
		});

		//Show submenu mobile
		if($win.width() < 768) {
			$('.nav > ul > li.menu-item-has-children > a').on('click', function(e) {
				var $this = $(this);

				if ( !$this.parent().hasClass('open') ) {
					e.preventDefault();
					$(this).parent().find('ul').slideToggle();
				}

				$this.parent().toggleClass('open');
			});
		}
	}

	urlDecode = function(str) {
		return decodeURIComponent((str + '')
			.replace(/%(?![\da-f]{2})/gi, function() {
				// PHP tolerates poorly formed escape sequences
				return '%25';
			})
			.replace(/\+/g, '%20'));
	}

	function selectFilters() {
		$('.widgets-sidebar .form-filter select').selectric({
			 disableOnMobile: false,
		});
	}

})(jQuery, window, document);
