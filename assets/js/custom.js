$(function() {
    
    $('a[data-method=post]').on('click', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        $('<form action="' + url + '" method="post"></form>').appendTo('body').submit();
    });
});