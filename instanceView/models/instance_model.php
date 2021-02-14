<?php

class Instance_model extends CI_Model
{
	public function getConnection()
	{
		$this->connect();

		return $this->connection;
	}
	
	public function connect()
	{
		if(empty($this->connection))
		{
			$this->connection = $this->load->database("account", true);
		}
	}
	public function getLasInstance($realmConnection)
	{
		$db = $this->realms->getRealm($realmConnection)->getCharacters()->getConnection();
		$query = $db->query("SELECT DISTINCT instance FROM character_instance WHERE permanent = 1 ORDER BY instance DESC LIMIT 5");
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			$array = array();
			foreach($result as $value => $v)
			{
				$instance = $this->instance_model->getInstanceById($realmConnection, $result[$value]['instance']);
				$mapName = $this->instance_model->getNameMapById($realmConnection, $instance[0]['map']);
				$array[$value]['id'] = $result[$value]['instance'];
				$array[$value]['difficulty'] = $instance[0]['difficulty'];
				$array[$value]['name'] = $mapName;
			}
			return $array;
		}
		
		unset($query);
		
		return false;
	}
	public function getCharactersInstanceById($realmConnection, $id)
	{
		$db = $this->realms->getRealm($realmConnection)->getCharacters()->getConnection();
		$query = $db->query("SELECT * FROM character_instance WHERE instance = ?", array( $id ));
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result;
		}
		
		unset($query);
		
		return false;
	}
	public function getCharactersInfo($raid, $RealmId)
	{
		$realmObj = $this->realms->getRealm($RealmId);
		$index = 1;
		foreach($raid as $value => $v)
		{
			$raid[$value]['index'] = $index;
			$guid = $raid[$value]['guid'];
			if($realmObj->getCharacters()->getFaction($guid) == 1)
				$faction = "Alianza";
			else
				$faction = "Horda";
			$character = $this->instance_model->getCharacterByGuid($RealmId, $guid);
			$raid[$value]['account'] = $this->instance_model->getNameAccount($character[0]['account']);
			$raid[$value]['name'] = $character[0]['name'];
			$raid[$value]['level'] = $character[0]['level'];
			$raid[$value]['guild'] = $this->instance_model->getNameGuild($RealmId, $guid);
			$raid[$value]['faction'] = $faction;
			$index++;
		}
		return $raid;
	}
	public function getInstanceById($realmConnection, $id)
	{
		$db = $this->realms->getRealm($realmConnection)->getCharacters()->getConnection();
		$query = $db->query("SELECT id, map, difficulty, completedEncounters FROM instance WHERE id = ?", array( $id ));
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			if($result[0]['difficulty'] == 0)
				$result[0]['difficulty'] = "10 Normal";
			if($result[0]['difficulty'] == 1)
				$result[0]['difficulty'] = "25 Normal";
			if($result[0]['difficulty'] == 2)
				$result[0]['difficulty'] = "10 Heroico";
			if($result[0]['difficulty'] == 3)
				$result[0]['difficulty'] = "25 Heroico";
			return $result;
		}
		
		unset($query);
		
		return false;
	}
	public function getNameMapById($realmId, $idMap)
	{
		//Connect to the world database
		$world_database = $this->realms->getRealm($realmId)->getWorld();
		$world_database->connect();
		$queryWorld = $world_database->getConnection()->query("SELECT script FROM instance_template WHERE map = ?", array($idMap));

		if($queryWorld->num_rows() > 0)
		{
			$result = $queryWorld->result_array();
			$phrases = array('instance_', '_');
			$string = str_replace($phrases,' ', $result[0]['script']);
			$string = strtoupper($string);
			return $string;
		}
		
		unset($queryWorld);
		
		return "Desconocida";
	}
	public function getCharacterByGuid($Realm, $guid)
	{
		$db = $this->realms->getRealm($Realm)->getCharacters()->getConnection();
		$query = $db->query("SELECT account, name, level FROM characters WHERE guid = ?", array( $guid ));
		
		if($query->num_rows() > 0)
		{
			$row = $query->result_array();

			return $row;
		}
		
		unset($query);
		
		return false;
	}
	public function getNameGuild($Realm, $guid)
	{
		$db = $this->realms->getRealm($Realm)->getCharacters()->getConnection();
		$query = $db->query("SELECT guildid FROM guild_member WHERE guid = ?", array( $guid ));

		if($query->num_rows() > 0)
		{
			$row = $query->result_array();
			$db1 = $this->realms->getRealm($Realm)->getCharacters()->getConnection();
			$Nextquery = $db1->query("SELECT name FROM guild WHERE guildid = ?", array( $row[0]['guildid'] ));
			$nameGuild = $Nextquery->result_array();
			return $nameGuild[0]['name'];
		}
		unset($query);
		
		return "N/A";
	}
	
	public function getNpcByInstance($realmId, $instanceID)
	{
		$db = $this->realms->getRealm($realmId)->getCharacters()->getConnection();
		$query = $db->query("SELECT guid, respawnTime FROM creature_respawn WHERE instanceId = ?", array($instanceID));
		$ArrayElite = array();
		
		if($query->num_rows() > 0)
		{
			$row = $query->result_array();

			//Connect to the world database
			$world_database = $this->realms->getRealm($realmId)->getWorld();
			$world_database->connect();

			foreach($row as $value => $v)
			{
				$queryWorld = $world_database->getConnection()->query("SELECT id FROM creature WHERE guid = ?", array($row[$value]['guid']));
				$rowWorld = $queryWorld->row();
				$queryNpc = $world_database->getConnection()->query("SELECT * FROM creature_template WHERE entry = ?", array($rowWorld->id));
				$rowNpc = $queryNpc->row();
				$ArrayElite[$value]['id'] = $rowNpc->entry;
				$ArrayElite[$value]['name'] = $rowNpc->name;
				$ArrayElite[$value]['maxlevel'] = $rowNpc->maxlevel;
				$ArrayElite[$value]['rank'] = $rowNpc->rank;
			}

		asort($ArrayElite);
		return $ArrayElite;

		}

		unset($query);
		return false;
	}
	public function getNameByGuid($Realm, $guid)
	{
		$db = $this->realms->getRealm($Realm)->getCharacters()->getConnection();
		$query = $db->query("SELECT name FROM characters WHERE guid = ?", array( $guid ));
		
		if($query->num_rows() > 0)
		{
			$row = $query->result_array();

			return $row[0]['name'];
		}
		
		unset($query);
		
		return "Desconocido";
	}
	public function getNameAccount($id)
	{
		$this->connect();
		
		$this->connection->select("username")->from(table("account"))->where(array(column("account", "id") => $id));
		$query = $this->connection->get();
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result[0]['username'];

		}
			unset($query);
			return "Desconocido";
	}

}