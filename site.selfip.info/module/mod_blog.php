<?php
if($path->Level(2)==NULL) {
	foreach(parent::ViewTableWhere('blog', 0, 0, 'blogid DESC', '0,10') as $blog): ?>
	<div style="border-bottom:#ececec 1px solid;padding-left:30px;">
	<table id="blog-recent" width="100%" border="0" cellpadding="5" cellspacing="0">
	   <tr>
		<td colspan="2">
		 <div id="blog-title"><h3><?php echo $blog['title']; ?></h3></div>
		</td>
	   </tr>
	   <tr>
		<td align="right" valign="top" style="width:250px;">	 
		 <div id="blog-image"><img src="<?php echo $this->domain; ?>module/blog/thumb/<?php echo $blog['image']; ?>" border="0" /></div><br />	 	 
		</td>
		<td valign="top" style="padding:15px 20px 10px 0; width:100%">
		 <div id="blog-pretext"><?php echo $this->SpaceTab().BB::Code($blog['pretext']); ?></div>
		 <div id="blog-readmore">
		 <a href="<?php echo $path->SetRequest(1, 'blog').$path->NextRequest(array(trim($blog['title']))); ?>"><?php echo _BLOG_READMORE; ?>
		 </a></div>
		</td>
	  </tr>
	</table>
	</div>
<?php 
endforeach;
} else {
	$blogEngine = new Blog();
	$blogEngine->ViewerBlog($path->Level(2));
	echo '<div class="body-blog">';
	$blog = parent::ViewTableWhere('blog', 'title', $path->Level(2), 0, 1);
	$blog = $blog[0];
	echo BB::Code($blog['fulltext']);
	echo '</div><div class="body-credit">';
	if($blog['created']==$blog['modified'])
	{
		echo '<u>'._BLOG_CREATED.'</u>&nbsp;'.DateThai($blog['created'],'f');
	} else {
		echo '<u>'._BLOG_MODIFIED.'</u>&nbsp;'.DateThai($blog['modified'],'f');
	}
	echo '</div><div id="blog-title"><h3>COMMENT</h3></div><table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td width="50%" valign="top">';
	echo '<div id="blog-comment">';
	if(parent::CountRow('blog_comment', 'blogid', $blogEngine->GetBlogID($path->Level(2)))==0) {
		echo _COMMENT_NONE;
	}
	foreach(parent::ViewTableWhere('blog_comment', 'blogid', $blogEngine->GetBlogID($path->Level(2)), 'commentid DESC', 20) as $comment):
		echo '<div class="comment-list"><div class="comment-name"><strong>'.$comment['name'].'</strong><font size="1"> on ';
		$totalcDays = LastDays($_SERVER['REQUEST_TIME']);
		$totalmDays = LastDays($comment['created']);
		if(($totalcDays-$totalmDays)==0) {
			echo 'Today';
		} else if(($totalcDays-$totalmDays)==1) {
			echo 'Yesterday';
		} else {
			echo 'Last '.($totalcDays-$totalmDays).' Days';
		}
		echo '</font></div><div class="comment-text">';
		echo BB::Code($comment['comment']);
		echo '</div></div>';		
	endforeach;
	echo '</div></td><td valign="top"><table width="95%" align="center" border="0" cellpadding="2" cellspacing="0"><tr><td align="right">';
	echo '<strong>Name: </strong></td><td align="left">';
	echo '<input id="name-comment" type="text" maxlength="20" size="20" value="" /></td></tr><tr><td align="right" valign="top">';
	echo '<strong>Comment: </strong></td><td align="left">';
	echo '<textarea id="text-comment" cols="60" rows="8"></textarea></td></tr><tr><td align="center" valign="top" colspan="2">';
	echo '<input id="submit-comment" type="submit" value="COMMENT"/></td></tr></table>';
	echo '</td></tr></table>';
}
?>




    

