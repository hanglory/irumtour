<title><?=$SITE_NAME?></title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=yes" />
<meta name="HandheldFriendly" content="true" />

<?IF($CP_COMPANY){?>
    <title><?=$CP_COMPANY2?></title>    

    <?if(!$META_CUST){?>
        <meta property="og:title" content="<?=$CP_COMPANY2?>" />
        <meta property="og:url" content="<?=$DOMAIN?>" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="http://<?=$_SERVER["HTTP_HOST"]?>/new/public/partner/<?=$CP_LOGO?>" />
        <meta property="og:description" content="<?=str_replace("이룸투어",$CP_COMPANY,$DESCRIPTION)?>" />

        <meta name="keywords" content="<?=$CP_COMPANY2?>" />
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="<?=$CP_COMPANY2?>" />
        <meta name="twitter:url" content="http://<?=$_SERVER["HTTP_HOST"]?>" />
        <meta name="twitter:image" content="http://<?=$_SERVER["HTTP_HOST"]?>/new/public/partner/<?=$CP_LOGO?>" />
        <meta name="twitter:description" content="<?=str_replace("이룸투어",$CP_COMPANY,$DESCRIPTION)?>" />
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

        <meta name="keywords" content="<?=$SITE_NAME?>" />
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="<?=$SITE_NAME?>" />
        <meta name="twitter:url" content="<?=$DOMAIN?>" />
        <meta name="twitter:image" content="<?=$DOMAIN?>/renew/images/common/top_logo.gif" />
        <meta name="twitter:description" content="<?=$DESCRIPTION?>" />
    <?}?>
<?}?>


<?if($CID){?>
<?if($CP_ICO){?>
<link rel="shortcut icon" href="<?=$DOMAIN?>/new/public/partner/<?=$CP_ICO?>">
<link rel="apple-touch-icon" href="<?=$DOMAIN?>/new/public/partner/<?=$CP_ICO?>" />
<?}?>
<?}else{?>
<link rel="shortcut icon" href="<?=$DOMAIN?>/renew/favicon.ico">
<link rel="apple-touch-icon" href="<?=$DOMAIN?>/renew/favicon.ico" />
<?}?>

<!-- <script src="https://code.jquery.com/jquery-latest.js" type="text/javascript"></script> -->
<script type="text/javascript" src="../renew/include/jquery.min.js"></script>

<script type="text/javascript" src="../renew/include/jquery.easing.min.js"></script>
<script type="text/javascript" src="../renew/include/jquery.easing.compatibility.js"></script>


<link href="./css/import.css?ver=<?=time()?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../renew/include/form_check.js"></script>
<script type="text/javascript" src="../renew/include/function.js"></script>


<script src="../renew/jquery.bxSlider/jquery.bxslider.min.js" type="text/javascript"></script>


<script type="text/javascript">
$(function(){
    <?if($code1==16){?>
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');       
    <?}?>
    <?if($code1==31){?>
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');       
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');       
    <?}?>
    <?if(SELF=="request_main.html"){?>
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');       
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');       
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');    
    $(".swiper-button-next").last().trigger('click');       
    <?}?>    
});    
</script>




<!-- 모달레이어팝업 기존-->
<link rel="stylesheet" href="css/magnific-popup.css">
<script src="css/jquery.magnific-popup.min.js"></script>

<script type="text/javascript" src="/renew/include/jquery.maskedinput.min.js"></script>


<!--새로추가-->
<meta name="apple-mobile-web-app-capable" content="yes" /><!-- iPhone app prefs -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" /><!-- iPhone app prefs -->
<!-- <script src="//code.jquery.com/jquery-1.12.4.js"></script> -->
<script src="../renew/include/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script><!-- 상단메뉴 슬라이드 관련 스크립트-->
<script type="text/javascript" src="js/webpublisher.js"></script>	<!-- 전체메뉴 관련 제이쿼리 -->
<script type='text/javascript' src='js/script.js?v=1'></script>
<script type="text/javascript" src="js/TweenMax.min.js" ></script>
<script type="text/javascript" src="js/slick.js"></script>
<script type="text/javascript" src="js/swiper.min.js"></script>
<script type="text/javascript" src="js/jquery.sticky.js"></script>
<link rel="stylesheet" href="../renew/include/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="js/slick.css" />
<link rel="stylesheet" type="text/css" href="js/swiper.min.css"><!-- 상단메뉴 슬라이드 관련 스크립트-->
<script type="text/javascript" src="js/jquery.bxslider.js"></script> <!-- 베스트추천여행 관련 JS-->

<!-- 모달레이어팝업 -->
<link rel="stylesheet" href="js/magnific-popup.css" />
<script type="text/javascript" src="js/jquery.magnific-popup.min.js"></script>




<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TXLDM3T');</script>
<!-- End Google Tag Manager -->