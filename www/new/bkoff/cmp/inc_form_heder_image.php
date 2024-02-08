<?
$staff_header="";
if($CP_ID){
    $sql_ = "
          select 
                filename3
            from cmp_cp
            where 
                id='$CP_ID'
                and filename3<>''
            limit 1    
        ";
    $dbo_->query($sql_);
    $rs_=$dbo_->next_record();
    if($rs_[filename3]) $staff_header = $DOMAIN."/new/public/partner/".$rs_[filename3];
}
?>
<center>
    <?if($staff_header){?>
        <img src="<?=$staff_header?>" style="max-width:<?=$page_width?>px"/>
    <?}else{?>
        <?if(!$CP_ID){?>
        <img src="<?=$caurl?>/bkoff/cmp/info02.gif" style="max-width:<?=$page_width?>px"/>
        <?}?>
    <?}?>
</center>