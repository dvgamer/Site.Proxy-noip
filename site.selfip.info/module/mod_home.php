<?php
$blogRow = 2;
$blogLimit = 4;
$blogPage = parent::CountRow('blog', 0, 0);
$blogEngine = new Blog();
for($iBlog=0;$iBlog<$blogLimit;$iBlog+=$blogRow){
	if($iBlog<$blogPage):
	?>
	<div style="border-bottom:#ececec 1px solid;padding-left:30px; background-color:#FFFFFF">
	<table id="blog-recent" width="100%" border="0" cellpadding="5" cellspacing="0">
	   <?php if($iBlog!=($blogLimit-1) && $blogRow!=1): ?>
	   <tr>
		<?php foreach(parent::ViewTableWhere('blog', 0, 0, 'blogid DESC', $iBlog.','.$blogRow) as $blog): ?>
		<td valign="top">
		 <div id="blog-title" style="height:50px;"><h3><?php echo $blog['title']; ?></h3></div>
		 <div id="blog-detail"><div id="blog-image" style="background-image:url(<?php echo $this->domain; ?>module/blog/thumb/<?php echo $blog['image']; ?>)">
		 <div id="blog-date"><?php echo date('M,d y',$blog['created']); ?></div>
		 <img src="<?php echo $this->domain; ?>images/blog_img.png" width="240" height="100" border="0"/></div>
		 <div id="blog-detail-text"><?php echo _BLOG_POSTBY; ?><strong>Admin</strong> | 
         <strong><?php echo parent::CountRow('blog_comment', 'blogid', $blog['blogid']); ?></strong><?php echo _BLOG_COMMENT; ?></div>
		 </div>	 
		 <div id="blog-view"><?php echo _BLOG_VIEW.' '.parent::GetValue('view', 'blog', 'blogid',  $blog['blogid']); ?></div>
         <div id="blog-readmore">
         <a href="<?php echo $path->SetRequest(1, 'blog').$path->NextRequest(array(trim($blog['title']))); ?>">
         <img src="<?php echo $this->domain; ?>images/btnReadMore.jpg" width="80" height="22" border="0"/>
         </a></div>
		</td>
		<?php endforeach; ?>
	   </tr>
	   <?php else: ?>   
		<?php foreach(parent::ViewTableWhere('blog', 0, 0, 'blogid DESC', $iBlog.',1') as $blog): ?>
	   <tr>
		<td colspan="2">
		 <div id="blog-title"><h3><?php echo $blog['title']; ?></h3></div>
		</td>
	   </tr>
	   <tr>
		<td align="right" valign="top" style="width:250px;">	 
		 <div id="blog-detail"><div id="blog-image" style="background-image:url(<?php echo $this->domain; ?>module/blog/thumb/<?php echo $blog['image']; ?>)">
		 <div id="blog-date" align="left"><?php echo date('M,d y',$blog['created']); ?></div>
		 <img src="<?php echo $this->domain; ?>images/blog_img.png" width="240" height="100" border="0"/></div>
		 <div id="blog-detail-text"><?php echo _BLOG_POSTBY; ?><strong>Admin</strong> | 
         <strong><?php echo parent::CountRow('blog_comment', 'blogid', $blog['blogid']); ?></strong><?php echo _BLOG_COMMENT; ?></div>
		 </div>	 	 
		</td>
		<td valign="top" style="padding:15px 20px 10px 0; width:100%">
		 <div id="blog-pretext"><?php echo BB::Code($blog['pretext']); ?></div>
         <div id="blog-readmore" style="margin-top:15px">
         <a href="<?php echo $path->SetRequest(1, 'blog').$path->NextRequest(array(trim($blog['title']))); ?>">
         <img src="<?php echo $this->domain; ?>images/btnReadMore.jpg" width="80" height="22" border="0"/>
         </a></div>
		</td>
      </tr>
		<?php endforeach; ?>
	   <?php endif; ?>	
	</table>
	</div>
	<?php 
	endif;
}
?>