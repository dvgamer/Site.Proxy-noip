<?php
	switch($_GET['status'])
	{
		case 'exists':
			if(file_exists($_GET['dir'])){
			  echo json_encode(array('pass'=>'<span id="check-p">Passed.','chk'=>1));
			} else {
			  echo json_encode(array('pass'=>'<span id="check-f">Warning.','chk'=>0));
			}
			break;
		case 'read':
			if(is_readable($_GET['dir'])){
			  echo json_encode(array('pass'=>'<span id="check-p">Passed.','chk'=>1));
			} else {
			  echo json_encode(array('pass'=>'<span id="check-f">Warning.','chk'=>0));
			}
			break;
		case 'write':
			if(is_writable($_GET['dir'])){
			  echo json_encode(array('pass'=>'<span id="check-p">Passed.','chk'=>1));
			} else {
			  echo json_encode(array('pass'=>'<span id="check-f">Warning.','chk'=>0));
			}
			break;
		case 'folder':
			if($_GET['folder']!=''){	
			  if (file_exists($_GET['dir'])) {
				  echo json_encode(array('pass'=>'<span id="check-p">Created.','chk'=>1));
			  } else {
			     mkdir($_GET['dir'],0777);
			 	 echo json_encode(array('pass'=>'<span id="check-f">Warning.','chk'=>0));
			  }
			} else {
			  echo json_encode(array('pass'=>'<span id="check-f">Warning.','chk'=>0));
			}
			break;
		case 'folderchk':
			if (file_exists($_GET['dir'])) {
				echo json_encode(array('pass'=>'<span id="check-p">Created.','chk'=>1));
			} else {
				echo json_encode(array('pass'=>'<span id="check-f">Warning.','chk'=>0));
			}
			break;
		case 'gd2':
			$gd2 = gd_info();
			if($gd2["JPG Support"] && $gd2["PNG Support"]) {
			  echo json_encode(array('pass'=>'<span id="check-p">Passed.','chk'=>1));
			} else {
			  echo json_encode(array('pass'=>'<span id="check-f">Warning.','chk'=>0));
			}
			break;
	}
?>