<?php
/*******************************************************************************
 *
 * $Id: HPSIMDAO.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/HPSIM/HPSIMDAO.php $
 *
 *******************************************************************************
 */

namespace STS\HPSIM;

use STS\Util\SysLog;

class HPSIMDAO
{
	// Chassis
	private $queryAllEnclosures = "mxquery -e 'All Enclosures' -x xml";

	// Management Modules
	private $queryMgmtProcs = "mxquery -e 'STS-ManagementProcessors' -x xml";
	private $reportMgmtProcs = "mxreport -e 'STS-ManagementProcessorVersions' -x XML";

	// VCs/FlexFabric
	private $querySwitches = "mxquery -e 'STS-Switches' -x xml";
	private $reportSwitches = "mxreport -e 'STS-SwitchVersions' -x XML";

	// Blades
	private $queryAllServers = "mxquery -e 'All Servers' -x xml";
	private $reportBlades = "mxreport -e 'STS-Blades' -x XML";
	private $reportBladeSlots = "mxreport -e 'STS-BladeSlots' -x XML";

	private $simConnect;
	private $config;
	private $useLocalFiles;

	protected $sysLog;
	protected $logLevel;

	public function __construct($useLocalFiles = false)
	{
		// Set up SysLog
		$this->sysLog   = SysLog::singleton($GLOBALS['config']->appName);
		$this->logLevel = $GLOBALS['config']->logLevel;
		$this->sysLog->debug();

		$this->config        = $GLOBALS['config'];
		$this->useLocalFiles = $useLocalFiles;

		// read the config file to get the HP SIM host and username
		$this->simConnect = "ssh {$this->config->hpSimUser}@{$this->config->hpSimHost}";
	}

	/**
	 * @return array
	 */
	public function getChassis()
	{
		$this->sysLog->debug();
		return $this->mxQuery("queryAllEnclosures");
	}

	/**
	 * @return array
	 */
	public function getMgmtProcessors()
	{
		$this->sysLog->debug();
		$mgmtProcs      = $this->mxQuery("queryMgmtProcs");
		$mmsDevNameHash = $this->mxReport("reportMgmtProcs");

		for ($i = 0; $i < count($mgmtProcs); $i++) {
			if (array_key_exists($mgmtProcs[$i]["DeviceName"], $mmsDevNameHash)) {
				$hash = $mmsDevNameHash[$mgmtProcs[$i]["DeviceName"]];
				foreach ($hash as $key => $value) {
					$mgmtProcs[$i][$key] = $value;
				}
			}
		}
		return $mgmtProcs;
	}

	/**
	 * @return array
	 */
	public function getSwitches()
	{
		$this->sysLog->debug();
		$switches            = $this->mxQuery("querySwitches");
		$switchesDevNameHash = $this->mxReport("reportSwitches");

		for ($i = 0; $i < count($switches); $i++) {
			if (array_key_exists($switches[$i]["DeviceName"], $switchesDevNameHash)) {
				$hash = $switchesDevNameHash[$switches[$i]["DeviceName"]];
				foreach ($hash as $key => $value) {
					$switches[$i][$key] = $value;
				}
			}
		}
		return $switches;
	}

	/**
	 * @return array
	 */
	public function getBlades()
	{
		$this->sysLog->debug();
		$blades                = $this->mxQuery("queryAllServers");
		$bladesDevNameHash     = $this->mxReport("reportBlades");
		$bladeSlotsDevNameHash = $this->mxReport("reportBladeSlots");

		for ($i = 0; $i < count($blades); $i++) {
			if (array_key_exists($blades[$i]["DeviceName"], $bladesDevNameHash)) {
				$hash = $bladesDevNameHash[$blades[$i]["DeviceName"]];
				foreach ($hash as $key => $value) {
					$blades[$i][$key] = $value;
				}
			}

			if (array_key_exists($blades[$i]["DeviceName"], $bladeSlotsDevNameHash)) {
				$hash = $bladeSlotsDevNameHash[$blades[$i]["DeviceName"]];
				foreach ($hash as $key => $value) {
					$blades[$i][$key] = $value;
				}
			}
		}
		file_put_contents("{$this->config->dataDir}/{$this->config->simBladesFile}", serialize($blades));
		return $blades;
	}

	/**
	 * @param $query
	 * @return array
	 */
	public function mxQuery($query)
	{
		$this->sysLog->debug();
		// this is the array that will be returned
		$results = array();

		$file = $this->config->dataDir . "/" . $this->config->$query;
		$doc  = new \DOMDocument();
		if ($this->useLocalFiles) {
			$doc->load($file);
		}
		else {
			$xml = $this->runQuery($this->$query);
			file_put_contents($file, $xml);
			$doc->loadXML($xml);
		}

		// get a list of nodes and loop over them
		$nodes = $doc->getElementsByTagName('node');
		foreach ($nodes as $node) {
			// hash of attributes to add to our resulting array
			$hash = array();

			// get a list of <attribute> nodes for this <node> and loop over them
			$attributes = $node->childNodes;
			foreach ($attributes as $attribute) {
				// skip #text nodes
				if ($attribute->nodeName == "#text") {
					continue;
				}

				// get the XML attributes for this <attribute> node. Yeah, the naming here is unfortunate
				/** @var $nodeAttrs \DOMNamedNodeMap */
				$nodeAttrs = $attribute->attributes;

				// get the attribute named "name"
				/** @var $attrNameAttr \DOMNode */
				$attrNameAttr = $nodeAttrs->getNamedItem('name');

				// assign to our hash
				if ($attrNameAttr->nodeValue == "DeviceName" || $attrNameAttr->nodeValue == "SerialNumber") {
					$value = strtolower($attribute->nodeValue);
				}
				else {
					$value = $attribute->nodeValue;
				}
				$hash[$attrNameAttr->nodeValue] = $value;
			}
			// assign this instance to our array to be returned
			$results[] = $hash;
		}
		// return the list
		return $results;
	}

	/*
	 Run an mxreport, parse output using the passed class and return a hash by device name
	*/
	/**
	 * @param $query
	 * @return array
	 */
	public function mxReport($query)
	{
		$this->sysLog->debug();
		// this is the array that will be returned
		$results = array();

		$fileName = $this->config->dataDir . "/" . $this->config->$query;

		$doc = new \DOMDocument('1.0', 'UTF-8');
		if ($this->useLocalFiles) {
			$xml        = file_get_contents($fileName);
			$decodedXml = utf8_encode($xml);
			$doc->loadXML($decodedXml);
		}
		else {
			$xml        = $this->runQuery($this->$query);
			$decodedXml = utf8_encode($xml);
			file_put_contents($fileName, $decodedXml);
			$doc->loadXML($decodedXml);
		}

		// get a list of Rows (nodes) and loop over them
		$rows = $doc->getElementsByTagName('Row');
		foreach ($rows as $row) {
			// create a new array which will be assigned to the returned hash
			$hash       = array();
			$deviceName = null;

			// get a list of nodes for this <Row> and loop over them
			$properties = $row->childNodes;
			foreach ($properties as $prop) {
				if ($prop->nodeName == "#text") {
					continue;
				}

				// check for device name since we'll use that as our returned hash key
				if ($prop->nodeName == "DeviceName") {
					$deviceName         = strtolower($prop->nodeValue);
					$hash['DeviceName'] = $deviceName;
					continue;
				}
				if ($prop->nodeName == "SerialNumber") {
					$hash['SerialNumber'] = strtolower($prop->nodeValue);
					continue;
				}
				$hash[$prop->nodeName] = $prop->nodeValue;
			}
			// assign this instance to our array to be returned
			if ($deviceName) {
				$results[$deviceName] = $hash;
			}
		}

		// return the list
		return $results;
	}

	/**
	 * @param $query
	 * @return string
	 * @throws \ErrorException
	 */
	public function runQuery($query)
	{
		$this->sysLog->debug();
		// define the complete ssh command to run
		$command = "{$this->simConnect} \"{$query}\"";

		// initialize the vars
		$xml    = "";
		$retVar = "";

		// exect the command and check for errors
		exec($command, $xml, $retVar);
		if ($retVar != 0) {
			throw new \ErrorException("Could not execute HP SIM command: {$command}");
		}
		// the output of exec is an array so join it into a string and return
		$xml = implode("\n", $xml);
		return $xml;
	}
} 

