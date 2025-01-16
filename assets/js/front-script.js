jQuery(function () {
    jQuery(".mmwea-wa-button.product_type_variable").addClass('disabled');
});

jQuery(document).ready(function () {

    jQuery(document).on("click", ".whatsapp-inquiry-button-wrapper a", function (e) {
        e.preventDefault();

        var originalHref = decodeURIComponent(jQuery(this).attr('href'));

        fetchCartData(function (cartData) {
            var messageBody = '';

            if (cartData.cartItems.length > 0) {
                var cartDetails = cartData.cartItems.map(function (item) {
                    var productDetails = "*Product Title*:- " + item.title + "\n*Product URL*:- " + item.url;
                    for (var key in item) {
                        if (item.hasOwnProperty(key) && key !== 'title' && key !== 'url') {
                            productDetails += "\n*" + key.charAt(0).toUpperCase() + key.slice(1) + "*:- " + item[key];
                        }
                    }

                    return productDetails;
                }).join('\n\n');

                messageBody += "\n\n*Product Details:*\n" + cartDetails;
            }

            var encodedMessageBody = encodeURIComponent(messageBody);
            var buttonUrl = originalHref + encodedMessageBody;

            window.open(buttonUrl, '_blank');
        });
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

function fetchCartData(callback) {
    jQuery.ajax({
        url: wc_add_to_cart_params.ajax_url, 
        type: 'POST',
        data: {
            action: 'get_cart_data',
        },
        success: function (response) {
            if (response.success) {
                callback(response.data);
            } else {
                console.error('Failed to fetch cart data:', response.data.message);
            }
        },
        error: function () {
            console.error('AJAX error: Unable to fetch cart data.');
        },
    });
}