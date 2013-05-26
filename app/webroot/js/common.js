$(function(){
	
	//Tooltip
	$('.tip').tooltip({placement:'bottom'});
	$('.user_menu').profiledrop();
	
});

//プロフィールのドロップダウンイベント
$.fn.profiledrop = function(config){
	//デフォルト設定
	var hover_flg = false;
	var self = this;
	var defaults = {
		speed : "fast"
	}
	
	//configと結合
	var options = $.extend(defaults, config);
	
	//プロフィールのクリックイベント
	$('.user_profile', this).click(function(){
		$(self).children('ul').toggle(options.speed);
	});
	
	//マウスオーバー状態フラグセット
	$(this).hover(
	function(){
		hover_flg = true;	
	},
	function(){
		hover_flg = false;
	});
	
	//html要素全体へのクリックイベント
	$('html').click(function(){
		if(!hover_flg){
			$(self).children('ul').hide(options.speed);
		}
	});
}