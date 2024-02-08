<?php /***MiniDB***/
/*
 filename : class.oracle.php (ver 0.2.2p3)
 email	  : hwooky@phpclass.com
 homepage : www.phpclass.com
 author   : hwooky
 environment : PHP3, PHP4
*/

class baseStaticOracle {
	var $Classname = "baseStaticOracle";
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
					@ora_logoff($this->Conn["id"]);
				$this->Conn = array("host"=>$host, "user"=>$user, "pass"=>$pass, "connect"=>($connect ? $connect : "pconnect"));
				if ("localConn" != strtolower($host))
					$user .= "@".$base;
				$connect = "ora_".(("pconnect"==$this->Conn["connect"]) ? "plogon" : "logon");
				$this->Conn["id"] = $connect($user, $pass);
				$this->Base = $base;
				ora_commiton($this->Conn["id"]);
			}
		}
		return $this->Conn["id"];
	}

	function close() {
		if ($this->Conn["id"] && "connect"==$this->Conn["connect"])
			@ora_logoff($this->Conn["id"]);
	}

	function free($rid) {
		if ($rid) {
			@ora_close($rid);
			unset($this->RIDS[$rid]);
		}
	}

	function query($rid, $query) {
		$this->free($rid);
		if ($rid=@ora_open($this->Conn["id"])) {
			if (!@ora_parse($rid, $query) || !@ora_exec($rid)) {
				@ora_close($rid);
				return false;
			}
			$this->RIDS[$rid] = true;
		}
		return $rid;
	}
}

$baseStaticOracle = new baseStaticOracle;

class baseOracle {
	var $RID = false; // Result ID(Cursor Index)
	var $LID = false; // Link ID

	function baseOracle($host="", $user="", $pass="", $base="", $connect="") {
		if (is_array($host))
			extract($host);
		$this->LID = $GLOBALS["baseStaticOracle"]->connect($host, $user, $pass, $base, $connect);
	}
 
	function prvNumRows($query) {
		if (eregi("^[[:space:]]*SELECT[[:space:]]", $query)) {
			$from_pos = strpos(strtoupper($query),"FROM");
			$q = "SELECT count(*) ". substr($query, $from_pos);
			$curs = ora_open($this->LID);
			ora_parse($curs, $q);
			ora_exec($curs);
			ora_fetch($curs);
			$numrows = ora_getcolumn($curs, 0);
			ora_close($curs);
			return($numrows);
		} else
			return @ora_numrows($this->RID);
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
		return $this->RID=$GLOBALS["baseStaticOracle"]->query($this->RID, $query);
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
			return array($this->prvNumRows($query), @ora_numcols($this->RID));
		return false;
	}

	function next_record() {
		if (ora_fetch($this->RID))
			for ($i=0; $i<ora_numcols($this->RID); $i++) {
				$col = strtolower(ora_columnname($this->RID, $i));
				$record[$col] = ora_getcolumn($this->RID, $i);
			}
		return $record;
	}
}

if (!$baseFlag) { 
	$baseFlag = true; 

	class MiniDB extends baseOracle {
		function MiniDB($host="", $user="", $pass="", $base="", $connect="") {
			$this->baseOracle($host, $user, $pass, $base, $connect);
		}
	}
}
?>