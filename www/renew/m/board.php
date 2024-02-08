<?
include("script/include_common_file_min_mobile.php");
?>
<!DOCTYPE html system "ABOUT:LEGACY-COMPAT">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../html/include/form_check.js"></script>
<script type="text/javascript" src="../html/include/function.js"></script>
</head>
<body>

	<div style="padding:10px;background-color:#FFF">
		<!-- 게시판 -->
		<?
		$bid = ($bid)? $bid : "goods_".$rs[tid];
		$bmode = ($bmode)? $bmode : "list";
		$BOARD = "script_bbs/" . $bmode .  ".php";
		include($BOARD);
		?>
		<!--// 게시판 -->
	</div>
</body>
</html>