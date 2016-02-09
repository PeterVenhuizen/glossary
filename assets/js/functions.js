$(document).ready(function() {
    $('.options').hover(function() {
        $(this).children('.option').toggle(); 
    }); 
    
    $('.edit_icon').click(function() {
        var id = $(this).attr('value');
        window.location.replace('edit_post.php?id='+id);
    });
    
    $('.delete_icon').click(function() {
        var id = $(this).attr('value');
        console.log(id);
        if (confirm("Are you sure you want to delete this?")) {
            $.post("assets/ajax/delete_post.php", { id: id })
                .done(function(data) {
                    window.location.href="index.php";      
                });
        }
    });
    
	//Internal back to top links
	$(document).on('click', '.to_top', function (e) {
		$('html, body').animate({
			scrollTop: $("body").offset().top - $('#page_header').height() - 10
		}, 250);
	});
});
