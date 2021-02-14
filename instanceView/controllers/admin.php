<?php

class Admin extends MX_Controller
{	
	private $js;
	private $css;
	
	private $language;


	const MODULE_NAME			= 'instanceView';
	const MODULE_PATH 			= 'modules/instanceView';
	public function __construct()
	{
		// Make sure to load the administrator library!
		$this->load->library('administrator');
		$this->load->model("instance_model");
		
		parent::__construct();

		$this->load->config(self::MODULE_NAME . '/instance_config');
		
		// Format the language strings
		$this->language = $this->config->item('cta_language');

		requirePermission("canViewAdmin");
	}

	public function index()
	{
		// Change the title
		$this->administrator->setTitle("Instancia");

		// Prepare the compitable realms
		$CompitableRealms = array();
		foreach ($this->realms->getRealms() as $realmData)
		{
			$CompitableRealms[] = array('id' => $realmData->getId(), 'name' => $realmData->getName());
		}
		unset($realmData);

		// Get the first compitable realm
		$FirstRealm = $CompitableRealms[0];

		$RealmId = $FirstRealm['id'];

		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'tokenName' => $this->security->get_csrf_token_name(),
			'tokenValue' => $this->security->get_csrf_hash(),
			'lastInstance' => $this->instance_model->getLasInstance($RealmId)
		);

		// Load my view
		$output = $this->template->loadPage("admin.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Instancias', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/instanceView/js/admin.js");
	}
	public function viewInstance()
	{
		// Change the title
		$this->administrator->setTitle("Instancia");

		// Prepare the compitable realms
		$CompitableRealms = array();
		foreach ($this->realms->getRealms() as $realmData)
		{
			$CompitableRealms[] = array('id' => $realmData->getId(), 'name' => $realmData->getName());
		}
		unset($realmData);

		// Get the first compitable realm
		$FirstRealm = $CompitableRealms[0];

		$RealmId = $FirstRealm['id'];

		// Get the post variables
		$instanceID = (int)$this->input->post('instanceId');

		// Get Characters Instance
		$raid = $this->instance_model->getCharactersInstanceById($RealmId, $instanceID);

		// Get the realm object
		if (!($realmObj = $this->realms->getRealm($RealmId)))
		{
			die($this->language['ERROR_REALM']);
		}

		if($raid == false)
		{
			die($this->language['ERROR_ID']);
		}

		$instance = $this->instance_model->getInstanceById($RealmId, $instanceID);
		$characters = $this->instance_model->getCharactersInfo($raid, $RealmId);

		$npc = $this->instance_model->getNpcByInstance($RealmId, $instanceID);

		$Elite = array();
		$RareElite = array();
		$Boss = array();

		foreach($npc as $value => $v)
		{
			if($npc[$value]['rank'] == 3 )
			{
				$Boss[$value]['id'] = $npc[$value]['id'];
				$Boss[$value]['name'] = $npc[$value]['name'];
				$Boss[$value]['maxlevel'] = $npc[$value]['maxlevel'];
			}
			if($npc[$value]['rank'] == 2 )
			{
				$RareElite[$value]['id'] = $npc[$value]['id'];
				$RareElite[$value]['name'] = $npc[$value]['name'];
				$RareElite[$value]['maxlevel'] = $npc[$value]['maxlevel'];
			}
			if($npc[$value]['rank'] == 1 )
			{
				$Elite[$value]['id'] = $npc[$value]['id'];
				$Elite[$value]['name'] = $npc[$value]['name'];
				$Elite[$value]['maxlevel'] = $npc[$value]['maxlevel'];
			}
		}
		
		// Prepare my data
		$data = array(
			'url' => $this->template->page_url,
			'characters' => $characters,
			'instance' => $instance,
			'idRaid' => $instanceID,
			'Boss' => $Boss,
			'RareElite' => $RareElite,
			'Elite' => $Elite,
			'nameMap' => $this->instance_model->getNameMapById($RealmId, $instance[0]['map'])
		);

		// Load my view
		$output = $this->template->loadPage("instance.tpl", $data);

		// Put my view in the main box with a headline
		$content = $this->administrator->box('Ver Instancia', $output);

		// Output my content. The method accepts the same arguments as template->view
		$this->administrator->view($content, false, "modules/instanceView/js/admin.js");
	}
}