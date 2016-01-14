// JavaScript Document
var pathDomain = 'http://it-my.selfip.info/site.selfip.info/';
$(document).ready(function() {	
	$.ajaxSetup({ 
		type: 'GET', 
		dataType: 'json', 
		/*beforeSend: function(){
			$('#preload-list').show();
			$('#list-detail').hide();
			
		}, 
		complete:function(){
			$('#list-detail').show();
			$('#preload-list').hide();
		}*/
	});	
	
	$.ajax({ url: pathDomain+'widget/storelist.php',
		data: ({ list : 'manga', img : pathDomain }),
		error: function (data){
			$('#list-detail').html('Not Found, Retry.');
		},
		success: function (data){
			$('#list-detail').html(data.mini);
		},		
	});
	
 	$('#anime-get').click(function() {
		WidgetListButton('anime',this);	
		$.ajax({ url: pathDomain+'widget/storelist.php',
			data: ({ list : 'anime', img : pathDomain }),
			error: function (data){
				$('#list-detail').html('Not Found, Retry.');
			},
			success: function (data){
				$('#list-detail').html(data.mini);
			},		
		}); 
	});

	$('#manga-get').click(function() {
		WidgetListButton('manga',this);	
		$.ajax({ url: pathDomain+'widget/storelist.php',
			data: ({ list : 'manga', img : pathDomain }),
			error: function (data){
				$('#list-detail').html('Not Found, Retry.');
			},
			success: function (data){
				$('#list-detail').html(data.mini);
			},		
		}); 
	});

	$('#game-get').click(function() {
		WidgetListButton('game',this);	
		$.ajax({ url: pathDomain+'widget/storelist.php',
			data: ({ list : 'other', img : pathDomain }),
			error: function (data){
				$('#list-detail').html('Not Found, Retry.');
			},
			success: function (data){
				$('#list-detail').html(data.mini);
			},		
		}); 
	});
	
	function WidgetListButton(list,my)
	{
		$('#list-anime').removeClass('list-select');
		$('#list-manga').removeClass('list-select');
		$('#list-game').removeClass('list-select');			
		$('#list-'+list).attr('class','list-select');
			
		$('#anime-get').removeAttr('disabled','disabled').css('cursor','pointer');
		$('#manga-get').removeAttr('disabled','disabled').css('cursor','pointer');	
		$('#game-get').removeAttr('disabled','disabled').css('cursor','pointer');	
		
		$(my).attr('disabled','disabled').css('cursor','default');
		
		$('#anime-get').attr('src',pathDomain + 'images/btnanime_enable.png');
		$('#manga-get').attr('src',pathDomain + 'images/btnmanga_enable.png');
		$('#game-get').attr('src',pathDomain + 'images/btngame_enable.png');
		
		$(my).attr('src',pathDomain + 'images/btn' + list + '_disable.png');
	}
		
});


	