jQuery(function() {
    jQuery(".mmwea-wa-button.product_type_variable").addClass('disabled');
});


jQuery(document).ready(function() {

    jQuery("body").on("woocommerce_variation_select_change", ".variations_form", function() {
        // var variable = "";
        // jQuery(".variations tbody tr").each(function() {
        //     console.log(jQuery(this).find(".label label").text());
        //     console.log(jQuery(this).find(".value select option:selected").val());
        //     variable = jQuery(this).find(".label label").text() + ":- " + jQuery(this).find(".value select option:selected").val();
        //     console.log(variable);
        //     jQuery("body #mmwea_type_variable").text(variable);

        // });
    });

    jQuery("body").on("hide_variation", ".variations_form", function() {

        jQuery(".mmwea-wa-button.product_type_variable").addClass('disabled');

    });

    jQuery("body").on("show_variation", ".variations_form", function() {

        console.log("event fire");
        jQuery(".mmwea-wa-button.product_type_variable").removeClass('disabled');
        var variable = "";
        var final_variable = "";
        jQuery(".variations tbody tr").each(function() {
            // console.log(jQuery(this).find(".label label").text());
            // console.log(jQuery(this).find(".value select option:selected").val());
            variable = jQuery(this).find(".label label").text() + ":- " + jQuery(this).find(".value select option:selected").val();
            console.log(variable);
            jQuery("body #mmwea_type_variable").append("%0D%0A%0D%0A" + variable);

        });
        final_variable = jQuery("#mmwea_type_variable").text();
        console.log(final_variable);
        var butotn_url = jQuery(".mmwea-wa-button.product_type_variable").attr('href');
        // console.log(butotn_url);
        // let res = butotn_url.replace(/{{product_variations}}/, final_variable);
        let res = butotn_url.replace("{{product_variations}}", final_variable);
        console.log(res);
        jQuery(".mmwea-wa-button.product_type_variable").attr('href', res);

    });

    jQuery("body").append('<div id="mmwea_type_variable"></div>');

    // jQuery("body").on("click", "#mmwea-checkout-btn", function() {

    //     jQuery("#place_order").trigger("click");
    //     return false;
    // });

    // jQuery('body').on('init_add_payment_method', function() {
    //     console.log('init_checkout triggered');
    //     // now.do.whatever();
    // });

});