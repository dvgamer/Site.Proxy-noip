// JavaScript Document
var siteName = 'none';
var oldName = 'none';
var mangaName= 'none';

$(document).ready(function() {
		$.ajaxSetup({
			type: 'GET',
			dataType: 'json',
		});	
	
	$('#urlrequest').keyup(function() {
		var htmlLog = $('#process-log').html();
		$.ajax({ url: 'plugins/urlrequest.php',
			name: 'load_url',
			timeout: 1000,
			data: ({ url : jQuery.trim($(this).val()) }),
			beforeSend: function (){
				$('#urlvalid').show('fast');
			},
			complete:function (data){
				$('#urlvalid').hide('fast');
			},
			error: function (data){
				$('#process-txt').html('----------| Server is Down |----------');
				$('#process-log').html('----------| Error: Server Down |----------<br>'+htmlLog);
			},
			success: function (data){
				siteName = data.site;
			},			
		});
		if(siteName=='none') {
			$('#process-txt').html('Site Manga non-Support.');
			$('#process-log').html('Site Manga non-Support.<br>'+htmlLog);
			$('#chk').attr("disabled","disabled").css("cursor","default");					
			$('#'+oldName).css({
				'color': '#666666',
				'font-weight': 'normal'
			});
		} else {
			$('#process-txt').html('Manga Site: '+siteName+' , Next to Step.1');
			$('#process-log').html('<b>Manga Site: '+siteName+' , Next to Step.1</b><br>'+htmlLog);
			$('#chk').removeAttr("disabled").css("cursor","pointer");
			$('#'+oldName).css({
				'color': '#666666',
				'font-weight': 'normal'
			});
			oldName = siteName;
			$('#'+siteName).css({
				'color': '#BB0000',
				'font-weight': 'bold'
			});
		}
		
		
	});
	
	$('#chk').click(function() {
		var htmlLog = $('#process-log').html();
		$('#process-txt').html('Check URL Request, Please Wait...');
		$('#process-log').html('Check URL Request...<br>'+htmlLog);
		$('#urlrequest').attr("disabled","disabled");
		$('#chk').attr("disabled","disabled").css("cursor","default");	
		$.ajax({ url: 'plugins/'+siteName+'.php',
			data: ({ mode : 'chk', url : $('#urlrequest').val() }),
			error: function (data){
				$('#process-txt').html('----------| Error: Process Check Manga |----------');
				$('#process-log').html('----------| Error: Process Check Manga |----------<br>'+htmlLog);
			},
			success: function (data){
				if(data.chapter!=0) {
					mangaName = data.name
					$('#process-txt').html('Please Create Directory, Next to Step.2');
					$('#process-log').html('<b>Please Create Directory, Next to Step.2</b><br>'+htmlLog);
					if(data.part) {
						$('#folder').val('\\');
					} else {
						$('#folder').val('[Manga] '+data.name);
					}
					$('#created').removeAttr("disabled").css("cursor","pointer");
					$('#name').html(' '+data.name);
					$('#chapter').html(' '+data.chapter);
				} else {
					$('#process-txt').html('Manag Not Found.');
					$('#process-log').html('Manag Not Found.<br>'+htmlLog);
					$('#chk').show();
					$('#preload').hide();
					$('#check_manga').removeAttr("disabled").css("cursor","pointer");
					$('#urlrequest').removeAttr("disabled");
					$('#folder').val(data.name);
					$('#created').removeAttr("disabled").css("cursor","pointer");
					ajaxStatus('exists');
					ajaxStatus('folderchk');					
				}				
			},		
		});
	});
	
	ajaxStatus('exists'); ajaxStatus('read'); ajaxStatus('write'); ajaxStatus('folder'); ajaxStatus('gd2');
	$('#folder').keyup(function() {
		ajaxStatus('exists');
		ajaxStatus('read');
		ajaxStatus('write');
	});
	
	$('#dir_manga').keyup(function() {
		ajaxStatus('exists');
		if(!$('#folder').val()) {
			$('#created').attr("disabled","disabled").css("cursor","default");
		} else {
			$('#created').removeAttr("disabled").css("cursor","pointer");
		}
		chkReady();
	});
	
	$('#browse').click(function() {
		ajaxStatus('folder');
		ajaxStatus('read');
		ajaxStatus('write');
		ajaxStatus('exists');
		$('#created').attr("disabled","disabled").css("cursor","default");
		ajaxStatus('folder');
	});
	$('#reset').click(function() {
		window.document.location.reload(true);
	});
	
	
	
	$('#process-text').click(function() {
		$('#process-log').toggle(function() {
			$(this).animate({
				
			}, 500)
		});		
	});
	
	
	
});

function ajaxStatus(status) {
	var htmlLog = $('#process-log').html();
	$.ajax({
		url: 'plugins/status.php',
		data: ({ 
			status: status, 
			dir: $('#directory').val() + '\\' + $('#folder').val(),
			folder: $('#folder').val()
		}),
		error: function (data){
			$('#process-txt').html('----------| Error: Process Status '+status+' |----------');
			$('#process-log').html('----------| Error: Process Status '+status+' |----------<br>'+htmlLog);
		},
		success: function (data){
			$('#td'+status).html(data.pass);
			$('#td'+status).attr('chk',data.chk);
			chkReady();		
		},			
	});	
}

function chkReady() {
	var htmlLog = $('#process-log').html();
	if($('#tdexists').attr('chk')!=0 && $('#tdread').attr('chk')!=0 && $('#tdwrite').attr('chk')!=0 && $('#tdfolder').attr('chk')!=0 && $('#tdgd2').attr('chk')!=0) {
		if($('#chapter').html()==0) {
			$('#process-txt').html('Request Manga Site.');
			$('#process-log').html('Request Manga Site.<br>'+htmlLog);
		}
	}
}
	
	