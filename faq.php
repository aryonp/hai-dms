<?php
/* -----------------------------------------------------
 * File name	: faq.php								
 * Created by 	: M. Aryo N. Pratama		
 * -----------------------------------------------------				            
 * Purpose		: Manage FAQ data.											                 			
 * -----------------------------------------------------
 */

require_once 'init.php';
require_once LIB_PATH.'functions.lib.php';
require_once LIB_PATH.'paging.lib.php';
chkLicense();
chkSession();

$page_title		= "FAQ";
$page_id		= "7";
chkSecurity($page_id);

$faq_list_query ="SELECT id, question, answer FROM faq WHERE del = '0' ORDER BY id ASC ";
$pagingResult = new Pagination();
$pagingResult->setPageQuery($faq_list_query);
$pagingResult->paginate();
$this_page = $_SERVER['PHP_SELF']."?".$pagingResult->getPageQString();

$status = "";

if (isset($_POST['add_faq'])){
	$question 	= mysql_real_escape_string(strip_tags(trim($_POST['faq_question'])));
	$answer = mysql_real_escape_string(strip_tags(trim($_POST['faq_answer'])));
	$uid	= $_SESSION['uid'];
	$date   = date('Y-m-d H:i:s');
	$add_faq_q  = "INSERT INTO faq (question, answer, createBy, createDate, updBy, updDate) 
			       VALUES ('$question','$answer','$uid','$date','$uid','$date');"; 
	if (!empty($question) AND !empty($answer)){
		if(@mysql_query($add_faq_q)) {
			log_hist(31,$question);
			header("location:$this_page");
			exit();
		} else {
			$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
						Failed to create new FAQ. Please check all available parameters.
				   		</div>";
			log_hist(32,$question);
		}
	}
	else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
					Failed to create new FAQ. Missing required information. Please check all available parameters.
				   	</div>";
		log_hist(32,$question);
	}
}

if (isset($_POST['upd_faq'])){
	$nid 	  = trim($_POST['nid']);
	$question = mysql_real_escape_string(strip_tags(trim($_POST['question'])));
	$answer   = mysql_real_escape_string(strip_tags(trim($_POST['answer'])));
	$uid	= $_SESSION['uid'];
	$date   = date('Y-m-d H:i:s');
	$upd_faq_q  = "UPDATE faq 
			       SET question ='$question', answer = '$answer', updBy = '$uid', updDate = '$date'
				   WHERE id = '$nid';";
	if(@mysql_query($upd_faq_q)) {
		log_hist(33,$nid);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
					Failed to update the FAQ. Please check all available parameters.
				   	</div>";
		log_hist(34,$nid);
	}
}

if (isset($_GET['did'])){
	$did = trim($_GET['did']);
	$uid	= $_SESSION['uid'];
	$date   = date('Y-m-d H:i:s');
	$del_faq_q  = "UPDATE faq 
			       SET del = '1', updBy = '$uid', updDate = '$date' 
	               WHERE id ='$did';";
	if(@mysql_query($del_faq_q)) {
		log_hist(35,$did);
		header("location:$this_page");
		exit();
	} else {
		$status .= "<div class=\"alert alert-danger alert-dismissable\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>
					Failed to delete the FAQ. Please check all available parameters.
				   	</div>";
		log_hist(36,$did);
	}
}

if(isset($_POST['cancel'])){
	header("location:$this_page"); 	
}

include THEME_DEFAULT.'header.php'; ?>
<//-----------------CONTENT-START-------------------------------------------------//>
<h1 class="page-header"><?=$page_title?> Page</h1>
<?=$status?>
<a href="#" data-toggle="collapse" data-target="#form-faq">
	<div>
		<span>Add New FAQ</span>
	</div>
</a><br>
<div id="form-faq" class="collapse">
<form class="well form" role="form" action="" method="POST">
	<div class="form-group">
		<label>Question</label>
		<input type="text" name="faq_question" class="form-control">
	</div>
	<div class="form-group">
		<label>Answer</label>
		<textarea class="form-control" name="faq_answer" rows="3"></textarea>
	</div>
	<div class="form-group">
		<button type="submit" name="add_faq" class="btn btn-primary">Create New FAQ</button>
	</div>
</form>
</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			&nbsp;
		</div>
		<div class="panel-body">
		<?=$pagingResult->pagingMenu()?>
			<br>
			<table border="0" cellpadding="1" cellspacing="1" width="100%" class="table table-striped table-bordered table-condensed">	
				<thead>
				<tr><td width="25" align="left">&nbsp;<b>NO</b></td>
					<td width="*" align="left">&nbsp;<b>LIST</b></td>
					<td width="*" colspan="2" align="center">&nbsp;<b>CMD</b>&nbsp;</td>
				</tr>
			</thead>
			<tbody>
<?php if ($pagingResult->getPageRows()>= 1) {	
			$count = $pagingResult->getPageOffset() + 1;
			$result = $pagingResult->getPageArray();
			while ($faq_list_array = mysql_fetch_array($result, MYSQL_ASSOC)) {
				if (isset($_GET['nid']) && $_GET['nid'] == $faq_list_array["id"]) {?>
				<form method="POST" action="">
				<input type="hidden" name="nid" value="<?=$faq_list_array["id"]?>">
				<tr bgcolor="#ffcc99" align="left" valign="top">
					<td width="25">&nbsp;<?=$count?>.</td>
					<td>Q: <input type="text"  class="form-control" name="question" value="<?=($faq_list_array["question"])?strip_tags($faq_list_array["question"]):"-";?>"><br/><br />
					A: <textarea rows="2"  class="form-control" name="answer" wrap="virtual"><?=($faq_list_array["answer"])?nl2br($faq_list_array["answer"]):"-;"?></textarea></td>
					<td align="center">
						<button type="submit" class="btn btn-default" name="upd_faq">
							<span class="glyphicon glyphicon-floppy-saved"></span>
						</button>
					</td>
					<td align="center">
						<button type="submit" class="btn btn-default" name="cancel">
							<span class="glyphicon glyphicon-floppy-remove"></span>
						</button>
					</td>
				</tr>
				</form>
<?php 			} else { ?>
				<tr align="left" valign="top">
					<td width="25">&nbsp;<?=$count?>.</td>
					<td><b><?=($faq_list_array["question"])?ucwords($faq_list_array["question"]):"-";?></b><br /><br />
					    <?=($faq_list_array["answer"])?nl2br($faq_list_array["answer"]):"-;"?></td>
					<td width="60" align="center">
						<a title="Edit FAQ" class="btn btn-default" href="<?=$this_page?>&nid=<?=$faq_list_array["id"]?>">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</td>
					<td width="60" align="center">
						<a title="Delete FAQ" class="btn btn-default" href="<?=$this_page?>&did=<?=$faq_list_array["id"]?>">
							<span class="glyphicon glyphicon-trash"></span>
						</a>
					</td>
				</tr>
<?php	 		} $count++; 
			}
		} else {?>
				<tr><td colspan="5" align="center" bgcolor="#e5e5e5"><br />No Data Entries<br /><br /></td></tr>
<?php 	} ?></tbody>
		</table>
				<?=$pagingResult->pagingMenu()?>
</div></div>
<//-----------------CONTENT-END-------------------------------------------------//>
<?php include THEME_DEFAULT.'footer.php'; ?>