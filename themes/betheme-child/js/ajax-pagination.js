jQuery(document).ready(function($){
    $('.ajax-pagination').each(function(){
        var $container = $(this);
        var instance   = $container.data('instance');
        var term       = $container.data('term');

        // Delegate click for both page links and next page
        $container.on('click', 'a.page, a.next_page', function(e){
            e.preventDefault(); // IMPORTANT to prevent full page reload

            var $link = $(this);
            var page = 1;

            // Extract page number from href
            var href = $link.attr('href');
            if(!href) return;

            var match = href.match(/page\/(\d+)/); // /page/2/ style
            if(match) page = parseInt(match[1]);
            else {
                match = href.match(/paged=(\d+)/); // ?paged=2 style
                if(match) page = parseInt(match[1]);
            }

            if(page < 1) page = 1;

            $.ajax({
                url: myAjax.url,
                type: 'POST',
                data: {
                    action: 'my_ajax_pagination',
                    page: page,
                    instance: instance,
                    term: term
                },
                beforeSend: function(){
                    $container.find('.posts_group.lm_wrapper').addClass('loading');
                },
                success: function(response){
                    if(response.success){
                        $container.find('.posts_group.lm_wrapper').html(response.data.html);
                        $container.find('.pagination-wrapper').html(response.data.pagination);
						
                        // Smooth scroll to the top of this instance
                        $('html, body').animate({
                            scrollTop: $container.offset().top - 96
                        }, 600); // 600ms scroll duration
                    }
                    $container.find('.posts_group.lm_wrapper').removeClass('loading');
                }
            });
        });
    });
});
