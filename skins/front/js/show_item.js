function show_price(price, quantity){
    $("#total_item_price").html((price * quantity).toFixed(2));
}


// recensioni slider
$(document).ready(function(){
    $('.recensione_row:first').show();
    var current_img=0;

    $('.next_recensione_button').click(function(){
        $('.recensione_row').eq(current_img).hide();
        current_img++;
        current_img = current_img < $('.recensione_row').length ? current_img : 0;
        $('.recensione_row').eq(current_img).fadeIn();
    });

    $('.previous_recensione_button').click(function(){
        $('.recensione_row').eq(current_img).hide();
        current_img--;
        current_img = current_img < 0 ? $('.recensione_row').length-1 : current_img;
        $('.recensione_row').eq(current_img).fadeIn();
    });

});