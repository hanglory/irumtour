<?php /***MiniDB***/
/*
 filename : class.mysql.php (ver 0.2.2p3)
 email	  : hwooky@phpclass.com
 homepage : www.phpclass.com
 author   : hwooky
 environment : PHP3, PHP4
*/

class baseStaticMysql {
	var $Classname = "baseStaticMysql";
	var $RIDS = array(); // Result IDS
	var $Conn = array(); // connection information
	var $Base;

	function connect($host, $user, $pass, $base, $connect) {
		if ("$host$user$pass$base$connect") {
			if ($host && ""=="$user$pass$base$connect") {
				$base = $host;
				extract($this->Conn);
			}
			if ("$host$user$pass" != $this->Conn["host"].$this->Conn["user"].$this->Conn["pass"]) {
				if ($this->Conn["id"] && "connect"==$this->Conn["connect"])
					@mysql_close($this->Conn["id"]);
				$this->Conn = array("host"=>$host, "user"=>$user, "pass"=>$pass, "connect"=>($connect ? $connect : "pconnect"));
				$connect = "mysql_".$this->Conn["connect"];
				@mysql_select_db($base, $this->Conn["id"]=$connect($host,$user,$pass));
				$this->Base = $base;
			}
			if ($base != $this->Base) {
				mysql_select_db($base, $this->Conn["id"]);
				$this->Base = $base;
			}
		}
		return $this->Conn["id"];
	}

	function close() {
		if ($this->Conn["id"] && "connect"==$this->Conn["connect"])
			@mysql_close($this->Conn["id"]);
	}

	function free($rid) {
		if ($rid) {
			@mysql_free_result($rid);
			unset($this->RIDS[$rid]);
		}
	}

	function query($rid, $query) {
		$this->free($rid);
		if ($rid=@mysql_query($query, $this->Conn["id"]))
			$this->RIDS[$rid] = true;
		return $rid;
	}
}

$baseStaticMysql = new baseStaticMysql;

class baseMysql {
	var $RID = false; // Result ID
	var $LID = false; // Link ID

	function baseMysql($host="", $user="", $pass="", $base="", $connect="") {
		if (is_array($host))
			extract($host);
		$this->LID = $GLOBALS["baseStaticMysql"]->connect($host, $user, $pass, $base, $connect);
	}

	function prvNumRows($query) {
		if (eregi("^[[:space:]]*SELECT[[:space:]]", $query))
			return @mysql_num_rows($this->RID);
		else
			return @mysql_affected_rows($this->LID);
	}

	function prvUnsetNumberIndex(&$field) {
		while(list($key,$val)=each($field))
			if (!ereg("[^0-9]", $key))
				unset($field[$key]);
	}

	function query_replace($table, $field_keys, $field_values) {
		$query = sprintf("REPLACE INTO $table (%s) VALUES (%s)",
			implode(",", $field_keys), 
			implode(",", $field_values)
		);
		return $this->query($query);
	}

	function prvQuery($query) {
		return $this->RID=$GLOBALS["baseStaticMysql"]->query($this->RID, $query);
	}

	function query($query) {
		if (eregi("^[[:space:]]*DELETE[[:space:]]", $query)) {
			$pos = strpos(strtoupper($query),"FROM");
			if (!eregi("WHERE", $q="SELECT count(*) ". substr($query, $pos))) {
				if (!($rid=@mysql_query($q, $this->LID)))
					return false;
				list($rows) = @mysql_fetch_array($rid);
				@mysql_free_result($rid);
			}
		}
		if ($this->prvQuery($query))
			return array($rows ? $rows : $this->prvNumRows($query), @mysql_num_fields($this->RID));
		return false;
	}

	function next_record() {
		if (is_array($record=@mysql_fetch_array($this->RID)))
			$this->prvUnsetNumberIndex($record);
		return $record;
	}
}

if (!$baseFlag) { 
	$baseFlag = true;

	class MiniDB extends baseMysql {
		function MiniDB($host="", $user="", $pass="", $base="", $connect="") {
			$this->baseMysql($host, $user, $pass, $base, $connect);
		}
	}
}
?>