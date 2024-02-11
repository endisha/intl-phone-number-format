jQuery( document ).ready(
	function ($) {
		$( "input#intl_phone_number_format_lookup_active" )
		.on(
			"change",
			function () {
				if (jQuery( this ).is( ":checked" )) {
					jQuery( this )
					.closest( "tbody" )
					.find( ".intl_phone_number_format_lookup_field" )
					.closest( "tr" )
					.show();
				} else {
					jQuery( this )
					.closest( "tbody" )
					.find( ".intl_phone_number_format_lookup_field" )
					.closest( "tr" )
					.hide();
				}
			}
		)
		.trigger( "change" );

		$( "input#intl_phone_number_format_active" )
		.on(
			"change",
			function () {
				if (jQuery( this ).is( ":checked" )) {
					jQuery( ".additional-fields" ).removeClass( "disabled" );
					jQuery( ".additional-fields-haed" ).removeClass( "disabled" );
					jQuery( ".additional-fields-desc" ).removeClass( "disabled" );
					jQuery( "input#intl_phone_number_format_lookup_active" )
					.parent()
					.parent()
					.parent()
					.parent()
					.removeClass( "disabled" );
					jQuery( "input#intl_phone_number_format_lookup_ttl" )
					.parent()
					.parent()
					.removeClass( "disabled" );
				} else {
					jQuery( ".additional-fields" ).addClass( "disabled" );
					jQuery( ".additional-fields-haed" ).addClass( "disabled" );
					jQuery( ".additional-fields-desc" ).addClass( "disabled" );
					jQuery( "input#intl_phone_number_format_lookup_active" )
					.parent()
					.parent()
					.parent()
					.parent()
					.addClass( "disabled" );
					jQuery( "input#intl_phone_number_format_lookup_ttl" )
					.parent()
					.parent()
					.addClass( "disabled" );
				}
			}
		)
		.trigger( "change" );
	}
);
