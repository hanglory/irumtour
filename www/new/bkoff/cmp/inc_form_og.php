<?
if($staff_id){
    $sql_ = "
        select 
            a.*,
            b.company,
            b.biz_no,
            b.biz_type1,
            b.biz_type2,
            b.bank_account,
            b.bank,
            b.bank_owner,
            concat(b.address,' ',b.address2) as address_,
            b.filename as partner_logo, 
            b.filename3 as partner_file, 
            b.filename5 as big_sign 
        from cmp_staff as a left join cmp_cp as b
        on a.cp_id=b.id
        where 
            a.id='$staff_id' and a.id<>''
        limit 1
        ";
    $dbo_->query($sql_);
    $rs_=$dbo_->next_record();
    if($rs_[filename1]) $staff_header = $DOMAIN."/new/public/cmp_files/".$rs_[filename1];
    elseif(!$rs_[filename1] && $rs_[partner_file]) $staff_header = $DOMAIN."/new/public/partner/".$rs_[partner_file];     
    if($rs_[partner_logo]) $cp_logo = $DOMAIN."/new/public/partner/".$rs_[partner_logo];
    if($rs_[company]) $cp_company = $rs_[company];
    if($rs_[company]){
        $cp_account = "$rs_[bank] $rs_[bank_account] $rs_[bank_owner]";
        $ACCOUNT = $cp_account;
        $carr = explode(" ",$ACCOUNT);
    }

    $CP_INFO[company] = $rs_[company];
    $CP_INFO[biz_no] = $rs_[biz_no];
    $CP_INFO[biz_type1] = $rs_[biz_type1];
    $CP_INFO[biz_type2] = $rs_[biz_type2];
    $CP_INFO[address] = $rs_[address_];
    $CP_INFO[big_sign] = $rs_[big_sign];

}
?>
<title><?=($cp_company)? $cp_company : $SITE_NAME?></title>
<meta property="og:type" content="website">
<meta property="og:title" content="<?=$OG_TITLE?>">
<meta property="og:description" content="<?=$OG_TITLE?> <?=($cp_company)? $cp_company : $SITE_NAME?>">
<meta property="og:site_name" content="<?=($cp_company)? $cp_company : $SITE_NAME?>">
<meta property="og:url" content="<?=$CPAGE?>">
<?if($cp_logo){?>
<meta property="og:image" content="<?=$cp_logo?>">
<?}else{?>
<meta property="og:image" content="https://irumtour.net/renew/images/common/top_logo.gif">
<?}?>