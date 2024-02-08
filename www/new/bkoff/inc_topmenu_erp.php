
<table border="0" cellspacing="0" cellpadding="0" width="95%" class="tbl_topmenu">
 <tr>
    <td align="center">
    <?
    $TOPMENU = array(
        "기본설정"=>"../cmp/list_golf.php?tms=1",
        "일정표등록"=>"../cmp/list_estimate.php?tms=2",
        "예약관리"=>"../cmp/list_reservation.php?tms=3",
        "송출현황"=>"../cmp/list_report2.php",
        "입출금관리"=>"../cmp/list_transfer.php",
        "경영관리"=>"../cmp/list_paper1.php?dtype=tour_date&tms=5",
        "매출현황"=>"../cmp/list_paper2.php?dtype=tour_date&partner=1&tms=6",
        "휴가및출장"=>"../cmp/list_hrmcal.php?tms=7",
        "항공예약"=>"javascript:window.open('/new/bkoff/cmp/sharevalue.php?type=reg')",
    );

    //$PARTNER_MENUS ="현황및실적,정산내역"; //권한에 없더라도 나와야 하는 파트너 메뉴
    //$PARTNER_MENUS2 ="인사관리"; //권한에 없다라도 나와야 하는 직원+파트너 메뉴


    $sess_proof_erp = $_SESSION['sessLogin']['proof_erp'];
    $sess_proof_erp .= ",송출현황"; 


    foreach ($TOPMENU as $TOPMENU_KEY => $TOPMENU_VAL) {

        $bit_topmenu_show = (strstr($sess_proof_erp,$TOPMENU_KEY))?1:0; 
        if($_SESSION['sessLogin']['staff_type']=="ceo") $bit_topmenu_show=1;
        if($bit_topmenu_show && !strstr($PARTNER_MENUS,$TOPMENU_KEY) && !strstr($PARTNER_MENUS2,$TOPMENU_KEY)){
            echo "<a href=\"$TOPMENU_VAL\" class=\"topmenu2\">$TOPMENU_KEY</a>";
        }

        elseif(!$bit_topmenu_show && strstr($_SESSION["sessLogin"]["staff_type"],"partner") && strstr($PARTNER_MENUS,$TOPMENU_KEY)){
            echo "<a href=\"$TOPMENU_VAL\" class=\"topmenu2\">$TOPMENU_KEY</a>";
        }

        if($_SESSION["sessLogin"]["staff_type"]=="leader_partner" && strstr($PARTNER_MENUS2,$TOPMENU_KEY)){
            echo "<a href=\"$TOPMENU_VAL\" class=\"topmenu2\">$TOPMENU_KEY</a>";
        }        
    }
    ?>

    <?if(strstr("@11114.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){?>
    <span style="color:yellow">(H <?=$_SESSION["sessLogin"]["staff_type"]."/cp_id:".$cp_id."/CP_ID:".$CP_ID;?>)</span>
    <?}?>
    </td>
 </tr>
</table>    