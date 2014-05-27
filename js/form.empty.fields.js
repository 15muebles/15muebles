jQuery(function() { 
	var error;
	jQuery("#quincem-form-content input[type=submit]").click(function() {
		jQuery("#quincem-form-content .req").each(function() {
			if ( jQuery(this).val() == null || jQuery(this).val() == "" ) {
				error = true;
			}
		});
		if ( error == true ) {
			alert("Asegúrate de haber rellenado todos los campos requeridos antes de enviar el formulario.");
			error = false;
			return false;
		}
	});
});
