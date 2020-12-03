function show_price(price, quantity){
    $("#total_item_price").html((price * quantity).toFixed(2));
}
