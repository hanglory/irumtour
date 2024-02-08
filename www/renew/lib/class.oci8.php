<?php /***MiniDB***/
/*
 filename : class.oci8.php (ver 0.2.2p3)
 email	  : hwooky@phpclass.com
 homepage : www.phpclass.com
 author   : hwooky
 environment : PHP3, PHP4
*/

class baseStaticOci8 {
	var $Classname = "baseStaticOci8";
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
					@OCIlogoff($this->Conn["id"]);
				$this->Conn = array("host"=>$host, "user"=>$user, "pass"=>$pass, "connect"=>($connect ? $connect : "pconnect"));
				$connect = "OCI".(("pconnect"==$this->Conn["connect"]) ? "plogon" : "logon");
				$this->Conn["id"] = $connect($user, $pass, $base);
				$this->Base = $base;
			}
		}
		return $this->Conn["id"];
	}

	function close() {
		if ($this->Conn["id"] && "connect"==$this->Conn["connect"])
			@OCIlogoff($this->Conn["id"]);
	}

	function free($rid) {
		if ($rid) {
			@OCIfreestatement($rid);
			unset($this->RIDS[$rid]);
		}
	}

	function query($rid, $query) {
		$this->free($rid);
		if ($rid=@OCIparse($this->Conn["id"], $query)) {
			if (!@OCIexecute($rid)) {
				@OCIfreestatement($rid);
				return false;
			}
			$this->RIDS[$rid] = true;
		}
		return $rid;
	}
}

$baseStaticOci8 = new baseStaticOci8;

class baseOci8 {
	var $RID = false; // Result ID(Statement)
	var $LID = false; // Link ID

	function baseOci8($host="", $user="", $pass="", $base="", $connect="") {
		if (is_array($host))
			extract($host);
		$this->LID = $GLOBALS["baseStaticOci8"]->connect($host, $user, $pass, $base, $connect);
	}

	function prvNumRows($query) {
		if (eregi("^[[:space:]]*SELECT[[:space:]]", $query)) {
			$from_pos = strpos(strtoupper($query),"FROM");
			$q = "SELECT count(*) ". substr($query, $from_pos);
			$stmt = OCIparse($this->LID, $q);
			OCIexecute($stmt);
			OCIfetch($stmt);
			return OCIresult($stmt, 1);
		} else
			return @OCIrowcount($this->RID);
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
		return $this->RID=$GLOBALS["baseStaticOci8"]->query($this->RID, $query);
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
			return array($this->prvNumRows($query), @OCInumcols($this->RID));
		return false;
	}

	function next_record() {
		if (OCIfetchinto($this->RID, $result, OCI_ASSOC+OCI_RETURN_NULLS))
			while (list($k,$v)=each($result))
				$record[strtolower($k)] = $v;
		return $record;
	}
}

if (!$baseFlag) { 
	$baseFlag = true; 

	class MiniDB extends baseOci8 {
		function MiniDB($host="", $user="", $pass="", $base="", $connect="") {
			$this->baseOci8($host, $user, $pass, $base, $connect);
		}
	}
}
?>