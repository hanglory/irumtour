<?
include_once("../include/common_file.php");


$cp_id="test";

if($_GET[mode]=="drop" && $cp_id && $_SESSION["sessLogin"]["staff_type"]=="ceo"){

    /*CP 삭제 시 초기화 해야 하는 table*/
    $drop_tables[]="cmp_account";
    $drop_tables[]="cmp_air";
    $drop_tables[]="cmp_bank";
    $drop_tables[]="cmp_calendar";
    $drop_tables[]="cmp_card";
    $drop_tables[]="cmp_cargo";
    $drop_tables[]="cmp_cashbill";
    $drop_tables[]="cmp_customer";
    $drop_tables[]="cmp_estimate";
    $drop_tables[]="cmp_expense";
    $drop_tables[]="cmp_golf";
    $drop_tables[]="cmp_golf2";
    $drop_tables[]="cmp_hotel";
    $drop_tables[]="cmp_hrm";
    $drop_tables[]="cmp_img";
    $drop_tables[]="cmp_invoice";
    $drop_tables[]="cmp_partner";
    $drop_tables[]="cmp_people";
    $drop_tables[]="cmp_prepare";
    $drop_tables[]="cmp_reservation";
    $drop_tables[]="cmp_set_price";
    $drop_tables[]="cmp_set_price_golf";
    $drop_tables[]="cmp_set_price_hotel";
    $drop_tables[]="cmp_staff";
    $drop_tables[]="cmp_temperature";
    $drop_tables[]="cmp_tour";
    $drop_tables[]="cmp_passport";
    $drop_tables[]="ez_bbs";
    $drop_tables[]="ez_category1";
    $drop_tables[]="ez_category2";
    $drop_tables[]="ez_member";
    $drop_tables[]="ez_nbanner1";
    $drop_tables[]="ez_nbanner2";
    $drop_tables[]="ez_nbanner2_seq";
    $drop_tables[]="ez_request";
    $drop_tables[]="ez_tour";
    $drop_tables[]="ez_tour_seq";
    $drop_tables[]="ez_withdraw";
    $drop_tables[]="popup";

    //table 별 파일 저장 폴더 이름
    $drop_file_path['ez_bbs']="bbs_files";
    $drop_file_path['cmp_estimate']="cmp_files";
    $drop_file_path['cmp_golf2']="cmp";
    $drop_file_path['cmp_hotel']="cmp";
    $drop_file_path['cmp_hrm']="cmp";
    $drop_file_path['cmp_img']="cmp";
    $drop_file_path['cmp_partner']="cmp";
    $drop_file_path['cmp_staff']="cmp_files";
    $drop_file_path['cmp_tour']="cmp";
    $drop_file_path['ez_tour']="goods";
    $drop_file_path['cmp_passport']="cmp_pass";


    foreach ($drop_tables as $key => $table) {
        $sql = "select * from $table where cp_id='$cp_id' and cp_id<>''";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        if($rs[cp_id]){
            if($drop_file_path[$table]){
                $path = $_SERVER[DOCUMENT_ROOT]."/new/public/".$drop_file_path[$table];
                for($i=0;$i<20;$i++){
                    $col = "filename".$i;
                    if($col=="filename0") $col="filename";
                    if($rs[$col]){
                        $file="$path/".$rs[$col];
                        if(is_file($file)){
                            checkVar($file,is_file($file));
                            unlink($file);
                        }
                    }

                }
            }

            $sql2 = "
                delete from $table 
                where 
                    cp_id='$cp_id' and cp_id<>''
                ";
            list($rows) = $dbo2->query($sql2);
            checkVar($rows.mysql_error(),$sql2);
        }
    }

    $sql = "
            delete
            from cmp_cp
            where 
                id='$cp_id' and id<>''
            limit 1
        ";
    $dbo->query($sql);
    checkVar($rs[id].mysql_error(),$sql);

    echo "
        <script>
            alert('계정을 삭제하였습니다.');
            top.location.href='list_cp.php';
        </script>
    ";

}



exit;
