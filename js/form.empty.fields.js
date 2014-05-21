jQuery(function() { 
	var error;
	jQuery("#quincem-form-content input[type=submit]").click(function() {
		jQuery("#quincem-form-content .form-control").each(function() {
			if ( jQuery(this).val() == null || jQuery(this).val() == "" ) {
				error = true;
			}
		});
		if ( error == true ) {
			alert("Todos los campos son necesarios para enviar el formulario.");
			error = false;
			return false;
		}
	});
});
