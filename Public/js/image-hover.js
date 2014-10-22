jQuery(document).ready(function(){
 
	 $('.title-wrap, .subtitle-wrap').each(function() {
        $(this).data('wrapping', $(this).width());
        $(this).css('width', 0);
      });

      $('div.images').bind('mouseenter', function() {
        $(this).find('.title-wrap').stop().each(function() {
          $(this).animate({
            width: $(this).data('wrapping')
          }, 400);
        });

        $(this).find('.subtitle-wrap').stop().each(function() {
          $(this).delay(200).animate({
            width: $(this).data('wrapping')
          }, 400);
        });
      });

      $('div.images').bind('mouseleave', function() {
        $(this).delay(200).find('.title-wrap').stop().each(function() {
          $(this).animate({
            width: 0
          }, 400);
        });

        $(this).find('.subtitle-wrap').stop().each(function() {
          $(this).animate({
            width: 0
          }, 400);
        });
      });
});