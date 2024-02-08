<?php /***MiniDB***/
/*
 filename : class.pgsql.php (ver 0.2.2p3)
 email	  : hwooky@phpclass.com
 homepage : www.phpclass.com
 author   : hwooky
 environment : PHP3, PHP4 <= 4.1.0
*/

class baseStaticPgsql {
	var $Classname = "baseStaticPgsql";
	var $RIDS = array(); // Result IDS
	var $Conn = array(); // connection information
	var $Base;

	function connect($host, $user, $pass, $base, $connect) {
		if ("$host$user$pass$base$connect") {
			if ($host && ""=="$user$pass$base$connect") {
				$base = $host;
				extract($this->Conn);
			}
			if ("$host$user$pass$base" != $this->Conn["host"].$this->Conn["user"].$this->Conn["pass"].$this->Base) {
				if ($this->Conn["id"] && "connect"==$this->Conn["connect"])
					@pg_close($this->Conn["id"]);
				$this->Conn = array("host"=>$host, "user"=>$user, "pass"=>$pass, "connect"=>($connect ? $connect : "pconnect"));
				$connect = "pg_".$this->Conn["connect"];
				$this->Conn["id"] = $connect("user=$user dbname=$base");
				$this->Base = $base;
			}
		}
		return $this->Conn["id"];
	}

	function close() {
		if ($this->Conn["id"] && "connect"==$this->Conn["connect"])
			@pg_close($this->Conn["id"]);
	}

	function free($rid) {
		if ($rid) {
			@pg_freeresult($rid);
			unset($this->RIDS[$rid]);
		}
	}

	function query($rid, $query) {
		$this->free($rid);
		if ($rid=@pg_exec($this->Conn["id"], $query))
			$this->RIDS[$rid] = true;
		return $rid;
	}
}

$baseStaticPgsql = new baseStaticPgsql;

class basePgsql {
	var $RID = false; // Result ID
	var $LID = false; // Link ID
	var $Row = 0;

	function basePgsql($host="", $user="", $pass="", $base="", $connect="") {
		if (is_array($host))
			extract($host);
		$this->LID = $GLOBALS["baseStaticPgsql"]->connect($host, $user, $pass, $base, $connect);
	}

	function prvNumRows($query) {
		if (eregi("^[[:space:]]*SELECT[[:space:]]", $query))
			return @pg_numrows($this->RID);
		else
			return @pg_cmdtuples($this->RID);
	}
  
	function prvUnsetNumberIndex($field) {
		while(list($key,$val)=each($field))
			if (!ereg("[^0-9]", $key))
				unset($field[$key]);
		return $field;
	}

	function query_replace($table, $field_keys, $field_values) {
		$ws = $field_keys[0]."=".$field_values[0];
		if ($this->prvQuery("SELECT * FROM $table WHERE $ws"))
			if (!($this->prvQuery("DELETE FROM $table WHERE $ws")))
				return false;
		$query = sprintf("INSERT INTO $table (%s) VALUES (%s)",
			implode(",", $field_keys), 
			implode(",", $field_values)
		);
		return $this->query($query);
	}

	function prvQuery($query) {
		return $this->RID=$GLOBALS["baseStaticPgsql"]->query($this->RID, $query);
	}

	function query($query) {
		if (eregi("^[[:space:]]*REPLACE[[:space:]]", $query)) {
			$table = strtok(eregi_replace("^[[:space:]]*REPLACE[[:space:]]+INTO[[:space:]]+", "", $query), " ");
			strtok("(");
			$keystr = strtok(")");
			strtok("(");
			$valstr = strtok(")");
			return $this->query_replace($table, explode(",", $keystr), explode(",", $valstr));
		}
		if ($this->prvQuery($query)) {
			$this->Row = 0;
			return array($this->prvNumRows($query), @pg_numfields($this->RID));
		}
		return false;
	}

	function next_record() {
		if ($this->Row >= pg_numrows($this->RID))
			return false;
		if (is_array($record=@pg_fetch_array($this->RID, $this->Row++)))
			$record = $this->prvUnsetNumberIndex($record);
		return $record;
	}
}

if (!$baseFlag) { 
	$baseFlag = true; 

	class MiniDB extends basePgsql {
		function MiniDB($host="", $user="", $pass="", $base="", $connect="") {
			$this->basePgsql($host, $user, $pass, $base, $connect);
		}
	}
}
?>