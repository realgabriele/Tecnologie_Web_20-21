function ajax_cart_request(pars){
    $.post('ajax_cart.php', pars, function(response){
        $('#cart-sidebar').html(response.cart_sidebar);
        $('#shopping-cart-container').parent().html(response.cart_body);
        $('#cart-sidebar-badge').html(response.cart_badge);

    })
        .fail(function() { console.log("error in ajax_cart_request"); })
}

function add_item(id){
    // alert("Button clicked, id "+this.id+", text"+this.innerHTML);
    ajax_cart_request({add: id});
}

function sub_item(id){
    ajax_cart_request({sub: id});
}

function del_item(){

}

function add_to_cart(button, id){
    curr_elem = $(button);      // clicked element (start)
    cart = $("#cart-sidebar-button .fa-shopping-cart");     // cart element (target)
    animation = $(".addtocart-animation");  // animated div

    start_pos = $(curr_elem).offset();
    target_pos = $(cart).offset();

    animation.css({"display": "block"});
    animation.css(start_pos);

    animation.animate(target_pos, 500, function(){
        animation.css({"display": "none"});
        cart.addClass('shake');
        setTimeout(function(){
            cart.removeClass('shake');
        }, 500)

    });

}