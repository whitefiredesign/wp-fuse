$(function() {

    $(document).ready(function($) {
        var data = {
            'action'                : 'count_post_visit',
            'ajax-popular-posts'    : 1,
            'post_id'               : PopularPostsData.post_id
        };

        
        $.post(ajaxurl, data, function(response) {
             // console.log(response); // post_id
        });
    });
    
});