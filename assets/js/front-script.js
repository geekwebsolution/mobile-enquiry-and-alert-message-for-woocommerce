jQuery(function() {
    jQuery(".mmwea-wa-button.product_type_variable").addClass('disabled');
});

jQuery(document).ready(function() {

    jQuery("body").on("hide_variation", ".variations_form", function() {

        jQuery(".mmwea-wa-button.product_type_variable").addClass('disabled');

    });

    jQuery("body").on("show_variation", ".variations_form", function() {

        console.log("event fire");
        jQuery(".mmwea-wa-button.product_type_variable").removeClass('disabled');
        var variable = "";
        var final_variable = "";
        jQuery(".variations tbody tr").each(function() {
            variable = jQuery(this).find(".label label").text() + ":- " + jQuery(this).find(".value select option:selected").val();
            console.log(variable);
            jQuery("body #mmwea_type_variable").append("%0D%0A%0D%0A" + variable);

        });
        final_variable = jQuery("#mmwea_type_variable").text();
        console.log(final_variable);
        var butotn_url = jQuery(".mmwea-wa-button.product_type_variable").attr('href');

        let res = butotn_url.replace("{{product_variations}}", final_variable);
        console.log(res);
        jQuery(".mmwea-wa-button.product_type_variable").attr('href', res);

    });

    jQuery("body").append('<div id="mmwea_type_variable"></div>');

});