(function update_time() {
	$('.timer').each(function() {
		var date = moment($(this).data('date'), 'YYYY-MM-DD HH:mm:ss');
		$(this).text(date.fromNow());
	});
	setTimeout(update_time, 1000);
})();

function getVideos(resolve) {
        jQuery.ajax({
                url: "/videos/all",
        }).done(function(ajax) {
                resolve(ajax.data);
        })
}
