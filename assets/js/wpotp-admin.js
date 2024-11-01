jQuery(document).ready(function ($) {

	if( $('#js__wpotp-page').length > 0 )
	{
		$('.js__wpotp-nav-link').on('click', function (e) {
			e.preventDefault();
			var target = $(this).attr('href');
			$('.js__wpotp-nav-link').removeClass('active');
			$(this).addClass('active');
			$('.js__wpotp-nav-content').removeClass('active').filter(target).addClass('active');
		});
		
		$('.js__wpotp-timepicker-new').on('click', function (e) {
			e.preventDefault();

			var target = $(this).data('target');
			var template = $(this).data('template');

			var source   = $(template).html();
			var template = Handlebars.compile(source);
			var count = $(target).children().length;

			var html = template({
				count: count
			});

			$(target).append(html);
			timepickerInit();
			deleteButtonInit();
		});

		deleteButtonInit();
		timepickerInit();
	}

	function deleteButtonInit()
	{
		$('.js__wpotp-timepicker-remove').off('click').on('click', function (e) {
			e.preventDefault();

			$(this).parent().remove();
		});
	}

	function timepickerInit()
	{
		$('.js__wpotp-timepicker').timepicker({
			'step': wpotp_interval,
			'useSelect': true,
			'timeFormat': 'H:i'
		});
	}

});