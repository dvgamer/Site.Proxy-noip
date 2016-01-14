<center>
<div id="itmyself">
 <div id="itmy-nav">
 <?php
 $site = new Site('selfip',$config,$GLOBALS['Domain']); 
 $path = new RequestPath();
 ?>
 <table align="right" id="nav-menu" width="200" border="0" cellpadding="2" cellspacing="0">
    <tr id="nav-tr" align="center" valign="bottom">
      <?php  
	  foreach($site->ViewTableWhere('menu', 'panelid', 1, 'menuid DESC', 0) as $nav) :
	  	if($path->Level(1)==$nav['path']) {
		  $image = $GLOBALS['Domain'].'images/btn'.$nav['name'].'_over.jpg';
		} else {
		  $image = $GLOBALS['Domain'].'images/btn'.$nav['name'].'_up.jpg';
		}
		list($width, $height) = getimagesize($image);
	  ?>
      <td><a href="<?php echo $path->SetRequest(1,$nav['path']); ?>" title="<?php echo $nav['detail']; ?>">
      <img src="<?php echo $image; ?>" border="0" width="<?php echo $width; ?>" height="<?php echo $height; ?>" /></a></td>
      <?php endforeach; ?>
      <td valign="bottom">
      <form name="serach" id="serach" method="post" action="#">
       <input name="find" type="text" id="find" maxlength="20" value="Find" />
      </form>
      </td>
    </tr>
    <tr align="center" valign="top">
	  <?php
	    foreach($site->ViewTableWhere('menu', 'panelid', 1, 'menuid DESC', 0) as $nav) :
          if($path->Level(1)==$nav['path']) {
			echo'<td><img src="'.$GLOBALS['Domain'].'images/nav_arrow.jpg" border="0" width="13" height="6" /></td>';
          } else {
			echo '<td>&nbsp;</td>';
		  }
		endforeach;
	  ?>
      <td>&nbsp;</td>
    </tr>
  </table>
 </div>
 <div id="itmy-head">
  <?php
  $site->Module('header');
  $widgetTotal = $site->CountModule('right');
  
  ?>
 </div>
 <div id="itmy-body">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td id="itmy-body-left">

      <?php if($site->chkModule($GLOBALS['Document'],$path->Level(1))): ?>
        <div id="nav-tab" <?php if($widgetTotal==0) { echo 'style="border-right:#ececec 1px solid;"'; } ?>><h2>
        <?php
		$navPath = $path->GetRequest();
		for($i=0;$i<count($navPath);$i++)
		{
			$navText = $site->GetValue('tag', 'module', 'name', $navPath[$i]);
			if($i!=count($navPath)-1) {
				echo '<a href="'.$path->SetRequest($i+1,$path->Level($i+1)).'">';
				echo $navText.'</a>';
			} else {
				echo $navText;
			}			
			if((count($navPath)-1)!=$i) {			
				echo '<img src="'.$GLOBALS['Domain'].'images/nav_tab.jpg" border="0" width="16" height="16" vspace="0" hspace="5" />';
			}
		}
        ?>
        </h2></div>
		<?php 
		endif;
		$site->BodyModule($path->Level(1));
		?>
        </td>
      <td id="itmy-body-right" <?php if($widgetTotal!=0) { echo 'style="width:360px;"'; } ?>>
      <?php $site->Module('right'); ?>
      </td>
    </tr>
  </table>  
 </div>
</div>
<?php if($site->chkModule($GLOBALS['Document'],$path->Level(1))): ?>
<div id="itmy-footbg">
 <div id="itmy-footer">
 <?php echo $_SERVER['REQUEST_TIME']; ?>
 <?php $site->Module('footer'); ?>
 </div>
</div>
<?php endif; ?>
</center>
