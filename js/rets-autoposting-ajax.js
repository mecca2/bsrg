;(function($, window, document, undefined) {
	var $win = $(window);
	var $doc = $(document);

	$doc.ready(function() {
		updateProperties();

		function updateProperties() {
			$.ajax({
				url: ajax_object.ajax_url,
				type: 'GET',
				dataType: 'json',
				data: {
					action: ajax_object.action
				},
			})
			.done( function( response ) {
				var $notice = $('#crb-rets-admin-notice');
				var $statusContainer = $('.crb-js-status');

				if ( ajax_object.action == 'crb_rets_get_from_api' ) {
					if ( response.api_status ) {
						$statusContainer.html( response.api_status.join('<br />') );

						$notice
							.find('.loading').hide()
						.end()
							.find('.finished').show()
						.end()
							.removeClass('notice-info')
							.addClass('notice-success');
					};
				} else if ( ajax_object.action == 'crb_rets_update_posts_initial' ) {
					if ( response.status == 'completed' ) {
						document.location.href = ajax_object.redirect;
					};
				} else if ( ajax_object.action == 'crb_rets_update_posts' ) {
					if ( response.status == 'completed' ) {
						document.location.href = ajax_object.redirect;
					} else {
						$statusContainer.html( response.join('<br />') );
						updateProperties();
					};
				} else if ( ajax_object.action == 'crb_rets_force_update_all_posts' ) {
					if ( response.status == 'completed' ) {
						document.location.href = ajax_object.redirect;
					};
				} else if ( ajax_object.action == 'crb_rets_delete_all_posts' ) {
					if ( response.status == 'completed' ) {
						document.location.href = ajax_object.redirect;
					} else {
						$statusContainer.html( response.message );
						updateProperties();
					};
				} else if ( ajax_object.action == 'crb_rets_flush_all_caches' ) {
					// Simply Redirect
					document.location.href = ajax_object.redirect;
				};

			})
			.fail(function() {
				console.log( "error" );
			})
			.always(function() {
				console.log( "complete" );
			});
		}

	});

})(jQuery, window, document);
