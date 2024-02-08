<?
include_once("../include/common_file.php");

$ctgfilter= " and (cp_id='$CP_ID' or cp_id='')";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title>jQuery treeview</title>

	<link rel="stylesheet" href="../../jquery.treeview/jquery.treeview.css" />
	<link rel="stylesheet" href="../../jquery.treeview/type/screen.css" />
	<link rel="stylesheet" href="../include/default.css">
	<link rel="stylesheet" href="../include/basic.css" type="text/css">

	<script src="../../jquery.treeview/lib/jquery.js" type="text/javascript"></script>
	<script src="../../jquery.treeview/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="../../jquery.treeview/jquery.treeview.js" type="text/javascript"></script>

	<script type="text/javascript">
		$(function() {
			$("#tree").treeview({
				collapsed: true,
				animated: "fast",
				control:"#sidetreecontrol",
				prerendered: true,
				persist: "location"
			});

		})

		function add(ctg,id_no){
			parent.document.getElementById('reg').src="category_reg.php?ctg="+ctg+"&id_no="+id_no;
			//parent.location.href="category.php?ctg="+ctg+"&id_no="+id_no;
		}
	</script>
	</head>
	<body>

	<div id="main">
	<div id="sidetree">

	<ul class="treeview" id="tree">
		<?
		$sql="select * from ez_tour_category1 where id_no>0 $ctgfilter order by seq asc";
		list($rows)=$dbo->query($sql);
    //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
		$i=1;
		while($rs=$dbo->next_record()){
		?>

				<li <?=($rows==$i)?"class='last'":" class='expandable'"?>><div class="hitarea expandable-hitarea"></div> <a href="javascript:add('category1','<?=$rs[id_no]?>');"><?=$rs[seq]?>. <?=$rs[subject]?> <?=($rs[bit_hide])?'(숨김)':''?></a>

					<ul>
						<?
						$sql2="select * from ez_tour_category2 where code1=$rs[id_no] $ctgfilter order by seq asc,seq_date desc";
						list($rows2) = $dbo2->query($sql2);
						$k=1;
						while($rs2=$dbo2->next_record()){
							//$dbo3->query("update ez_tour_category2 set seq=$k where id_no=$rs2[id_no]");
						?>
						<li <?=($rows2==$k)?"class='last'":""?>><div class="hitarea expandable-hitarea"></div> <a href="javascript:add('category2','<?=$rs2[id_no]?>');"><?=$rs2[seq]?>. <?=$rs2[subject]?> <?=($rs2[bit_hide])?'(숨김)':''?></a>

							<ul>
								<?
								$sql3="select * from ez_tour_category3 where code2=$rs2[id_no] $ctgfilter order by seq asc,seq_date desc";
								list($rows3) = $dbo3->query($sql3);
								$j=1;
								while($rs3=$dbo3->next_record()){
									//$dbo4->query("update ez_tour_category3 set seq=$j where id_no=$rs3[id_no]");
								?>
								<li <?=($rows3==$j)?"class='last'":""?>><a name="p<?=$rs3[id_no]?>"></a><a href="javascript:add('category3','<?=$rs3[id_no]?>');"><?=$rs3[seq]?>. <?=$rs3[subject]?> <?=($rs3[bit_hide])?'(숨김)':''?></a>

								<ul>
									<?
									$sql4="select * from ez_tour_category4 where code3=$rs3[id_no] $ctgfilter order by seq asc,seq_date desc";
									list($rows4) = $dbo4->query($sql4);
									$j=1;
									while($rs4=$dbo4->next_record()){
										//$dbo_->query("update ez_tour_category4 set seq=$j where id_no=$rs3[id_no]");
									?>
									<li <?=($rows4==$j)?"class='last'":""?>><a name="p<?=$rs4[id_no]?>"></a><a href="javascript:add('category4','<?=$rs4[id_no]?>');"><?=$rs4[seq]?>. <?=$rs4[subject]?> <?=($rs4[bit_hide])?'(숨김)':''?></a></li>
									<?
									$j++;
									}?>
								</ul>

								</li>
								<?
								$j++;
								}?>
							</ul>

						</li>
						<?
						$k++;
						}?>
					</ul>

				</li>

		<?
			$i++;
		}
		?>

	</ul>


</div>
</div>

</body>
</html>