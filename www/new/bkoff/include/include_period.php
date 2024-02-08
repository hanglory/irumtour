	<input type="button" class="button" value="오늘" onclick="setDateData('<?=date("Y/m/d")?>','<?=date("Y/m/d")?>')">
	<input type="button" class="button" value="일주일" onclick="setDateData('<?=date("Y/m/d",mktime(0,0,0,date("m"),date("d")-6,date("Y")))?>','<?=date("Y/m/d")?>')">
	<input type="button" class="button" value="15일" onclick="setDateData('<?=date("Y/m/d",mktime(0,0,0,date("m"),date("d")-15,date("Y")))?>','<?=date("Y/m/d")?>')">
	<input type="button" class="button" value="한달" onclick="setDateData('<?=date("Y/m/d",mktime(0,0,0,date("m")-1,date("d"),date("Y")))?>','<?=date("Y/m/d")?>')">
