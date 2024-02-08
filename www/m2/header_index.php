<title><?=$SITE_NAME?></title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=yes" />
<meta name="HandheldFriendly" content="true" />


<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />


<?IF($CP_COMPANY){?>
    <title><?=$CP_COMPANY2?></title>    

    <?if(!$META_CUST){?>
        <meta property="og:title" content="<?=$CP_COMPANY2?>" />
        <meta property="og:url" content="<?=$DOMAIN?>" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="http://<?=$_SERVER["HTTP_HOST"]?>/new/public/partner/<?=$CP_LOGO?>" />
        <meta property="og:description" content="<?=str_replace("이룸투어",$CP_COMPANY,$DESCRIPTION)?>" />

        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="<?=$CP_COMPANY2?>" />
        <meta name="twitter:url" content="http://<?=$_SERVER["HTTP_HOST"]?>" />
        <meta name="twitter:image" content="http://<?=$_SERVER["HTTP_HOST"]?>/new/public/partner/<?=$CP_LOGO?>" />
        <meta name="twitter:description" content="<?=str_replace("이룸투어",$CP_COMPANY,$DESCRIPTION)?>" />
        <meta name="keywords" content="<?=($MATA_KEYWORDS)? $MATA_KEYWORDS : $CP_COMPANY2?>" />
    <?}?>
<?}ELSE{?>
    <title><?=$SITE_NAME?></title>    

    <?if(!$META_CUST){?>
        <meta name="description" content="<?=$DESCRIPTION?>">    
        <meta property="og:title" content="<?=$SITE_NAME?>" />
        <meta property="og:url" content="<?=$DOMAIN?>" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="<?=$DOMAIN?>/renew/images/common/top_logo.gif" />
        <meta property="og:description" content="<?=$DESCRIPTION?>" />

        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="<?=$SITE_NAME?>" />
        <meta name="twitter:url" content="<?=$DOMAIN?>" />
        <meta name="twitter:image" content="<?=$DOMAIN?>/renew/images/common/top_logo.gif" />
        <meta name="twitter:description" content="<?=$DESCRIPTION?>" />
        <meta name="keywords" content="<?=($MATA_KEYWORDS)? $MATA_KEYWORDS : $SITE_NAME?>" />
    <?}?>
<?}?>
<link rel="canonical" href="<?=$CPAGE?>">


<link rel="shortcut icon" href="<?=$DOMAIN?>/m/favicon.ico">
<link rel="apple-touch-icon" href="<?=$DOMAIN?>/m/favicon.ico" />


<!-- Google Tag Manager -->
<script async>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TXLDM3T');</script>
<!-- End Google Tag Manager -->