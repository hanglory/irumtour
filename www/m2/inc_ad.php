
<?if(strstr("@1xxxxxxxxxxxx4.37.242.84@xxxxxxxx221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){?>
<?if(!$CID){?>
<!-- Channel Plugin Scripts -->
<script defer>
  (function() {
    var w = window;
    if (w.ChannelIO) {
      return (window.console.error || window.console.log || function(){})('ChannelIO script included twice.');
    }
    var ch = function() {
      ch.c(arguments);
    };
    ch.q = [];
    ch.c = function(args) {
      ch.q.push(args);
    };
    w.ChannelIO = ch;
    function l() {
      if (w.ChannelIOInitialized) {
        return;
      }
      w.ChannelIOInitialized = true;
      var s = document.createElement('script');
      s.type = 'text/javascript';
      s.async = true;
      s.src = 'https://cdn.channel.io/plugin/ch-plugin-web.js';
      s.charset = 'UTF-8';
      var x = document.getElementsByTagName('script')[0];
      x.parentNode.insertBefore(s, x);
    }
    if (document.readyState === 'complete') {
      l();
    } else if (window.attachEvent) {
      window.attachEvent('onload', l);
    } else {
      window.addEventListener('DOMContentLoaded', l, false);
      window.addEventListener('load', l, false);
    }
  })();
  ChannelIO('boot', {
    "pluginKey": "a996a533-b90b-4ee3-af3d-2f335a187475"
  });
</script>
<!-- End Channel Plugin -->
<?}?>
<?}?>



<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-G06ME2NFBT"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-G06ME2NFBT');
</script>




<!-- NAVER SCRIPT -->
<script type="text/javascript" src="//wcs.naver.net/wcslog.js" defer></script> 
<script type="text/javascript"> 
if (!wcs_add) var wcs_add={};
wcs_add["wa"] = "s_1a8045f4534a";
if (!_nasa) var _nasa={};
wcs.inflow("irumtour.net");
wcs_do(_nasa);
</script>

<!-- Mirae Log Analysis Script Ver 1.0   -->
<script TYPE="text/javascript" defer>
var mi_adkey = "olxfm";
var mi_is_defender = "";
var mi_dt=new Date(),mi_y=mi_dt.getFullYear(),mi_m=mi_dt.getMonth()+1,mi_d=mi_dt.getDate(),mi_h=mi_dt.getHours();
var mi_date=""+mi_y+(mi_m<=9?"0":"")+mi_m+(mi_d<=9?"0":"")+mi_d+(mi_h<=9?"0":"")+mi_h;
var mi_script = "<scr"+"ipt "+"type='text/javascr"+"ipt' src='//log1.toup.net/mirae_log.js?t="+mi_date+"' async='true'></scr"+"ipt>"; 
document.writeln(mi_script);
</script>
<!-- Mirae Log Analysis Script END  -->