<?
//include_once("script/include_common_file.php");


// $dir = "./";
 
// if (is_dir($dir)){                              
//   if ($dh = opendir($dir)){                     
//     while (($file = readdir($dh)) !== false){   
//         if(strstr($file,".html")){
//             echo "
//             <loc>".$DOMAIN."/renew/".$file . "</loc>
//             <lastmod>2021-05-14T10:00:54+09:00</lastmod>
//             ";        
//         }
//     }                                           
//     closedir($dh);                              
//   }                                             
// }  


// include "./sitemap-generator.php";
// $config = include("./sitemap-config.php");
// $smg = new SitemapGenerator($config);
// // Run the generator
// $smg->GenerateSitemap();
?>
<?php 
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

$https = ($_SERVER['HTTPS']=='on')? "https":"http";
$DOMAIN = $https . "://" . $_SERVER['HTTP_HOST'];

// 기본 사이트 정의
$lastBuildDate =  date('c');// 사이트 최종 수정일 (date 포맷은 ISO 8601 date 으로 2023-06-14T02:11:34+09:00 형태로 표기된다.)
echo '
    <url>
        <loc>'.$DOMAIN.'</loc>
        <lastmod>'.$lastBuildDate.'</lastmod>
        <priority>1.0</priority>
    </url>
';


echo '</urlset>';

?>