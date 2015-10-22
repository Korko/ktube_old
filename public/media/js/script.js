(function update_time() {
	$('.timer').each(function() {
		var date = moment($(this).data('date'), 'YYYY-MM-DD HH:mm:ss');
		$(this).text(date.fromNow());
	});
	setTimeout(update_time, 1000);
})();