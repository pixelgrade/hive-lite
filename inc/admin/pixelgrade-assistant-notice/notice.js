(function ($) {
	$(document).ready(function () {
		var temp_url = wp.ajax.settings.url,
			$noticeContainer = $( '.pixassist-notice' ),
			$button = $noticeContainer.find( '.js-handle-pixassist' ),
			$dismissButton = $noticeContainer.find( '.button.dismiss' ),
			$text = $noticeContainer.find( '.pixassist-notice-button__text' ),
			$status = $noticeContainer.find( '.js-plugin-message' );

		$button.on('click', function() {
			let installedSuccessfully = -1,
				activatedSuccessfully = -1,
				activatedAlready = -1,
				noActionTaken = -1,
				folderAlreadyExists = -1

			// Put the button in a loading state
			$button.addClass('state--plugin-installing').prop('disabled', true);

			// Hide the dismiss button
			$dismissButton.fadeOut(500);

			/*
			 * We need to determine what to do first, install or activate.
			 */
			if ( pixassistNotice.status === 'missing' ) {
				$text.html(pixassistNotice.i18n.btnInstalling);
				wp.ajax.settings.url = pixassistNotice.installUrl;
			} else if ( pixassistNotice.status === 'installed' ) {
				$text.html(pixassistNotice.i18n.btnActivating);
				wp.ajax.settings.url = pixassistNotice.activateUrl;
			}

			wp.a11y.speak($text.html());

			wp.ajax.send({type: 'GET'}).always(function (response) {
				installedSuccessfully = -1
				activatedSuccessfully = -1
				activatedAlready = -1
				noActionTaken = -1
				folderAlreadyExists = -1

				if (typeof response === 'string') {
					installedSuccessfully = response.indexOf('<p>' + pixassistNotice.i18n.installedSuccessfully + '</p>');
					activatedSuccessfully = response.indexOf('<div id="message" class="updated"><p>');
					noActionTaken = response.indexOf('<div id="message" class="error"><p>No action taken.');
					folderAlreadyExists = response.indexOf('<p>' + pixassistNotice.i18n.folderAlreadyExists + '</p>');
				}

				if (installedSuccessfully !== -1) {
					wp.a11y.speak(pixassistNotice.i18n.installedSuccessfully);

					/*
					 * We need to activate the plugin
					 */

					$text.html(pixassistNotice.i18n.btnActivating);
					wp.a11y.speak(pixassistNotice.i18n.btnActivating);

					wp.ajax.settings.url = pixassistNotice.activateUrl;

					$button.removeClass( 'state--plugin-installing' ).addClass( 'state--plugin-activating' );

					wp.ajax.send({type: 'GET'}).always(function (response) {
						activatedSuccessfully = -1
						noActionTaken = -1

						if (typeof response === 'string') {
							activatedSuccessfully = response.indexOf('<div id="message" class="updated"><p>');
							noActionTaken = response.indexOf('<div id="message" class="error"><p>No action taken.');
						}

						if (activatedSuccessfully !== -1 || noActionTaken !== -1) {
							doPluginReady();
						} else {
							$button.removeClass( 'state--plugin-activating' ).addClass( 'state--plugin-invalidated' );
							$text.html(pixassistNotice.i18n.btnError);

							$status.html(pixassistNotice.i18n.error);

							wp.a11y.speak(pixassistNotice.i18n.error);
						}

						wp.ajax.settings.url = temp_url;
					});

				} else if (folderAlreadyExists !== -1 || activatedSuccessfully !== -1 || noActionTaken !== -1) {
					doPluginReady();
				} else {
					$button.removeClass( 'state--plugin-activating' ).addClass( 'state--plugin-invalidated' );
					$text.html(pixassistNotice.i18n.btnError);

					$status.html(pixassistNotice.i18n.error);

					wp.a11y.speak(pixassistNotice.i18n.error);
				}

				wp.ajax.settings.url = temp_url;
			});
			wp.ajax.settings.url = temp_url;
		})

		function doPluginReady() {
			setTimeout( function() {
				wp.a11y.speak(pixassistNotice.i18n.activatedSuccessfully);

				$button.removeClass('state--plugin-activating').removeClass('state--plugin-installing').addClass('state--plugin-ready');
				// We don't need to take any action. Just leave the normal click.
				$button.unbind('click');

				$button.attr('href', pixassistNotice.pixassistSetupUrl);
				$text.html(pixassistNotice.i18n.btnGoToSetup);

				wp.a11y.speak(pixassistNotice.i18n.clickStartTheSiteSetup);
			}, 1000);
		}

		// Send ajax on click of dismiss icon
		$noticeContainer.on( 'click', '.notice-dismiss', function() {
			ajaxDismiss( $(this) );
		});

		// Send ajax
		function ajaxDismiss( dismissElement ) {
			$.ajax({
				url: pixassistNotice.ajaxurl,
				type: 'post',
				data: {
					action: 'pixassist_install_dismiss_admin_notice',
					nonce_dismiss: $noticeContainer.find('#nonce-pixassist_install-dismiss').val()
				}
			})
		}
	});
})(jQuery);
