<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ABSORPTION MANGA TO HDD.</title>
<style type="text/css">
body,td,th {
	font-family: Tahoma;
}
</style>
</head>
<head>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="event.chk.js"></script>
<script type="text/javascript" src="event.plugins.js"></script>

</head>
<body>
<center><br /><br /><br />
<form name="manga" id="manga" method="post" action="">
<table id="box-main" width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><h1>ABSORPTION MANGA TO HDD.</h1></td>
    <td rowspan="2" valign="top">
    <center><fieldset style="width:85%;">
      <legend>Status</legend>
      <table id="status" border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td width="76" align="right"><strong>Exists: </strong></td>
          <td width="70" align="left" id="tdexists"></span></td>
          <td width="90" align="right"><strong>Folder: </strong></td>
          <td width="80" align="left" id="tdfolder"></td>
        </tr>
        <tr>
          <td align="right"><strong>Readable: </strong></td>
          <td align="left" id="tdread">&nbsp;</span></td>
          <td align="right"><strong>GD2: </strong></td>
          <td align="left" id="tdgd2"></span></td>
          </tr>
        <tr>
          <td align="right"><strong>Writable: </strong></td>
          <td align="left" id="tdwrite">&nbsp;</span></td>
          <td align="right"><strong></strong></td>
          <td align="left" id="tdready"></span></td>
          </tr>
        </table>
        
    </fieldset><br /></center> 
      <div id="manga"><strong>Manga Name:</strong><span id="name"></span><br /><strong>Chapter Total:</strong><span id="chapter"></span></div><br />
      <input id="directory" type="hidden" name="directory" size="50" maxlength="50" value="C:\AppServ\www\LifeItMy\tmp_file\" disabled="disabled"/>
    <label><strong>Folder Name:</strong><br />
      <input type="text" name="folder" id="folder" size="50" maxlength="100" /></label><br />
    <em id="error">etc. GE Good Ending</em><br /><br />    
    <center><fieldset style="display:none;">
      <legend>Size Image</legend>
      <label id="radio"><input name="size" type="radio" value="1" checked="checked" /> Normal <em>(500x768)</em></label>&nbsp;&nbsp;
      <label id="radio"><input name="size" type="radio" value="0" /> Full Size <em>(848x1100)</em></label><br />
      </fieldset>
<!--      <em>- Full size will take more than 2 times normal size.</em><br /><br /><br />
-->      <input id="created" type="button" value="Step.2 Created Folder" disabled="disabled" />&nbsp;&nbsp;
		 <input id="write" type="button" value="Step.3 Generator Image" disabled="disabled" /><br /><br />
		 <input id="reset" type="button" value="Reset New Manga" disabled="disabled" />     
    </center>    </td>
    </tr>
  <tr>
    <td valign="top" width="390">

      <label><strong>Request Manga URL :</strong><br />
        <input type="text" id="urlrequest" name="url_manga" size="65" />
        <span id="urlvalid" style="display:none"><img src="images/proload-min.gif" border="0" width="16" height="11" align="absmiddle" hspace="5" /></span>
        </label><br />
      <em id="error_url">etc. http://www.niceoppai.net/viewforum.php?f=10&start=0</em> <br /><br />
       <center><input id="chk" type="button" value="Step.1 Check Manga" disabled="disabled" /></center><br />
      <center><fieldset style="width:70%; text-align:left;">
        <legend>Manga Thai Translate List Support</legend>
        <div id="list-th">
         <ul>
          <li id="nekopost">nekopost.net</li>
          <li id="anity">anity.net</li>
          <li id="niceoppai">niceoppai.net</li>
          <li id="zoisandsook">zoisandsook.com</li>
          <li id="naruto">board.naruto.in.th</li>          
          <li id="viruseddy">viruseddy.blogspot.com</li>
          <li id="anubiscuit">anubiscuit.blogspot.com</li>
          <li id="ramosdinho">ramosdinho.blogspot.com</li>         
          <li id="firepocket">firepocket.blogspot.com</li>         
          <li id="fumin-kun">fumin-kun.blogspot.com</li>         
         </ul>  
         </div> 
        </fieldset></center><br />
      <center><fieldset style="width:70%; text-align:left;">
        <legend>Manga English Translate List Support</legend>
        <div id="list-en">
         <ul>
          <li id="gehentai">g.e-hentai.org</li>
          <li id="bleachexile">manga.bleachexile.com</li>
         </ul>   
         </div>
        </fieldset></center><br />
    </td>
    </tr>
  <tr>
    <td colspan="2">
    <center><fieldset id="process">
    <legend id="process-text">Log Status</legend>
    <div id="process-bg"><div id="process-bar"><div id="process-txt">Request Manga Site.</div></div></div>
    <div id="process-log" style="display:none;"><br /><br /><strong>Starting log..</strong>.<br></div>
    </fieldset></center>  
    <div align="right"><em>Design by it-my.selfip.info.</em></div>
    </td>
    </tr>
</table>
</form>
<div id="debug" style="width:600px;" align="left"></div>
</center>
</body>
</html>
