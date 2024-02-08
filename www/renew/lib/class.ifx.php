<?php /***MiniDB***/
/*
 filename : class.ifx.php (ver 0.2.2p3)
 email	  : hwooky@phpclass.com
 homepage : www.phpclass.com
 author   : hwooky
 environment : PHP3, PHP4
*/

class baseStaticIfx {
	var $Classname = "baseStaticIfx";
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
					@ifx_close($this->Conn["id"]);
				$this->Conn = array("host"=>$host, "user"=>$user, "pass"=>$pass, "connect"=>($connect ? $connect : "pconnect"));
				$connect = "ifx_".$this->Conn["connect"];
				$this->Conn["id"] = $connect(($host ? "$base@$host" : $base), $user, $pass);
				$this->Base = $base;
			}
		}
		return $this->Conn["id"];
	}

	function close() {
		if ($this->Conn["id"] && "connect"==$this->Conn["connect"])
			@ifx_close($this->Conn["id"]);
	}

	function free($rid) {
		if ($rid) {
			@ifx_free_result($rid);
			unset($this->RIDS[$rid]);
		}
	}

	function query($rid, $query) {
		$this->free($rid);
		if ($rid=@ifx_query($query, $this->Conn["id"]))
			$this->RIDS[$rid] = true;
		return $rid;
	}
}

$baseStaticIfx = new baseStaticIfx;

class baseIfx {
	var $RID = false; // Result ID
	var $LID = false; // Link ID

	function baseIfx($host="", $user="", $pass="", $base="", $connect="") {
		if (is_array($host))
			extract($host);
		$this->LID = $GLOBALS["baseStaticIfx"]->connect($host, $user, $pass, $base, $connect);
	}

	function prvNumRows($query) { 
		if (eregi("^[[:space:]]*SELECT[[:space:]]", $query)) { 
			$from_pos = strpos(strtoupper($query),"FROM"); 
			$q = "SELECT count(*) ". substr($query, $from_pos); 
			$rid = @ifx_query($q, $this->LID); 
			$record = @ifx_fetch_row($rid); 
			@ifx_free_result($rid); 
			return $record['(count)']; 
		} else 
			return @ifx_affected_rows($this->RID); 
	} 

	function prvUnsetNumberIndex(&$field) {
		while(list($key,$val)=each($field))
			if (!ereg("[^0-9]", $key))
				unset($field[$key]);
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
		return $this->RID=$GLOBALS["baseStaticIfx"]->query($this->RID, $query);
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
		if ($this->prvQuery($query))
			return array($this->prvNumRows($query), @ifx_num_fields($this->RID));
		return false;
	}

	function next_record() {
		if (is_array($record=@ifx_fetch_row($this->RID)))
			$this->prvUnsetNumberIndex($record);
		return $record;
	}
}

if (!$baseFlag) { 
	$baseFlag = true;

	class MiniDB extends baseIfx {
		function MiniDB($host="", $user="", $pass="", $base="", $connect="") {
			$this->baseIfx($host, $user, $pass, $base, $connect);
		}
	}
}
?>