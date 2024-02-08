<?
include_once("../include/common_file.php");
set_time_limit(0);

exit;


$sql = "select * from cmp_reservation";
$dbo->query($sql);
while($rs=$dbo->next_record()){

	$sql2 = "
		update cmp_reservation set
			price=(select sum(price) from cmp_people where code=$rs[code] and bit=1),
			price_last = (select sum(price)-price_customer_input from cmp_people where code=$rs[code] and bit=1),
			fee = (select sum(price-(price_air+price_land)) from cmp_people where code=$rs[code] and bit=1)
		where code=$rs[code]";

	$dbo2->query($sql2);
	if(mysql_error()) checkVar(mysql_error(),$sql2);


}
?>

fin.