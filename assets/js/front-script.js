jQuery(function () {
    jQuery(".mmwea-wa-button.product_type_variable").addClass('disabled');
});

jQuery(document).ready(function () {

    jQuery(document).on("click", ".whatsapp-inquiry-button-wrapper a", function (e) {
        e.preventDefault();
    
        var originalHref =  decodeURIComponent(jQuery(this).attr('href'));
        var messageBody = '';
        
        if (typeof wcCartData !== 'undefined' && wcCartData.cartItems.length > 0) {
            var cartDetails = wcCartData.cartItems.map(function (item) {
                return "*Product Title*:- " + item.title + "\n*Product URL*:- " + item.url;
            }).join('\n\n');
    
            messageBody += "\n\n*Product Details:*\n" + cartDetails;
        }
    
        var encodedMessageBody = encodeURIComponent(messageBody);
        var buttonUrl = originalHref + encodedMessageBody;

        window.open(buttonUrl, '_blank');
    });

    jQuery("body").append('<div id="mmwea_type_variable"></div>');

    jQuery("body").on("hide_variation", ".variations_form", function () {
        jQuery(".mmwea-wa-button.product_type_variable").addClass('disabled');
    });

    jQuery("body").on("show_variation", ".variations_form", function (event, variation) {

        jQuery(".mmwea-wa-button.product_type_variable").removeClass('disabled');
        var res = "";
        var data = [];
        var final_variable = "";

        data = jQuery.parseJSON(variation.mmwea_selected_variation);
        var totalCount = data.length;
        var numberCounter = 0;

        if (data.length > 0) {
            jQuery.each(data, function (index, value) {
                // Store selection of variations
                final_variable += "%0D%0A" + value;

                numberCounter++;
                if (numberCounter == totalCount) {
                    // after promise
                    let mmwea_variable = jQuery("#mmwea_type_variable").text();
                    let butotn_url = jQuery(".mmwea-wa-button.product_type_variable").attr('href');

                    if (mmwea_variable.trim() != '') {
                        res = butotn_url.replace(mmwea_variable, final_variable);
                        jQuery("body #mmwea_type_variable").text(mmwea_variable);
                    } else {
                        res = butotn_url.replace("%7B%7Bproduct_variations%7D%7D", final_variable);
                        jQuery("body #mmwea_type_variable").text(final_variable);
                    }
                    jQuery(".mmwea-wa-button.product_type_variable").attr('href', res);
                }
            });
        }
    });
});