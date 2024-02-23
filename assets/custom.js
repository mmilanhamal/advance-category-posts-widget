jQuery(document).ready(function($){  
	$('body').on('change','.option',function(){
		if( $(this).is(':checked') && $(this).val() == 'slide' )
		{
			$('.time-options').fadeOut();
			$('.slider-options').fadeIn();
		}
		if( $(this).is(':checked') && $(this).val() == 'time' )
		{
			$('.time-options').fadeIn();
			$('.slider-options').fadeOut();
		}

		if( $(this).is(':checked') && $(this).val() == 'list' )
		{
			$('.time-options').fadeOut();
			$('.slider-options').fadeOut();
		}
	});
});