function ajax_cart_request(pars){
    $.post('ajax_cart.php', pars, function(response){
        $('#cart-sidebar-container').html(response.cart_sidebar);
        $('#shopping-cart-container').parent().html(response.cart_body);
        $('#cart-sidebar-badge').html(response.cart_badge);

    })
        .fail(function(error) {
            console.log("error in ajax_cart_request");
            console.log(error);
        })
}

function add_item(id){
    // alert("Button clicked, id "+this.id+", text"+this.innerHTML);
    ajax_cart_request({add: id});
}

function sub_item(id){
    ajax_cart_request({sub: id});
}

function del_item(id){
    ajax_cart_request({del: id});
}

function update_item(id, quantity){
    ajax_cart_request({set_item: id, set_quantity: quantity});
}

function add_to_cart(button, id){
    curr_elem = $(button);      // clicked element (start)
    cart = $("#cart-sidebar-button .fa-shopping-cart");     // cart element (target)
    animation = $(".addtocart-animation");  // animated div

    start = $.extend( $(curr_elem).offset(), $(curr_elem).width(), {"display": "block"} );
    target = $.extend( $(cart).offset(), $(cart).width() );
    console.log($(curr_elem).width());

    animation.css(start);
    animation.animate(target, 500, function(){
        animation.css({"display": "none"});
        cart.addClass('shake');
        setTimeout(function(){
            cart.removeClass('shake');
        }, 500)
    });

    ajax_cart_request({add: id});

}