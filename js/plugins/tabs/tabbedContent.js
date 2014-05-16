//tab effects
var TabbedContent = {
	init: function() {	
		$(".Worktab_item").mouseover(function() {
			var background = $(this).parent().find(".Workmoving_bg");
			$(background).stop().animate({
				left: $(this).position()['left']
			}, {
				duration: 300
			});
			TabbedContent.slideContent($(this));
		});
	},
	slideContent: function(obj) {
		var margin = $(obj).parent().parent().find(".Workslide_content").width();
		margin = margin * ($(obj).prevAll().size() - 1);
		margin = margin * -1;
		$(obj).parent().parent().find(".Worktabslider").stop().animate({
			marginLeft: margin + "px"
		}, {
			duration: 300
		});
	}
}
$(document).ready(function() {
	TabbedContent.init();
});