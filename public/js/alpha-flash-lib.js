class Flash 
{

    constructor()
    {
        console.log('Flash class instantiated.');
    }

    modifyDOM()
    {
        var $messageBoxes = $(".alpha-flash-box");
        var $errors = $(".alpha-flash-box.flash-error");
        var $notifications = $(".alpha-flash-box.flash-notification");
        var $successes = $(".alpha-flash-box.flash-success");

        $messageBoxes.on("click", function(e){
            $(this).fadeOut(500);
        });

        $errors.each(function(){
            $(this).prepend('<img class="flash-box-icon" src="/img/Alpha/flash_error.png" />');
        });

        $notifications.each(function(){
            $(this).prepend('<img class="flash-box-icon" src="/img/Alpha/flash_notification.png" />');
        });

        $successes.each(function(){
            $(this).prepend('<img class="flash-box-icon" src="/img/Alpha/flash_success.svg" />');
        });
    }

    fadeOutAll()
    {
        var $messageBoxes = $(".alpha-flash-box.auto-fade");

        setTimeout(function(){
            $messageBoxes.each(function(){
                $(this).fadeOut(500);
            });
        }, 5000);
    }

}

$(document).ready(function() {
    var F = new Flash();
    F.modifyDOM();
    F.fadeOutAll();
});