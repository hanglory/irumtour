<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_golf2";
$MENU = "cmp_basic";
$TITLE = "골프장 정보";
$file_rows=5;



$sql = "
        select 
            a.*,
            (select club_name from cmp_golfclub where club_id=a.club_id) as club_name
        from $table as a
        where a.id_no=$id_no
    ";
$dbo->query($sql);
$rs= $dbo->next_record();

?>
<?include("../top_min.html");?>
<style>
body{padding:0 10px;}
</style>


	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con" style="padding-left:10px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>

	<!--내용이 들어가는 곳 시작-->

	<br>

    <table border="0" cellspacing="1" cellpadding="3" width="100%">
		<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject">골프장명</td>
          <td colspan="3">
	           <?=$rs['name']?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">예약실전화번호</td>
          <td width="35%">
	           <?=$rs['reg_phone']?>
          </td>

          <td class="subject" width="17%">예약실 팩스번호</td>
          <td>
	           <?=$rs['reg_fax']?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject" width="15%">예약오픈일</td>
          <td>
	           <?=$rs['reg_open_date']?>
          </td>

          <td class="subject">예약방법</td>
          <td>
	           <?=$rs['reg_method']?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">결제방법</td>
          <td>
	           <?=$rs['pay_method']?>
          </td>
          <td class="subject">결제일</td>
          <td>
          	 <?=$rs['reg_pay_date']?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">담당자명</td>
          <td>
	           <?=$rs['reg_staff']?>
          </td>

          <td class="subject">담당자연락처</td>
          <td>
	           <?=$rs['reg_staff_phone']?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">담당자 이메일</td>
          <td colspan="3">
	           <?=$rs['staff_email']?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">골프텔유무</td>
          <td>
	           <?=$rs['golftel']?>
          </td>

          <td class="subject">연계호텔</td>
          <td>
	           <?=$rs['hotel']?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

       <tr>
          <td class="subject" width="15%">직거래가능여부</td>
          <td>
	           <?=$rs['direct']?>
          </td>

          <td class="subject">종류</td>
          <td>
	           <?=$rs['assort_membership']?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

       <tr>
          <td class="subject" width="15%">규모</td>
          <td>
	           <?=$rs['ground_scale']?>
          </td>

          <td class="subject">랜드사</td>
          <td>
	           <?=$rs['land_company']?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>




        <tr>
          <td class="subject" width="15%">취소규정</td>
          <td colspan="3">
	           <?=nl2br($rs['reg_cancel'])?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>




        <tr><td colspan="12" bgcolor='#E1E1E1' height=1></td></tr>

        <tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td>
                        <?if($_SESSION["sessLogin"]["staff_type"]=="ceo"){?>
                        <span class="btn_pack medium bold"><a href="view_golf2.php?id_no=<?=$id_no?>"> 수정하기 </a></span>&nbsp;&nbsp;&nbsp;&nbsp;
                        <?}?>
                        <span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
                    </td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
		  </td>
        </tr>

        <tr>
	  <td colspan=10 height=20>
	  </td>
        </tr>
	</form>
	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>