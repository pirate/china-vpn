jQuery(function($){
    $('#siteorigin-panels-dismiss').click(function(e){
        e.preventDefault();
        var $$ = $(this);
        $.get( $$.attr('href') );
        $$.closest('.updated').slideUp(function(){ $(this).remove(); });
    });
});