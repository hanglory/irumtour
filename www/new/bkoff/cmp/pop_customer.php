<?
include_once("../include/common_file.php");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_people";
$TITLE = "고객정보 (아피스,고객명단)";

$name = trim($name);

$filter .= " group by phone,skypass"; // 20200827 추가



if($mode=="drop" && $tbl && $id_no){
    $sql = "
        update $tbl set
            bit_hide_customer=1
        where 
            cp_id='$CP_ID'
            and id_no=$id_no
        limit 1
        ";
    list($rows)=$dbo->query($sql);
    //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar($rows.mysql_error(),$sql);exit;}
    back();
    exit;
}



//$FILTER_PARTNER_QUERY_ = str_replace("main_staff","staff",$FILTER_PARTNER_QUERY);
//$FILTER_PARTNER_QUERY = str_replace("main_staff","b.main_staff",$FILTER_PARTNER_QUERY);

$sql = "
    select 
        tbl,
        origin_id_no,    
        name,
        sex,
        name_eng,
        rn,
        passport_no,
        passport_limit,
        phone,
        skypass,
        leader,
        staff
    from(
      select 
          'cmp_people' as tbl,
          a.id_no as origin_id_no,
          a.name,
          a.sex,
          a.name_eng,
          a.rn,
          a.passport_no,
          a.passport_limit,
          a.phone,
          '' as skypass,
          b.name as leader,
          b.main_staff as staff
      from $table as a left join cmp_reservation as b
      on a.code=b.code
      where a.name like '%$name%'
          and b.cp_id='$CP_ID'
          and (a.passport_no<>'' or a.phone<>'' or a.rn<>'')
          and a.bit_hide_customer=0

      union all

      select 
          'cmp_customer' as tbl,  
          a.id_no as origin_id_no,
          a.name,
          a.sex,
          a.name_eng,
          a.rn,
          a.passport_no,
          a.passport_limit,
          a.phone,
          a.skypass,
          '' as leader,
          a.staff
      from cmp_customer as a
          where name like '%$name%'   
          and a.cp_id='$CP_ID'
          and (a.passport_no<>'' or a.phone<>'' or a.rn<>'')
          and a.bit_hide_customer=0
    ) as data
    group by rn,passport_no,phone,skypass    
    order by name asc
";  


list($rows) =  $dbo->query($sql);
//if($debug){checkVar(mysql_error(),$sql);}
?>
<?include("../top_min.html");?>
<script type="text/javascript">
<!--
function set_user(id,i,j,bit){

    <?if($page){?>
        opener.$("#name").val($(".name_"+j).text());
        opener.$("#phone").val($(".phone_"+j).text());
    <?}else{?>
        if(i){
            opener.$("#id_"+i).val(id);
            opener.$("#name_"+i).val($(".name_"+j).text());
            opener.$("#sex_"+i).val($(".sex_"+j).text());
            opener.$("#name_eng_"+i).val($(".name_eng_"+j).text());
            opener.$("#rn_"+i).val($(".rn_"+j).text());
            opener.$("#passport_no_"+i).val($(".passport_no_"+j).text());
            opener.$("#passport_limit_"+i).val($(".passport_limit_"+j).text());
            opener.$("#phone_"+i).val($(".phone_"+j).text());
            opener.$("#skypass_"+i).val($(".skypass_"+j).text());

        }else{
            opener.$("#id").val(id);
            opener.$("#name").val($(".name_"+j).text());
            opener.$("#sex").val($(".sex_"+j).text());
            opener.$("#name_eng").val($(".name_eng_"+j).text());
            opener.$("#rn").val($(".rn_"+j).text());
            opener.$("#passport_no").val($(".passport_no_"+j).text());
            opener.$("#passport_limit_").val($(".passport_limit_"+j).text());
            opener.$("#phone").val($(".phone_"+j).text());
            //opener.$("#skypass").val($(".skypass"+j).text());
        }
    <?}?>

    if(parseInt(bit)>0){
        opener.document.getElementById('plink_'+i).style.color='#ff0066';
    }else{
        opener.document.getElementById('plink_'+i).style.color='gray';
    }

    //actarea.location.href=url;

    self.close();
}

function drop_user(tbl,id_no){
    var url = "<?=SELF?>?mode=drop";
    url+="&tbl="+tbl;
    url+="&id_no="+id_no;
    if(confirm("삭제하시겠습니까?")){
        location.href=url;
    }
}

//-->
</script>
<script language="JavaScript">

</script>

<div style="padding:0 10px 0 10px">

    <table width="97%" border="0" cellspacing="0" cellpadding="0">
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

    <!-- Search Begin------------------------------------------------>
    <div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_list">
    <form name=fmSearch method="get">
    <input type=hidden name='i' value="<?=$i?>">
    <input type=hidden name='page' value="<?=$page?>">


    <tr height=22>
    <td align=right valign=top>
    <input class=box type="text" name="name" size="15" maxlength="40" value='<?=$name?>'>
    <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
    </td>
    <tr>
    </form>
    </table>
    </div>
    <!-- Search End------------------------------------------------>


    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_list">



        <tr><td colspan="12"  bgcolor='#5E90AE' height="2"></td></tr>
        <tr align="center" height="25" bgcolor="#F7F7F6">
        <th class="subject" >대표자</th>
        <th class="subject" >고객명</th>
        <th class="subject" >성별</th>
        <th class="subject" >영문명</th>
        <th class="subject" >주민번호</th>
        <th class="subject" >여권번호</th>
        <th class="subject" >유효기간</th>
        <th class="subject" >연락처</th>
        <th class="subject" >스카이패스</th>
        <th class="subject" ></th>
        </tr>

        <?
        $j=1;
        $filter_sub="";
        //if(strstr("@14.37.242.84@221.154.216.133@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
        while($rs=$dbo->next_record()){
            if($rs[rn]){
            $aes = new AES($rs[rn], $inputKey, $blockSize);
            $dec=$aes->decrypt();
            $rs[rn] = $dec;
            }
            if(strstr($rs[passport_no],"error_")) $rs[passport_no]="";
            if($rs[passport_no]){
            $aes = new AES($rs[passport_no], $inputKey, $blockSize);
            $dec=$aes->decrypt();
            $rs[passport_no] = $dec;
            }

            $sql2 = "select * from cmp_passport where passport_no='$rs[passport_no]'";
            list($photo_rows) = $dbo2->query($sql2);

        ?>
        <tr height="35" align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td><span class="leader_<?=$j?>"><?=$rs[leader]?></span></td>
          <td><span class="name_<?=$j?>"><?=$rs[name]?></span></td>
          <td><span class="sex_<?=$j?>"><?=$rs[sex]?></span></td>
          <td><span class="name_eng_<?=$j?>"><?=$rs[name_eng]?></span></td>
          <td><span class="rn_<?=$j?>"><?=$rs[rn]?></span></td>
          <td><span class="passport_no_<?=$j?>"><?=$rs[passport_no]?></span></td>
          <td><span class="passport_limit_<?=$j?>"><?=$rs[passport_limit]?></span></td>
          <td><span class="phone_<?=$j?>"><?=$rs[phone]?></span></td>
          <td><span class="skypass_<?=$j?>"><?=$rs[skypass]?></span>

          </td>
          <td>
            <span class="btn_pack small bold"><a href="javascript:set_user('<?=$rs[id_no]?>','<?=$i?>','<?=$j?>','<?=$photo_rows?>')"> 선택 </a></span>
            <span class="btn_pack small bold"><a href="javascript:drop_user('<?=$rs[tbl]?>','<?=$rs[origin_id_no]?>')"> 삭제 </a></span>
          </td>
        </tr>
        <tr><td colspan="12" class="tblLine"></td></tr>
        <?
            if($rs[sex]) $filter_sub=" and sex<>'$rs[sex]'";
            if($rs[name_eng]) $filter_sub=" and name_eng<>'$rs[name_eng]'";
            if($rs[rn]) $filter_sub=" and rn<>'$rs[rn]'";
            if($rs[passport_no]) $filter_sub=" and passport_no<>'$rs[passport_no]'";
            if($rs[phone]) $filter_sub=" and phone<>'$rs[phone]'";
        $j++;
        }?>
        <?if(!$rows){?>
        <tr><td colspan="12" class="c" height="50">해당 하는 자료를 찾을 수 없습니다.</td></tr>
        <tr><td colspan="12" class="tblLine"></td></tr>
        <?}?>


    </table>



</div>



    <!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>