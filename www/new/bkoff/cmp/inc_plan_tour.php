<?

//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($tour_days1,$TOUR_INFO[1][0]);}

			$txt_modify = nl2br($txt2);

			if(!strstr($txt_modify,"<br />")){
				$txt_modify = $txt_modify."<br /><div></div>";	
				$txt2.="<br/>";
			}

			$txt_modify_arr = explode("<br />",$txt_modify);

			//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){echo "xxxxx".$txt_modify. "xxxxxxx";}
		
			while(list($key,$val)=each($txt_modify_arr)){
				$val=str_replace("\n","",$val);
				$val=str_replace("&nbsp;","",$val);
				$val2=str_replace(" ","",$val);
				if($val2){
					$txt_modify_last = $val;	
				}

			}

			//$txt1  = str_replace($txt_modify_last,"",$txt1);
			$txt2  = str_replace($txt_modify_last,"",$txt2);

			if($plan_type=="I"){

				$txt2  = $txt_modify_arr[0];
				
				$txt_modify_last = "";

				for($for1daytour_row=1;$for1daytour_row<count($txt_modify_arr);$for1daytour_row++){
					$txt_modify_last  .= "<br/>".$txt_modify_arr[$for1daytour_row];
				}
				$txt_modify_last = substr($txt_modify_last,5);

			}	

			if(strstr($tour_days1,",${tour_no},") && $TOUR_INFO[1][0]){
				//$txt1 .="\n\n" . $TOUR_INFO[1][0];
				//$txt1 .="\n" . $TOUR_INFO[1][1];
				$txt2 .=$TOUR_INFO[1][0];
				$txt2 .= "<br/>".nl2br($TOUR_INFO[1][1]);
				
				$txt2 .="<div style='margin-bottom:5px'>";
				if($TOUR_INFO[1][2]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[1][2] . "' width='180' height='130' style='margin-right:5px'>";
				if($TOUR_INFO[1][3]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[1][3] . "' width='180' height='130'>";
				$txt2 .="</div>";	
			}

			if(strstr($tour_days2,",${tour_no},") && $TOUR_INFO[2][0]){
				//$txt1 .="\n\n" . $TOUR_INFO[2][0];
				//$txt1 .="\n" . $TOUR_INFO[2][1];
				$txt2 .=$TOUR_INFO[2][0];
				$txt2 .= "<br/>".nl2br($TOUR_INFO[2][1]);
				
				$txt2 .="<div style='margin-bottom:5px'>";
				if($TOUR_INFO[2][2]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[2][2] . "' width='180' height='130' style='margin-right:5px'>";
				if($TOUR_INFO[2][3]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[2][3] . "' width='180' height='130'>";
				$txt2 .="</div>";	
			}

			if(strstr($tour_days3,",${tour_no},") && $TOUR_INFO[3][0]){
				//$txt1 .="\n\n" . $TOUR_INFO[3][0];
				//$txt1 .="\n" . $TOUR_INFO[3][1];
				$txt2 .=$TOUR_INFO[3][0];
				$txt2 .= "<br/>".nl2br($TOUR_INFO[3][1]);
				
				$txt2 .="<div style='margin-bottom:5px'>";
				if($TOUR_INFO[3][2]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[3][2] . "' width='180' height='130' style='margin-right:5px'>";
				if($TOUR_INFO[3][3]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[3][3] . "' width='180' height='130'>";
				$txt2 .="</div>";	
			}

			if(strstr($tour_days4,",${tour_no},") && $TOUR_INFO[4][0]){
				//$txt1 .="\n\n" . $TOUR_INFO[4][0];
				//$txt1 .="\n" . $TOUR_INFO[4][1];
				$txt2 .=$TOUR_INFO[4][0];
				$txt2 .= "<br/>".nl2br($TOUR_INFO[4][1]);
				
				$txt2 .="<div style='margin-bottom:5px'>";
				if($TOUR_INFO[4][2]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[4][2] . "' width='180' height='130' style='margin-right:5px'>";
				if($TOUR_INFO[4][3]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[4][3] . "' width='180' height='130'>";
				$txt2 .="</div>";	
			}

			if(strstr($tour_days5,",${tour_no},") && $TOUR_INFO[5][0]){
				//$txt1 .="\n\n" . $TOUR_INFO[5][0];
				//$txt1 .="\n" . $TOUR_INFO[5][1];
				$txt2 .=$TOUR_INFO[5][0];
				$txt2 .= "<br/>".nl2br($TOUR_INFO[5][1]);
				
				$txt2 .="<div style='margin-bottom:5px'>";
				if($TOUR_INFO[5][2]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[5][2] . "' width='180' height='130' style='margin-right:5px'>";
				if($TOUR_INFO[5][3]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[5][3] . "' width='180' height='130'>";
				$txt2 .="</div>";	
			}

			if(strstr($tour_days6,",${tour_no},") && $TOUR_INFO[6][0]){
				//$txt1 .="\n\n" . $TOUR_INFO[6][0];
				//$txt1 .="\n" . $TOUR_INFO[6][1];
				$txt2 .=$TOUR_INFO[6][0];
				$txt2 .= "<br/>".nl2br($TOUR_INFO[6][1]);
				
				$txt2 .="<div style='margin-bottom:5px'>";
				if($TOUR_INFO[6][2]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[6][2] . "' width='180' height='130' style='margin-right:5px'>";
				if($TOUR_INFO[6][3]) $txt2 .= "<img src='http://irumtour.net/new/public/cmp/" . $TOUR_INFO[6][3] . "' width='180' height='130'>";
				$txt2 .="</div>";	
			}		


			//$txt1  = $txt1 ."\n". $txt_modify_last;
			$txt2  = $txt2 ."<div>".  $txt_modify_last . "</div>";

?>