// JavaScript Document
Array.prototype.count = function () {
	return this.length;
}
$(document).ready(function() {
	var iTotalLoop = 0;
	var percent = 0;
	var cTotal = 0;
	var cLoop = 0;
	var iLoop = 0;
	var success = 0;
	var skip = 0;
	var warning = 0;
	var part = 0;
	var chapter = new Array();
	
	$('#created').click(function() {
		$('#process-txt').html('Create Folder Manga, Please Wait...');
		$('#process-log').append('Create Folder Manga...<br>');
//----> Getlist Manga
		$('#created').attr("disabled","disabled");
		$('#folder').attr("disabled","disabled");
		$.ajax({ url: 'plugins/'+siteName+'.php',
			data: ({ mode : 'list', 
				url : $('#urlrequest').val()
			}),
			error: function (data){
				$('#process-txt').html('----------| Error: Get list Manga |----------');
				$('#process-log').append('----------| <b>Error:</b> Get list Manga |----------<br>');
			},
			success: function (data){	
				iTotalLoop = data.count();
				for(var i=1;i<iTotalLoop;i=i+2) {
					chapter[i-1] = data[i-1];
					chapter[i] = data[i];
					//$('#debug').append('<b>Chapter: </b>' + chapter[i-1] + '<b> Folder:</b>' + chapter[i] + '<br>');
					
//----------------> Get Chapter And Created Folder Chapter
					//$('#debug').append($('#directory').val() + "\\" + $('#folder').val() + "\\" + data[i]);
					$.ajax({ url: 'plugins/'+siteName+'.php',
						data: ({ mode : 'create',
							dir: $('#directory').val() + $('#folder').val(),
							chapter: data[i]
					  	}),
						error: function (){
							$('#process-txt').html('----------| Error: Get Chapter And Created Folder Chapter |----------');
							$('#process-log').append('----------| <b>Error:</b> Get Chapter And Created Folder Chapter |----------<br>');
						},
						success: function (data){
							$('#process-txt').html('Created Directory: '+data.chapter);
							$('#process-log').append('<b>Created in Directory:</b> '+data.dir+'\\'+data.chapter+'<br>');
						}
					});					
				}
				$('#write').removeAttr("disabled").css("cursor","pointer");
			}						
		});

	});
	
	
	$('#write').click(function() {
		$('#write').attr("disabled","disabled").css("cursor","default");
		$('#process-txt').html('Get List Image, Please Wait...');						
		// Alert Debug
		//$('#debug').append('<br><b>LoopTotal: </b>'+iTotalLoop+'<br>');
		for(var i=1;i<iTotalLoop;i=i+2) {			
			//$('#debug').append('<b>URL: </b>'+chapter[i-1]+'<br>');	
								
//--------> Get Chapter
			$.ajax({ url: 'plugins/'+siteName+'.php',
				data: ({ mode : 'image',
					url : jQuery.trim($('#urlrequest').val()),
					mangaurl: chapter[i-1], 
					manganame: chapter[i]
				}),
				error: function (data){
					$('#process-txt').html('----------| Error: Generator Chapter |----------');
					$('#process-log').append('----------| <b>Error:</b> Generator Chapter |----------<br>');
				},
				success: function (data){
					// data[0] = Folder Manga Chapter
					// data[1] = Total Image Manga Chapter
					// data[c] = Image Name
					$('#process-txt').html('Request URL To Server, Please Wait...');						
					$('#process-log').append('<b>Chapter:</b> '+data[0]+' <em>(Total '+(data[1]-1)+' pages.)</em><br>');						
					//$('#debug').append(' <b>Sample: </b>'+data[3]+'<br>');
					cLoop = 1;
					for(var c=2;c<data[1]+1;c++) {
						//$('#debug').append('<b>Directory: </b>'+$('#directory').val() + '\\' + $('#folder').val() + '\\' + data[0] + '<br>');
						//$('#debug').append('<b>Image: </b>'+data[c]+'<br>');
						//$('#debug').append('<b>cLoop: </b>'+cLoop+'<br>');	
						
//--------------------> Save Image
						$('#process-text').html("Downloading...");
						$.ajax({ url: 'plugins/image.php',
							data: ({ mode : 'write',
								url : data[c],
								dir: $('#directory').val() + "\\" + $('#folder').val() + "\\" + data[0],
								id: cLoop
							}),
							type: 'GET',
							error: function (data){
								$('#process-txt').html('----------| Error: Save Image '+data+' |----------');
								$('#process-log').append('----------| <b>Error:</b> Save Image '+data+' |----------<br>');
								warning++;
							},
							success: function (data){
								//$('#debug').append('<b>ID: </b>'+data.id+'<br>');
								//$('#debug').append('<b>Path: </b>'+data.path+'<br>');
								//$('#debug').append('<b>URL: </b>'+data.url+'<br>');		
								$('#process-bar').css("width",(percent*(warning+success+skip))+"%");

								if(data.created==0) {
									$('#process-log').append('<b>Chapter: </b>'+data.name+' <b>Image: </b>'+data.file+' <font color="#990000"><b>Failed</b></font><br>');
									warning++;
								} else if(data.created==2)  {
									$('#process-log').append('<b>Chapter: </b>'+data.name+' <b>Image: </b>'+data.file+' <font color="#000099"><b>Skip</b></font><br>');
									skip++;
								} else {
									$('#process-log').append('<b>Chapter: </b>'+data.name+' <b>Image: </b>'+data.file+' <font color="#009900"><b>Success</b></font><br>');
									success++;
								}
								if((warning+success+skip)==(cTotal)) {
									$('#process-text').html('Success: '+success+' Skip: '+skip+' Failed: '+warning+' (Total: '+(cTotal)+' pages)');							
									$('#process-txt').html('Download Complated, Thank you.');
									$('#reset').removeAttr("disabled").css("cursor","pointer");
								} else if((warning+success+skip)<(cTotal)) {
									$('#process-txt').html('Success: '+success+' Skip: '+skip+' Failed: '+warning+' (Total: '+(cTotal)+' pages)');	
								}
							}						
						});//end Save Image		*/	
						cLoop++;
						cTotal++;						
					}//end for c
					percent = 100 / (cTotal-1);
				}		
			});
		}//end for i
	});
});