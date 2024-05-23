<meta name="naver-site-verification" content="64429b940055930286923f0441a15462cdf37275" />
<meta name="naver-site-verification" content="1413429c8119d10a628d09877f63bef607c878a1" />
<meta name="google-site-verification" content="edEva6A5vIGNm4rcT1-8cTM1e2MtV25reD5-oN7ofq4" />
<meta name="viewport" content="width=1400">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

<link rel="shortcut icon" href="<?=$DOMAIN?>/renew/favicon.ico">
<link rel="apple-touch-icon" href="<?=$DOMAIN?>/renew/favicon.ico" />

<script type="text/javascript" src="./include/jquery.min.js"></script>
<link type="text/css" rel="stylesheet" href="css/import_index.css"/>



<script>
$( function() {

    $(".side_quick").hide();
    $(window).scroll(function(){
        var scroll_top = $(document).scrollTop();
        $("#position_scroll").text(scroll_top);
        //console.log(scroll_top);
        //$("#pop_<?=$code?>").css("top",scroll_top+"px");

        /*right banner*/
        <?if(strstr(SELF,"detailview")){?>
        if(scroll_top>=100){$(".side_quick").show();}
        else{$(".side_quick").hide();}
        <?}elseif(strstr(SELF,"sub")){?>
        if(scroll_top>=600){$(".side_quick").show();}
        else{$(".side_quick").hide();}
        <?}else{?>
        if(scroll_top>=500){$(".side_quick").show();}
        else{$(".side_quick").hide();}
        <?}?>

    });

});
</script>
<!--검색엔진 관련-->
<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "Person",
        "name": "이룸투어",
        "url": "https://irumtour.net/",
        "sameAs": [
            "https://youtube.com/@irum-tour?si=MJfgKUn9ZORK2bce",
            "http://blog.naver.com/irumplace",
            "https://band.us/@irumtour"
        ]
    }
</script>
<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "ItemList",
        "itemListElement":[
            {
                "@type": "ListItem",
                "item": {
                    "@type": "Organization",
                    "name": "한달살기골프",
                    "image": "https://irumtour.net/new//public/goods/2404/66504_1.jpg?timestamp=1716350108405",
                    "url": "https://irumtour.net/renew/sublist.html?code1=14&code2=153"
                },
                "position": "1"
            },
            {
                "@type": "ListItem",
                "item": {
                    "@type": "Organization",
                    "name": "특가골프",
                    "image": "https://irumtour.net/new//public/goods/2402/CP64893_1.jpg?timestamp=1716350174139",
                    "url": "https://irumtour.net/renew/sublist.html?code1=16&code2=148#ptop"
                },
                "position": "2"
            },
            {
                "@type": "ListItem",
                "item": {
                    "@type": "Organization",
                    "name": "연휴 골프",
                    "image": "https://irumtour.net/renew/hpimg/hdgf.jpg",
                    "url": "https://irumtour.net/renew/sublist.html?code1=31&code2=149"
                },
                "position": "3"
            },
            {
                "@type": "ListItem",
                "item": {
                    "@type": "Organization",
                    "name": "2인 골프",
                    "image": "https://irumtour.net/renew/hpimg/two.png",
                    "url": "https://irumtour.net/renew/sublist.html?code1=14&code2=135"
                },
                "position": "4"
            },
            {
                "@type": "ListItem",
                "item": {
                    "@type": "Organization",
                    "name": "프리미엄 골프",
                    "image": "https://irumtour.net/renew/hpimg/premium.jpeg",
                    "url": "https://irumtour.net/renew/sublist.html?code1=15&code2=90"
                },
                "position": "5"
            },
            {
                "@type": "ListItem",
                "item": {
                    "@type": "Organization",
                    "name": "답사후기",
                    "image": "https://irumtour.net/renew/hpimg/irum.jpg",
                    "url": "https://irumtour.net/renew/review.html?code1=%EC%9D%BC%EB%B3%B8"
                },
                "position": "6"
            }
        ]
    }
</script>

<?if(!strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"mobile")){?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-128508586-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-128508586-1');
</script>


<!-- Global site tag (gtag.js) - Google Ads: 795858133 --> 
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-795858133"></script> 
<script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'AW-795858133'); </script>

<!-- Event snippet for 견적문의 conversion page In your html page, add the snippet and call gtag_report_conversion when someone clicks on the chosen link or button. --> 
<script> function gtag_report_conversion(url) { var callback = function () { if (typeof(url) != 'undefined') { window.location = url; } }; gtag('event', 'conversion', { 'send_to': 'AW-795858133/HO69CPeo24YBENWpv_sC', 'event_callback': callback }); return false; } </script>


<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TXLDM3T');</script>
<!-- End Google Tag Manager -->
<?}?>




