$(window).on('load', function() {
    $("#cart-sidebar-button").on("click", function() {
        set_sidebar_position()
        $("#cart-sidebar-container").animate({width: "toggle"});
    });

    $("#cart-sidebar-button")
        .on("mouseenter", function() {
            set_sidebar_position()
            $("#cart-sidebar-container").slideDown();
        })
        .on("mouseleave", function () {
            $("#cart-sidebar-container").slideUp();
        });
});

function set_sidebar_position(){
    var button = $("#cart-sidebar-button");

    var offset = button.offset();
    var top = (offset.top + button.height()) + "px";
    var right = $(window).width() - (offset.left + button.width() + 50) + "px";

    $('#cart-sidebar-container').css( {
        'position': 'absolute',
        'right': right,
        'top': top,
        'z-index': 101
    });
}