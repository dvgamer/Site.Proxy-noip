<?php
$HaKko = new Engine();
$reqPath = new RequestPath();
$dirTranslator = 'translator/';
$listManga = new DirectoryReader($HaKko->Document.$dirTranslator);
?>
<center>
<div id="hakko-shadow"><div id="hakko-main">
<div id="hakko-logo"><a href="<?php echo $urlDomain; ?>">
<img src="<?php echo $HaKko->Domain; ?>images/HaKkoLogo.png" border="0" width="370" height="79" align="left" /></a>
<div id="nav-hakko">
<?php echo $HaKko->Navigator(); ?>
</div>
</div>
<div id="hakko-body">
<?php if($reqPath->Level(1)=='board'): ?>
<?php echo $HaKko->BoxFull('<div style="height:800px;">&nbsp;</div>',0); ?>

<?php elseif($reqPath->Level(1)=='info'): ?>
<?php 
echo $HaKko->BoxFull('&nbsp;',0);
?>
<?php else: ?>
<?php if(!$reqPath->TotalLevel()): ?>
<script language="javascript">
$(document).ready(function() {
	$('#s3slider').disableTextSelect();
	$("#s3slider").noContext();
	$('#s3slider').s3Slider({ 
		timeOut: 8000 
	});
	$('#update-manga').fadeIn('slow');
});
</script>
<?php

// Manga Translator
$maxList = 20;
$HaKko->MangaTranslator($listManga);
$translatorList = '<h3>Manga Translator</h3>';
foreach($HaKko->MangaStore as $list) {
	$translatorList .= $HaKko->ThumbList($list['thumb'], $list['name'], $list['status'],$list['last_id'], $list['created']);
	foreach($list['chapter'] as $ch) {
		$tmplist['created'] = $ch['created'];
		$tmplist['name'] = $list['name'];
		$tmplist['chapter'] = $ch['name'];
		$tmplist['thumb'] = $list['thumb'];
		$listChapter[] = $tmplist;
	}
}

// Update Manga
$preview = NULL;
rsort($listChapter);
foreach($listChapter as $i=>$lastList) {
	if($i<$maxList) {
		$colorRow = NULL;
		if ($i%2==1) { $colorRow = ' bgcolor="#f9f9f9"'; }
		$indexChapter = explode('-', $lastList['chapter']);
		$lastDay = (int)(($_SERVER['REQUEST_TIME']-$lastList['created'])/86400);
		if($lastDay==0) {
			$lastDay = 'Today';
		} elseif($lastDay==1) {		
			$lastDay = 'Yesterday';		
		} else {
			$lastDay = 'Last '.$lastDay.' Day';		
		}
		$textCh = 'Chapter '.$lastList['chapter'];
		if((float)$lastList['chapter']<1 && (float)$lastList['chapter']!=0) {
			$textCh = 'Special Chapter '.($lastList['chapter']*10);
		}
		$linkChapter = $reqPath->SetRequest(1,$lastList['name']).$reqPath->NextRequest('Chapter-'.$indexChapter[0]);
		$preview .= '<a href="'.$linkChapter.'"><table id="last-list" width="100%" cellpadding="2" cellspacing="0" '.$colorRow.'><tr><td width="40">';
		$preview .= '<div style="border:#aaaaaa solid 1px;margin:3px;"><img src="'.$GLOBALS['Domain'].$lastList['thumb'].'" border="0" width="40" height="40" /></div></td><td align="left">';
		$preview .= '<strong>'.$lastList['name'].'</strong> ';
		$preview .= '<font id="last-day" color="#999999"><em>('.$lastDay.')</em></font><div id="last-chapter">';
		$preview .= '<a href="'.$linkChapter.'">'.$textCh.'</a></div></td></tr></table></a>';
	} else {
		break;
	}
	
}

// Slider Show
$gallery = new DirectoryReader($HaKko->Document.'gallery/');
$slideImage = '<div id="slide-space"><div id="slide-img"><div id="s3slider"><ul id="s3sliderContent">';
$listGallery = $gallery->toArray();
shuffle($listGallery);
foreach($listGallery as $img) {
	if(is_file($HaKko->Document.'gallery/'.$img)) {
		$info = pathinfo($img);
		$text = explode('-', $info['filename']);
		$name = ereg_replace('\([0-9])','',$text[1]);
		$slideImage .= '<li class="s3sliderImage"><img src="'.$HaKko->Domain.'gallery/'.$img.'" />';
		$slideImage .= '<span style="color:#999999;"><h1>'.$name.'</h1>&nbsp;&nbsp;&nbsp;'.$text[0].' ('.date('M,d y',filemtime($HaKko->Document.'gallery/'.$img)).')</span></li>';
	}
}
$slideImage .= '<div class="clear s3sliderImage"></div></ul></div></div></div>';

$fileTarget = $HaKko->Document.'article/abuntme.txt';
if(file_exists($fileTarget)) { 
	$aboutMe = file_get_contents($fileTarget);
}
// Home Page
?>
<table width="10%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top"><?php
    echo $HaKko->BoxMod('<h3>Login</h3>'.$session->Cookie('password','dvgamer',10).'<br/><br/><br/><br/>',0);
	echo $HaKko->BoxMod($translatorList,'file'); 
	if(file_exists($fileTarget)) { 
    echo $HaKko->BoxMod('<h3>About Me</h3><span style="margin-left:20px;">'.BB::Code($aboutMe).'</span>',0);
	}
	?></td>
    <td valign="top"><?php 
	echo $HaKko->BoxBody($slideImage,0);
	echo $HaKko->BoxBody('<h3>Update Manga</h3><div id="update-manga">'.$preview.'</div>', 'tag'); 
	?></td>
  </tr>
</table>
<?php else: ?>
<?php if(!$reqPath->Level(2)): ?>
<script language="javascript">
$(document).ready(function() {
	$('#comment-submit').click(function() {
		if($('#comment-text').val().trim()!='')
		{
			$(this).attr('disabled','disabled');
			$('#comment-box').fadeOut('slow');
			$.ajax({ url: '<?php echo $reqPath->SetRequest(1, 'site.hakko.selfip'); ?>include/lib/engine.comment.php',
				type: 'POST',
				data: ({ userid : <?php echo 1; ?>, manga: '<?php echo $reqPath->Level(1); ?>', comment: $('#comment-text').val().trim() }),
				error: function (data){
					$('#comment-list').html('Error');
				},
				success: function(data) {								
					var newList = '<div id="manga-comment">';
					newList += '<div id="list-comment"><span id="date-comment"><?php echo _MANGA_TODAY; ?></span>';
					newList += '<u><?php echo 'Test'; ?></u><span id="date-comment"><?php echo _MANGA_SAY; ?></span></div>';
					newList += '<div id="name-comment">' + $('#comment-text').val() + '</div></div>';		
					$('#comment-list').html(newList+$('#comment-list').html());
				},
			});
		}
	});
	
});
</script>
<?php
$HaKko->MangaTranslator($listManga);
$foundMangaStore = false;
$listChapter = array();
$content = array();

// Manga Detail - List Chapter
foreach($HaKko->MangaStore as $manga) {
	if($reqPath->Level(1)==$manga['name']) {
		$content['name'] = $manga['name'];
		$content['status'] = $manga['status'];
		$content['cover'] = $manga['cover'];
		$content['created'] = $manga['created'];
		$content['update'] = $manga['created'];
		$foundMangaStore = true;
		foreach($manga['chapter'] as $list) {
			if($content['update']<$list['created']) {
				$content['update'] = $list['created'];
			}
			$tmpChapter = explode('-',$list['name']);
			if(strlen((int)$tmpChapter[0])==2) {
				$tmpChapter[0] = '0'.$tmpChapter[0];
			} else if(strlen((int)$tmpChapter[0])==1) {
				$tmpChapter[0] = '00'.$tmpChapter[0];
			}
			$tmpChapter['id'] = $tmpChapter[0];
			$tmpChapter['name'] = trim($tmpChapter[1]);
			$listChapter[] = $tmpChapter;
		}
	}
}
if($foundMangaStore) {
	$listString = NULL;
	if(count($listChapter)!=0) {
		rsort($listChapter);
		foreach($listChapter as $list) {			
			$textChapter = 'Chapter '.(float)$list['id'];
			if((float)$list['id']<1 && (float)$list['id']!=0) {
				$textChapter = 'Special Chapter '.($list['id']*10);
			}
			$listString .= '<a href="'.$reqPath->SetRequest(2,'Chapter-'.(float)$list['id']).'"><div id="manga-chapter">';
			$listString .= '<div id="list-chapter">'.$textChapter.'</div>';
			$listString .= '<div id="name-chapter">'.trim($list['name']).'</div></div></a>';
		}
	} else {
		$listString = '<span id="details">'._MANGA_NONE.'</span>';
	}

	$fileTarget = $HaKko->Document.$dirTranslator.'[Content] Database/'.$reqPath->Level(1).'.txt';
	$content['author'] = _MANGA_NONE;
	$content['genre'] = _MANGA_NONE;
	$content['summary'] = _MANGA_NONE;
	$content['translator'] = _MANGA_NONE;

	if(file_exists($fileTarget)) { 
		$content['raw'] = file_get_contents($fileTarget);
		$tmpData = explode('|',iconv('tis-620','utf-8',$content['raw']));
		$content['author'] = $tmpData[0];
		$content['translator'] = $tmpData[1];
		$content['genre'] = $tmpData[2];
		$content['summary'] = BB::Code($tmpData[3]);
	}

// Manga Details
	$detailManga = '<table cellpadding="3" cellspacing="0"><tr>';
	$detailManga .= '<td valign="top" width="360" rowspan="2">'.BB::Code('[img='._MANGA_DATE.BB::Date($content['created'],'m').']'.'../../'.$content['cover'].'[/img]').'</td>';
	$detailManga .= '<td valign="top" width="100%" class="textbox"><br/><h3>'.$reqPath->Level(1).'</h3>';
	$detailManga .= '<iframe src="http://www.facebook.com/plugins/like.php?href='.rawurlencode($urlDomain.$reqPath->NextRequest($reqPath->Level(1))).'&amp;layout=button_count&amp;show_faces=false&amp;width=320&amp;action=recommend&amp;colorscheme=light&amp;" ';
	$detailManga .= 'frameborder="0" class="manga_like"  allowTransparency="true"></iframe>';
	$detailManga .= '<h4>'._MANGA_AUTHOR.'</h4><span id="details">'.$content['author'].'</span>';
	$detailManga .= '<h4>'._MANGA_GENRE.'</h4><span id="details">'.$content['genre'].'</span>';
	$detailManga .= '<h4>'._MANGA_SUMMARY.'</h4><span id="details">'.$content['summary'].'</span>';
	$detailManga .= '<h4>'._MANGA_TRANSLATOR.'</h4><span id="details">'.$content['translator'].'</span>';
	$detailManga .= '<h4>'._MANGA_UPDATE.'</h4><span id="details">'.BB::Date($content['update'],'m').'</span>';
	//$detailManga .= '<h4>Status</h4><span id="details">View: </span>';
	$detailManga .= '</td></tr><tr>';
	$detailManga .= '<td valign="bottom">';
	if(isset($boxComment)) {
		$detailManga .= '<div id="comment-box"><h4>Comment</h4>';
		$detailManga .= '<textarea name="comment" id="comment-text" rows="5"></textarea><br />';
		$detailManga .= '<div align="right"><input type="button" value=" " id="comment-submit" /></div></div>';
	}
	$detailManga .= '</td>';
	$detailManga .= '</tr></table>';

// Comment Manga
	$commentManga = '<h3>Comment</h3><div id="comment-list">';
	foreach($HaKko->ViewTableWhere('comment', 'manga', $reqPath->Level(1), 'commentid DESC', 20) as $list) {
		$commentManga .= '<div id="manga-comment">';
		$commentManga .= '<div id="list-comment"><span id="date-comment">'.BB::Date($list['created'],'d').'</span>';
		$commentManga .= '<u>'.$HaKko->GetValue('name', 'user', 'userid', $list['userid']).'</u><span id="date-comment">'._MANGA_SAY.'</span></div>';
		$commentManga .= '<div id="name-comment">'.$list['comment'].'</div></div>';
	}
	$commentManga .= '</div>';
?>
<script type="application/javascript">
$(document).ready(function(){
	$('.img-blog').disableTextSelect();	
	$('.img-blog').noContext();	
});
</script>

<table width="10%" border="0" cellpadding="0" cellspacing="0">
  <tr>
	<td valign="top"><?php 
	echo $HaKko->BoxBody($detailManga, 'info'); 
	echo $HaKko->BoxBody($commentManga, 0); 
	?></td>
	<td valign="top"><?php
	echo $HaKko->BoxMod($listString,0);
	?></td>
  </tr>
</table><?php 
// Manga Not Found.
} else {	
	$errorPage = '<h5>'.BB::Code(_ERROR_LINE1).'</h5>';
	$errorPage .= '<font size="+1"><br/><strong>'.BB::Code(_ERROR_LINE2).'</strong>';
	$errorPage .= '<br/><br/><br/><br/><strong>'.BB::Code(_ERROR_LINE3).'</strong></font>';
	$errorPage .= '<ul><strong>'._ERROR_LINE4.'</strong><a href="'.$reqPath->SetRequest(0,'').'">'._ERROR_LINE5.'</a><br/><br/><br/>';
	$errorPage .= '</ul><hr/><div align="right"><span id="last-chapter"><u>'._HEAD_TITLE.'</u> <strong>Engine</strong>.</span></div><br/>';
	echo $HaKko->BoxFull($errorPage,0);
} 
?>
<?php else:
	$HaKko->MangaTranslator($listManga);
	$foundChapter = false;
	$foundManga = false;
	$listPage = array();
	$chapterName = NULL;
	// Chk Manga - Chapter
	foreach($HaKko->MangaStore as $manga) {
		if($reqPath->Level(1)==$manga['name']) {	
			$foundManga = true;
			foreach($manga['chapter'] as $list) {
				$chapterName = $list['name'];
				$tmpurl = explode('-',$reqPath->Level(2));
				$tmpdir = explode('-',$list['name']);
				if(trim($tmpurl[1])==trim($tmpdir[0])) {
					$foundChapter = true;
					break;
				}
			}
		}
	}	
	
// Read Manga Chapter Page
	if($foundManga) {
		$listImage = new DirectoryReader($listManga->location.iconv('utf-8','tis-620','[Manga] ').$reqPath->Level(1).'/'.iconv('utf-8','tis-620',$chapterName));
		$totalImage = count($listImage->toArray())-1;	
		if($foundChapter && $totalImage!=1) { ?>
		<script language="javascript">
			$(document).ready(function() {
				var imageId = 2;
				var imageTotal = 0;
				var nextChapter = false;	
				var backChapter = false;	
				var nextPage = true;	
				
				// Disable context menu on an element
				$("#block-selected,#back-page,#next-page").noContext();
				$('#back-page,#next-page').disableTextSelect();	
				
				$.ajax({ url: '<?php echo $reqPath->SetRequest(1, 'site.hakko.selfip'); ?>include/lib/engine.list.php',
					type: 'POST',
					data: ({ manga : '<?php echo $reqPath->Level(1); ?>', chapter: '<?php echo $chapterName; ?>' }),
					error: function (data){
						$('#image-manga').html('Not Found.');
					},
					success: function (data){
						imageTotal = data.total;
						nextChapter = data.anext;
						backChapter = data.aback;
						$.ajax({ url: '<?php echo $reqPath->SetRequest(1, 'site.hakko.selfip'); ?>include/lib/engine.image.php',
							type: 'POST',
							data: ({ manga : '<?php echo $reqPath->Level(1); ?>', chapter: '<?php echo $chapterName; ?>', id: imageId, total: imageTotal }),
							error: function (data){
								$('#image-manga').html('Not Found.');
							},
							beforeSend: function() {
								$("#preload").html('<h4>Load Chapter Manga, Please Wait...</h4>');
								$("#preload").animate({"top": "+=40px"}, 500 );								
							},
							success: function(data) {								
								$("#preload").animate({"top": "-=40px"}, 500 );					
								PriviewImage(data);
							},
						});
					},
				});
				
				
				// Select Page Change
				$('#goto-image').change(function() {
					var gotoImage = $('#goto-image').val();
					imageId = gotoImage;

					$.ajax({ url: '<?php echo $reqPath->SetRequest(1, 'site.hakko.selfip'); ?>include/lib/engine.image.php',
						type: 'POST',
						data: ({ manga : '<?php echo $reqPath->Level(1); ?>', chapter: '<?php echo $chapterName; ?>', id: gotoImage, total: imageTotal }),
						error: function (data){
							$('#image-manga').html('Not Found.');
						},
						beforeSend: function() {
							$("#preload").html('<h4>Go to '+(imageId-1)+' page, Please Wait...</h4>');
							$("#preload").animate({"top": "+=40px"}, 500 );
						},
						success: function(data) {								
							$("#preload").animate({"top": "-=40px"}, 500 );					
							nextPage = data.image;
							PriviewImage(data);
						},
					});					
				});
				

				$("#block-selected").rightClick(function() {
					// Todo
				});				
				
				// Left Click Image
				$('#image-manga').click(function() {
					StepNextPage();
				});
												
				// Right Click Image
				$('#image-manga').rightClick(function(){
					StepBackPage();
				});
				
				// Next Buttom
				$('#next-page').click(function(){
					StepNextPage();
				});
				// Back Buttom
				$('#back-page').click(function(){
					StepBackPage();
				});
				
				$(document).keydown(function(e){
					if(e.keyCode == 37) { 
						StepBackPage();
						return false;
					} 
					if(e.keyCode == 39) {
						StepNextPage();
						return false;
					}					
				});
				
				// Next Page Function
				function StepNextPage() {
					if(nextPage) {
						imageId++;
						$('#goto-image').val(imageId);
					}
					$.ajax({ url: '<?php echo $reqPath->SetRequest(1, 'site.hakko.selfip'); ?>include/lib/engine.image.php',
						type: 'POST',
						data: ({ manga : '<?php echo $reqPath->Level(1); ?>', chapter: '<?php echo $chapterName; ?>', id: imageId, total: imageTotal }),
						error: function (data){
							$('#image-manga').html('Not Found.');
						},
						beforeSend: function() {
							$("#preload").html('<h4>Are the next page, Please Wait...</h4>');
							$("#preload").animate({"top": "+=40px"}, 500 );
						},
						success: function(data) {								
							$("#preload").animate({"top": "-=40px"}, 500 );					
							nextPage = data.image;
							if(data.image!='next' && data.image!='back') {								
								PriviewImage(data);
							} else {
								if(nextChapter!=0) {
									document.location = "<?php echo $reqPath->SetRequest(1, $reqPath->Level(1)); ?>Chapter-"+nextChapter+"/";
								} else {
									document.location = "<?php echo $reqPath->SetRequest(1, $reqPath->Level(1)); ?>";
								}
							}
						},
					});
				}
				
				// Back Page Function
				function StepBackPage() {
					if(nextPage) {
						imageId--;
						$('#goto-image').val(imageId);
					}
					$.ajax({ url: '<?php echo $reqPath->SetRequest(1, 'site.hakko.selfip'); ?>include/lib/engine.image.php',
						type: 'POST',
						data: ({ manga : '<?php echo $reqPath->Level(1); ?>', chapter: '<?php echo $chapterName; ?>', id: imageId, total: imageTotal }),
						error: function (data){
							$('#image-manga').html('Not Found.');
						},
						beforeSend: function() {
							$("#preload").html('<h4>Are the back page, Please Wait...</h4>');
							$("#preload").animate({"top": "+=40px"}, 500 );
						},
						success: function(data) {								
							$("#preload").animate({"top": "-=40px"}, 500 );					
							nextPage = data.image;
							if(data.image!='next' && data.image!='back') {								
								PriviewImage(data);
							} else {
								if(backChapter!=0) {
									document.location = "<?php echo $reqPath->SetRequest(1, $reqPath->Level(1)); ?>Chapter-"+backChapter+"/";
								} else {
									document.location = "<?php echo $reqPath->SetRequest(1, $reqPath->Level(1)); ?>";
								}
							}
						},
					});
				}

				function PriviewImage(data)
				{
					$('#image-manga').css({
						'background-image': 'url(<?php echo $reqPath->SetRequest(1, 'site.hakko.selfip'); ?>'+data.image+')',
						'width': data.width+'px',
						'height': data.height+'px',
						'position': 'absolute',
						'margin': '-9px 0 0 -13px',
					});	
					$('#image-space').css({
						'width': (data.width-22)+'px',
						'height': (data.height-18)+'px',
					});	
					$('#box-manga').css({
						width: (data.width+50)+'px',
						height: (data.height+50)+'px',
					});
				}
			});
            </script>            
            <?php 
			
			$textChapter = ereg_replace('-', ' ', $reqPath->Level(2));
			$listId = ereg_replace('[^0-9\.]','',$reqPath->Level(2));
			if((float)$listId<1 && (float)$listId!=0) {
				$textChapter = 'Special Chapter '.($listId*10);
			}
			$headManga = '<div id="back-page">&nbsp;</div><div id="next-page">&nbsp;</div>';
			$headManga .= '<div id="preload"><h4>&nbsp;</h4></div>';
			
			$headManga .= '<div style=" padding:20px 0 10px 0; font-weight:bold; font-size:18px;">'.$textChapter.'</div>';
			
			$headManga .= '<strong>Page <select name="goto-image" id="goto-image">';
			for($index=1;$index<$totalImage;$index++) {
				$headManga .= '<option value="'.($index+1).'">'.$index.'</option>';
			}
			$headManga .= '</select> (of '.($totalImage-1).' pages)</strong><br/><br/>';

			echo '<a href="'.$reqPath->SetRequest(1,$reqPath->Level(1)).'"><center><h2>'.$reqPath->Level(1).'</h2></center></a>'.$headManga;
			echo '<span style="font-size:11px;">(<strong>NextPage:</strong> Left-click on image. OR Press right Arrow-Key. / ';
			echo '<strong>BackPage:</strong> Right-click on the image. OR Press left Arrow-Key.)</span><br/><br/>';
			echo '<div id="block-selected">'.$HaKko->BoxManga('&nbsp;').'</div>';			
		} else {
			$errorPage = '<h5>'.BB::Code(_ERROR_LINE1).'</h5>';
			$errorPage .= '<font size="+1"><br/><strong>'.BB::Code(_ERROR_LINE2).'</strong>';
			$errorPage .= '<br/><br/><br/><br/><strong>'.BB::Code(_ERROR_LINE3).'</strong></font>';
			$errorPage .= '<ul><strong>'._ERROR_LINE4.'</strong><a href="'.$reqPath->SetRequest(1,$reqPath->Level(1)).'">'._ERROR_LINE5.'</a><br/><br/><br/>';
			$errorPage .= '</ul><hr/><div align="right"><span id="last-chapter"><u>'._HEAD_TITLE.'</u> <strong>Engine</strong>.</span></div><br/>';
			echo $HaKko->BoxFull($errorPage,0);
		}
	} else {
		$errorPage = '<h5>'.BB::Code(_ERROR_LINE1).'</h5>';
		$errorPage .= '<font size="+1"><br/><strong>'.BB::Code(_ERROR_LINE2).'</strong>';
		$errorPage .= '<br/><br/><br/><br/><strong>'.BB::Code(_ERROR_LINE3).'</strong></font>';
		$errorPage .= '<ul><strong>'._ERROR_LINE4.'</strong><a href="'.$reqPath->SetRequest(0,'').'">'._ERROR_LINE5.'</a><br/><br/><br/>';
		$errorPage .= '</ul><hr/><div align="right"><span id="last-chapter"><u>'._HEAD_TITLE.'</u> <strong>Engine</strong>.</span></div><br/>';
		echo $HaKko->BoxFull($errorPage,0);
	}
?>
<?php endif;endif;endif; ?>
</div>
<div id="hakko-credit"><div id="hakko-credit-img">&nbsp;</div></div>
</div></div></center>