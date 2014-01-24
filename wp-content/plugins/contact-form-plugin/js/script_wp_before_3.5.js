(function($) {
	$(document).ready( function() {
		$( '#cntctfrm_additions_options' ).change( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '.cntctfrm_additions_block' ).removeClass( 'cntctfrm_hidden' );
				$( '#cntctfrm_hide_additional_settings' ).css( 'display', 'block' );
			} else {
				$( '.cntctfrm_additions_block' ).addClass( 'cntctfrm_hidden' );
				$( '#cntctfrm_show_additional_settings' ).css( 'display', 'none' );
				$( '#cntctfrm_hide_additional_settings' ).css( 'display', 'none' );
			}
		});
		if ( $( '#cntctfrm_additions_options' ).is( ':checked' ) ) {
			$( '#cntctfrm_show_additional_settings' ).css( 'display', 'block' );
			$( '.cntctfrm_additions_block' ).addClass( 'cntctfrm_hidden' );
		}
		$( '#cntctfrm_show_additional_settings' ).click( function() {
			$( this ).css( 'display', 'none' );
			$( '#cntctfrm_hide_additional_settings' ).css( 'display', 'block' );
			$( '.cntctfrm_additions_block' ).removeClass( 'cntctfrm_hidden' );
		});
		$( '#cntctfrm_hide_additional_settings' ).click( function() {
			$( this ).css( 'display', 'none' );
			$( '#cntctfrm_show_additional_settings' ).css( 'display', 'block' );
			$( '.cntctfrm_additions_block' ).addClass( 'cntctfrm_hidden' );
		});

		$( '#cntctfrm_change_label' ).change( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '.cntctfrm_change_label_block' ).removeClass( 'cntctfrm_hidden' );
			} else {
				$( '.cntctfrm_change_label_block' ).addClass( 'cntctfrm_hidden' );
			}
		});
		$( '#cntctfrm_display_add_info' ).change( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '.cntctfrm_display_add_info_block' ).removeClass( 'cntctfrm_hidden' );
			} else {
				$( '.cntctfrm_display_add_info_block' ).addClass( 'cntctfrm_hidden' );
			}
		});
		$( '#cntctfrm_add_language_button' ).click( function() {
			$.ajax({
				url: '../wp-admin/admin-ajax.php',/* update_url, */
				type: "POST",
				data: "action=cntctfrm_add_language&lang=" + $( '#cntctfrm_languages' ).val(),
				success: function( result ) {
					var lang_val = $( '#cntctfrm_languages' ).val();
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab, .cntctfrm_action_after_send_block .cntctfrm_language_tab' ).each( function() {
						$( this ).addClass( 'hidden' );
					});
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).first().clone().appendTo( '.cntctfrm_change_label_block' ).removeClass( 'hidden' ).removeClass( 'cntctfrm_tab_en' ).addClass( 'cntctfrm_tab_' + lang_val );
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).first().clone().insertBefore( '#cntctfrm_before' ).removeClass( 'hidden' ).removeClass( 'cntctfrm_tab_en' ).addClass( 'cntctfrm_tab_' + lang_val );
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).last().find( 'input' ).each( function() {
						$( this ).val( '' );
						$( this ).attr( 'name', $( this ).attr( 'name' ).replace( '[en]', '[' + lang_val + ']' ) );
					});
					var text = $( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).last().find( '.cntctfrm_info' ).last().text();
					text = text.replace( 'lang=en', 'lang=' + lang_val );
					text = text.replace( ' or [contact_form]', '' );
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).last().find( '.cntctfrm_info' ).last().text( text );
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).last().find( 'input' ).val( '' ).attr( 'name', $( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).last().find( 'input' ).attr( 'name' ).replace( '[en]', '[' + lang_val + ']' ) );
					text = $( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).last().find( '.cntctfrm_info' ).last().text();
					text = text.replace('lang=en', 'lang='+lang_val);
					text = text.replace(' or [contact_form]', '');
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).last().find( '.cntctfrm_info' ).last().text( text );
					$( '.cntctfrm_change_label_block .cntctfrm_label_language_tab, .cntctfrm_action_after_send_block .cntctfrm_label_language_tab' ).each( function() {
						$( this ).removeClass( 'cntctfrm_active' );
					});
					$( '.cntctfrm_change_label_block .clear' ).prev().clone().attr( 'id','cntctfrm_label_' + lang_val ).addClass( 'cntctfrm_active' ).html( $( '#cntctfrm_languages option:selected' ).text() + ' <span class="cntctfrm_delete" rel="' + lang_val + '">X</span>').insertBefore( '.cntctfrm_change_label_block .clear' );
					$( '.cntctfrm_action_after_send_block .clear' ).prev().clone().attr( 'id','cntctfrm_label_' + lang_val ).addClass( 'cntctfrm_active' ).html( $( '#cntctfrm_languages option:selected' ).text() + ' <span class="cntctfrm_delete" rel="' + lang_val + '">X</span>').insertBefore( '.cntctfrm_action_after_send_block .clear' );
					$( '#cntctfrm_languages option:selected' ).remove();
				},
				error: function( request, status, error ) {
					alert( error + request.status );
				}
			});
		});
		$( '.cntctfrm_change_label_block .cntctfrm_label_language_tab').live('click', function(){
			$( '.cntctfrm_label_language_tab').each(function(){
				$( this ).removeClass('cntctfrm_active');
			});
			var index = $( '.cntctfrm_change_label_block .cntctfrm_label_language_tab' ).index( $( this ) );
			$( this ).addClass( 'cntctfrm_active' );
			var blocks = $( '.cntctfrm_action_after_send_block .cntctfrm_label_language_tab' );
			$( blocks[ index ] ).addClass( 'cntctfrm_active');
			$( '.cntctfrm_language_tab' ).each( function() {
				$( this ).addClass( 'hidden');
			});
			$( '.' + this.id.replace( 'label', 'tab' ) ).removeClass( 'hidden' );
		});
		$( '.cntctfrm_action_after_send_block .cntctfrm_label_language_tab' ).live( 'click', function() {
			$( '.cntctfrm_label_language_tab' ).each( function() {
				$( this ).removeClass( 'cntctfrm_active' );
			});
			var index = $( '.cntctfrm_action_after_send_block .cntctfrm_label_language_tab' ).index( $( this ) );
			$( this ).addClass('cntctfrm_active' );
			var blocks = $('.cntctfrm_change_label_block .cntctfrm_label_language_tab' );
			$( blocks[ index ] ).addClass( 'cntctfrm_active' );
			$( '.cntctfrm_language_tab' ).each( function() {
				$( this ).addClass( 'hidden' );
			});
			console.log( this.id.replace( 'text', 'tab' ), index );
			$( '.' + this.id.replace( 'text', 'tab' ) ).removeClass( 'hidden' );
		});
		$( '.cntctfrm_delete' ).live( 'click', function( event ) {
			event.stopPropagation();
			if ( confirm( confirm_text ) ) {
				var lang = $( this ).attr('rel');
				$.ajax({
					url: '../wp-admin/admin-ajax.php',/* update_url, */
					type: "POST",
					data: "action=cntctfrm_remove_language&lang=" + lang,
					success: function(result) {
						$( '#cntctfrm_label_' + lang + ', #cntctfrm_text_' + lang + ', .cntctfrm_tab_' + lang ).each( function() {
							$( this ).remove();
						});
					$( '.cntctfrm_change_label_block .cntctfrm_label_language_tab' ).removeClass( 'cntctfrm_active' ).first().addClass( 'cntctfrm_active' );
					$( '.cntctfrm_action_after_send_block .cntctfrm_label_language_tab' ).removeClass( 'cntctfrm_active' ).first().addClass( 'cntctfrm_active' );
					$( '.cntctfrm_change_label_block .cntctfrm_language_tab' ).addClass( 'hidden').first().removeClass( 'hidden' );
					$( '.cntctfrm_action_after_send_block .cntctfrm_language_tab' ).addClass( 'hidden' ).first().removeClass( 'hidden' );
					},						
					error: function( request, status, error ) {
						alert( error + request.status );
					}
				});
			}
		});
		$( '.cntctfrm_language_tab_block' ).css( 'display', 'none' );
		$( '.cntctfrm_language_tab_block_mini' ).css( 'display', 'block' );		
		$( '.cntctfrm_language_tab_block_mini' ).live( 'click', function() {
			if ( $( '.cntctfrm_language_tab_block' ).css( 'display' ) == 'none' ) {
				$( '.cntctfrm_language_tab_block ').css( 'display', 'block' );
				$( '.cntctfrm_language_tab_block_mini' ).css('background-position', '1px -3px' );
			} else {
				$( '.cntctfrm_language_tab_block' ).css( 'display', 'none' );
				$( '.cntctfrm_language_tab_block_mini' ).css( 'background-position', '' );
			}
		});
		$( '.cntctfrm_help_box' ).mouseover( function() {
			$( this ).children().css( 'display', 'block' );
		});
		$( '.cntctfrm_help_box' ).mouseout( function() {
			$( this ).children().css( 'display', 'none' );
		});

		/* add notice about changing in the settings page */
		$( '#cntctfrm_settings_form input' ).bind( "change click select", function() {
			if ( $( this ).attr( 'id' ) != 'cntctfrm_hide_additional_settings' && $( this ).attr( 'id' ) != 'cntctfrm_show_additional_settings' && $( this ).attr( 'type' ) != 'submit' ) {
				$( '.updated.fade' ).css( 'display', 'none' );
				$( '#cntctfrm_settings_notice' ).css( 'display', 'block' );
			};
		});
		$( 'select[name="cntctfrm_user_email"]').focus( function() {
			$('#cntctfrm_select_email_user').attr( 'checked', 'checked' );
			$( '.updated.fade' ).css( 'display', 'none' );
			$( '#cntctfrm_settings_notice' ).css( 'display', 'block' );
		});
	});
})(jQuery);