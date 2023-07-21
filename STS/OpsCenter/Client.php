<?php

namespace STS\OpsCenter;

/**
 * OpsCenter Client
 */
class Client extends OpsCenter
{

	/**
	 * A method to create OpsCenter\Client objects
	 */
	public function __construct()
	{

        // construct our parent
        parent::__construct();

	} // __construct()

    /**
     * A method to allocate a number of IP addresses from a vNET.
     *
     * @param string $vnet
     * @param null   $num
     *
     * @throws \Exception
     * @return string XML output of _IAASRequest
     */
    public function allocateIpAddresses($vnet = "", $num = null) {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_vnetIdType, $vnet)) { throw new \Exception ("ERROR: bad vNET ID ({$vnet})"); }

            //
            // we should be good at this point
            //

            // set our required / optional params
            $options = Array('vnet' => $vnet);
            if ($num) { $options['num'] = $num; }

            // perform our request
            $result = $this->_IAASRequest('AllocateIpAddresses', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // allocateIpAddresses()

    /**
     * A method to attach volumes to a given vserver.
     *
     * @param string $vserverId
     * @param array  $volumeIds
     *
     * @throws \Exception
     * @return string
     */
    public function attachVolumesToVserver($vserverId = "", $volumeIds = Array()) {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_vnetIdType, $vserverId)) { throw new \Exception("ERROR: bad vServer ID ({$vserverId})"); }
            if (! is_array($volumeIds)) { throw new \Exception("ERROR: parameter volumeIds not array"); }
            if (count($volumeIds) < 1) { throw new \Exception("ERROR: parameter volumeIds must have at least 1 value"); }

            // iterate over our volume ids to validate values
            foreach ($volumeIds as $volumeId) {
                if (! preg_match($this->_volumeIdType, $volumeId)) {
                    throw new \Exception("ERROR: bad volume ID ({$volumeId})");
                } // if not a volume id
            } // foreach volume id

            //
            // we should be good at this point
            //

            // set our required / optional params
            $options = Array(
                'vserverId' => $vserverId,
                'volumeIds' => implode('&', $volumeIds)
            );

            // generate our volume Ids string
            $vIdCount = 1;
            foreach ($volumeIds as &$volumeId) {
                $options["volumeId.{$vIdCount}"] = $volumeId;
                $vIdCount++;
            }

            // perform our request
            $result = $this->_IAASRequest('AttachVolumesToVserver', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // attachVolumesToVserver()

    /**
     * @param string $account
     * @param string $forUser
     *
     * @return string
     * @throws \Exception
     */
    public function createAccessKeyAsObject($account = "", $forUser = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_accountIdType, $account)) { throw new \Exception ("ERROR: bad account ID ({$account})"); }

            //
            // we should be good at this point
            //

            // set our required / optional params
            $options = Array('account' => $account);
            if ($forUser) { $options['forUser'] = $forUser; }

            // perform our request
            $result = $this->_AKMRequest('CreateAccessKeyObject', $options);

        } // try

            // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // createAccessKeyAsObject()

    /**
     * @param string $account
     * @param string $keyFileStoreName
     * @param string $forUser
     *
     * @return string
     * @throws \Exception
     */
    public function createAccessKeyToFile($account = "", $keyFileStoreName = "", $forUser = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_accountIdType, $account)) { throw new \Exception ("ERROR: bad account ID ({$account})"); }
            if (! preg_match($this->_genericString, $keyFileStoreName)) { throw new \Exception("ERROR: keyFileStoreName not string"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'account'          => $account,
                'keyFileStoreName' => $keyFileStoreName
            );

            // set our optional params
            if ($forUser) { $options['forUser'] = $forUser; }

            // perform our request
            $result = $this->_AKMRequest('CreateAccessKeyToFile', $options);

        } // try

            // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // createAccessKeyToFile()

    /**
     * @param string $name
     * @param string $description
     * @param int    $size
     *
     * @return string
     * @throws \Exception
     */
    public function createDistributionGroup($name = "", $description = "", $size = 5000) {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_genericString, $name)) { throw new \Exception("ERROR: name not string"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array( 'name' => $name );

            // set our optional params
            if ($description) { $options['description'] = $description; }
            if ($size) { $options['size'] = $size; }

            // perform our request
            $result = $this->_IAASRequest('CreateDistributionGroup', $options);

        } // try

            // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // createDistributionGroup()

    /**
     * @param string $keyName
     *
     * @return string
     * @throws \Exception
     */
    public function createKeyPairAsObject($keyName = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_genericString, $keyName)) { throw new \Exception("ERROR: keyName not string"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array( 'keyName' => $keyName );

            // perform our request
            $result = $this->_IAASRequest('CreateKeyPairAsObject', $options);

        } // try

            // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // createKeyPairAsObject()

    /**
     * @param string $keyName
     * @param string $keyFileName
     *
     * @return string
     * @throws \Exception
     */
    public function createKeyPairToFile($keyName = "", $keyFileName = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_genericString, $keyName)) { throw new \Exception("ERROR: invalid keyName ({$keyName})"); }
            if (! preg_match($this->_genericString, $keyFileName)) { throw new \Exception("ERROR: invalid keyName ({$keyFileName})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'keyName'     => $keyName,
                'keyFileName' => $keyFileName
            );

            // perform our request
            $result = $this->_IAASRequest('CreateKeyPairToFile', $options);

        } // try

            // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // createKeyPairToFile()

    /**
     * @param string $name
     * @param string $description
     * @param string $volumeId
     *
     * @return string
     * @throws \Exception
     */
    public function createSnapshot($name = "", $description = "", $volumeId = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_genericString, $name)) { throw new \Exception("ERROR: invalid name ({$name})"); }
            if (! preg_match($this->_volumeIdType, $volumeId)) { throw new \Exception("ERROR: invalid volumeId ({$volumeId})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'name'     => $name,
                'volumeId' => $volumeId
            );

            // set our optional params
            if ($description) { $options['description'] = $description; }

            // perform our request
            $result = $this->_IAASRequest('CreateSnapshot', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // createSnapshot()

    /**
     * @param string $resourceId
     * @param array  $tags
     *
     * @return string
     * @throws \Exception
     */
    public function createTags($resourceId = "", $tags = Array()) {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_resourceIdType, $resourceId)) { throw new \Exception("ERROR: bad vServer ID ({$resourceId})"); }
            if (! is_array($tags)) { throw new \Exception("ERROR: parameter tags not array"); }
            if (count($tags) < 1) { throw new \Exception("ERROR: parameter tags must have at least 1 value"); }

            // iterate over our volume ids to validate values
            foreach ($tags as $key => $val) {

                if (! preg_match($this->_genericString, $key) ) { throw new \Exception("ERROR: bad tag name ({$key})"); }
                if (! preg_match($this->_genericString, $val) ) { throw new \Exception("ERROR: bad tag value ({$val})"); }

            } // foreach volume id

            //
            // we should be good at this point
            //

            // set our required / optional params
            $options = Array( 'resourceId' => $resourceId, );

            // generate our tag name / value entries
            $tagCount = 1;
            foreach ($tags as $key => $val) {
                $options["tags.{$tagCount}.name={$key}"] = "tags.{$tagCount}.value={$val}";
                $tagCount++;
            }

            // perform our request
            $result = $this->_IAASRequest('CreateTags', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;
        
    } // createTags()

    /**
     * @param string $name
     * @param string $description
     * @param int    $size
     *
     * @return string
     * @throws \Exception
     */
    public function createVnet($name = "", $description = "", $size = 16) {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_genericString, $name)) { throw new \Exception("ERROR: invalid name ({$name})"); }
            if ($size < 1) { throw new \Exception("ERROR: size must be a positive integer"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'name' => $name,
                'size' => $size
            );

            // set our optional params
            if ($description) { $options['description'] = $description; }

            // perform our request
            $result = $this->_IAASRequest('CreateVnet', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // createVnet()

    /**
     * @param string $name
     * @param string $description
     * @param int    $size
     * @param int    $shared
     * @param string $snapshotId
     *
     * @return string
     * @throws \Exception
     */
    public function createVolume($name = "", $description = "", $size = 16, $shared = 0, $snapshotId = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_genericString, $name)) { throw new \Exception("ERROR: invalid name ({$name})"); }
            if ($size < 1) { throw new \Exception("ERROR: size must be a positive integer"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'name' => $name,
            );

            // set our optional params
            if ($description) { $options['description'] = $description; }
            if ($size) { $options['size'] = $size; }
            if ($shared) { $options['shared'] = $shared; }
            if ($snapshotId) {
                if (! preg_match($this->_snapshotIdType, $snapshotId)) { throw new \Exception("ERROR: bad snapshot ID ({$snapshotId})");  }
                $options['snapshotId'] = $snapshotId;
            }

            // perform our request
            $result = $this->_IAASRequest('CreateVolume', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // createVolume()

    /**
     * @param string $accessKeyId
     *
     * @return string
     * @throws \Exception
     */
    public function deleteAccessKey($accessKeyId = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_genericString, $accessKeyId)) { throw new \Exception("ERROR: invalid name ({$accessKeyId})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'accessKeyId' => $accessKeyId,
            );

            // perform our request
            $result = $this->_AKMRequest('DeleteAccessKey', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;
        
    } // deleteAccessKey()

    /**
     * @param string $distributionGroupId
     *
     * @return string
     * @throws \Exception
     */
    public function deleteDistributionGroup($distributionGroupId = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_distributionGroupIdType, $distributionGroupId)) {
                throw new \Exception("ERROR: invalid distribution group id ({$distributionGroupId})");
            }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'distributionGroupId' => $distributionGroupId,
            );

            // perform our request
            $result = $this->_IAASRequest('DeleteDistributionGroup', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // deleteDistributionGroup()

    /**
     * @param string $keyName
     *
     * @return string
     * @throws \Exception
     */
    public function deleteKeyPair($keyName = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_genericString, $keyName)) { throw new \Exception("ERROR: invalid name ({$keyName})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'keyName' => $keyName,
            );

            // perform our request
            $result = $this->_IAASRequest('DeleteKeyPair', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;
        
    } // deleteKeyPair()

    /**
     * @param string $snapshotId
     *
     * @return string
     * @throws \Exception
     */
    public function deleteSnapshot($snapshotId = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_snapshotIdType, $snapshotId)) {
                throw new \Exception("ERROR: invalid snapshotId ({$snapshotId})");
            }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'snapshotId' => $snapshotId,
            );

            // perform our request
            $result = $this->_IAASRequest('DeleteSnapshot', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;
        
    } // deleteSnapshot()

    /**
     * @param string $resourceId
     * @param array  $tags
     *
     * @return string
     * @throws \Exception
     */
    public function deleteTags($resourceId = "", $tags = Array()) {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_resourceIdType, $resourceId)) {
                throw new \Exception("ERROR: invalid resourceId ({$resourceId})");
            }
            if (! is_array($tags)) { throw new \Exception("ERROR: tags param is not array"); }
            if (count($tags) < 1) { throw new \Exception("ERROR: tags parameter has less than one entry"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'resourceId' => $resourceId,
            );

            // iterate over our tags
            $count = 1;
            foreach ($tags as $tag) {

                if (! preg_match($this->_genericString, $tag)) { throw new \Exception("ERROR: invalid name ({$tag})"); }

                // add in our options
                $options["tags.{$count}.name"] = $tag;

            } // foreach tag

            // perform our request
            $result = $this->_IAASRequest('DeleteTags', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // deleteTags()

    /**
     * @param string $vnet
     *
     * @return string
     * @throws \Exception
     */
    public function deleteVnet($vnet = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_vnetIdType, $vnet)) {
                throw new \Exception("ERROR: invalid vNET ID ({$vnet})");
            }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'vnet' => $vnet,
            );

            // perform our request
            $result = $this->_IAASRequest('DeleteVnet', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;        
        
    } // deleteVnet()

    /**
     * @param string $volumeId
     *
     * @return string
     * @throws \Exception
     */
    public function deleteVolume($volumeId = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_volumeIdType, $volumeId)) {
                throw new \Exception("ERROR: invalid volumeId ({$volumeId})");
            }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'volumeId' => $volumeId,
            );

            // perform our request
            $result = $this->_IAASRequest('DeleteVolume', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // deleteVolume()

    /**
     * @param string $serverTemplateId
     *
     * @return string
     * @throws \Exception
     */
    public function deregisterServerTemplate($serverTemplateId = "") {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_serverTemplateIdType, $serverTemplateId)) {
                throw new \Exception("ERROR: invalid server template id ({$serverTemplateId})");
            }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'serverTemplateId' => $serverTemplateId
            );

            // perform our request
            $result = $this->_IAASRequest('DeregisterServerTemplate', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // deregisterServerTemplate()

    /**
     * @param string $forUser
     *
     * @return string
     * @throws \Exception
     */
    public function describeAccessKeys($forUser = "") {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // forUser is optional
            if ($forUser) {
                if (! preg_match($this->_genericString, $forUser)) { throw new \Exception("ERROR: invalid forUser ({$forUser})"); }
                $options['forUser'] = $forUser;
            }

            // perform our request
            $result = $this->_AKMRequest('DescribeAccessKeys', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeAccessKeys()

    /**
     * @param string $account
     * @param string $forUser
     *
     * @return string
     * @throws \Exception
     */
    public function describeAccounts($account = "", $forUser = "") {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($account) {
                if (! preg_match($this->_accountIdType, $account)) {throw new \Exception("ERROR: invalid account ({$account})"); }
                $options['account'] = $account;
            }
            if ($forUser) {
                if (! preg_match($this->_genericString, $forUser)) { throw new \Exception("ERROR: invalid forUser ({$forUser})"); }
                $options['forUser'] = $forUser;
            }

            // perform our request
            $result = $this->_AKMRequest('DescribeAccounts', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeAccounts()

    /**
     * @param string $resourceId
     * @param array  $attrNames
     *
     * @return string
     * @throws \Exception
     */
    public function describeAttributes($resourceId = "", $attrNames = Array()) {

        // this could get messy
        try {

            // run through our checks
            if (! preg_match($this->_resourceIdType, $resourceId)) {
                throw new \Exception("ERROR: invalid resourceId ({$resourceId})");
            }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'resourceId' => $resourceId
            );

            // set our optional params
            if ($attrNames) {
                $count = 1;
                foreach ($attrNames as $name) {
                    if (! preg_match($this->_genericString, $name)) { throw new \Exception("ERROR: invalid attribute name ({$name})"); }
                    $options["attrNames.{$count}"] = $name;
                    $count++;
                } // foreach attribute name
            } // if we have attribute names

            // perform our request
            $result = $this->_IAASRequest('DescribeAttributes', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeAttributes()

    /**
     * @param array $ids
     * @param array $filters
     *
     * @return string
     * @throws \Exception
     */
    public function describeDistributionGroups($ids = Array(), $filters = Array()) {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($ids) {
                $count = 1;
                foreach ($ids as $id) {
                    if (! preg_match($this->_distributionGroupIdType, $id)) {throw new \Exception("ERROR: invalid distributionGroupId ({$id})"); }
                    $options["ids.{$count}"] = $id;
                    $count++;
                }
            }
            if ($filters) {
                $count = 1;
                foreach ($filters as $filter) {
                    if (! preg_match($this->_genericString, $filter)) {throw new \Exception("ERROR: invalid filter ({$filter})"); }
                    $options["filters.{$count}"] = $filter;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('DescribeDistributionGroups', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeDistributionGroups()

    /**
     * @param array $filters
     *
     * @return string
     * @throws \Exception
     */
    public function describeIpAddresses($filters = Array()) {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($filters) {
                $count = 1;
                foreach ($filters as $filter) {
                    if (! preg_match($this->_genericString, $filter)) {throw new \Exception("ERROR: invalid filter ({$filter})"); }
                    $options["filters.{$count}"] = $filter;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('DescribeIpAddresses', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeIpAddresses()

    /**
     * @param array $keyNames
     * @param array $filters
     *
     * @return string
     * @throws \Exception
     */
    public function describeKeyPairs($keyNames = Array(), $filters = Array()) {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($keyNames) {
                $count = 1;
                foreach ($keyNames as $keyName) {
                    if (! preg_match($this->_genericString, $keyName)) {throw new \Exception("ERROR: invalid keyName ({$keyName})"); }
                    $options["keyNames.{$count}"] = $keyName;
                    $count++;
                }
            }
            if ($filters) {
                $count = 1;
                foreach ($filters as $filter) {
                    if (! preg_match($this->_genericString, $filter)) {throw new \Exception("ERROR: invalid filter ({$filter})"); }
                    $options["filters.{$count}"] = $filter;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('DescribeKeyPairs', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeKeyPairs()

    /**
     * @param array $ids
     * @param array $filters
     *
     * @return string
     * @throws \Exception
     */
    public function describeServerTemplates($ids = Array(), $filters = Array()) {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($ids) {
                $count = 1;
                foreach ($ids as $id) {
                    if (! preg_match($this->_serverTemplateIdType, $id)) {throw new \Exception("ERROR: invalid serverTemplateId ({$id})"); }
                    $options["ids.{$count}"] = $id;
                    $count++;
                }
            }
            if ($filters) {
                $count = 1;
                foreach ($filters as $filter) {
                    if (! preg_match($this->_genericString, $filter)) {throw new \Exception("ERROR: invalid filter ({$filter})"); }
                    $options["filters.{$count}"] = $filter;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('DescribeServerTemplates', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeServerTemplates()

    /**
     * @param array $ids
     * @param array $filters
     *
     * @return string
     * @throws \Exception
     */
    public function describeSnapshots($ids = Array(), $filters = Array()) {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($ids) {
                $count = 1;
                foreach ($ids as $id) {
                    if (! preg_match($this->_snapshotIdType, $id)) {throw new \Exception("ERROR: invalid snapshotId ({$id})"); }
                    $options["ids.{$count}"] = $id;
                    $count++;
                }
            }
            if ($filters) {
                $count = 1;
                foreach ($filters as $filter) {
                    if (! preg_match($this->_genericString, $filter)) {throw new \Exception("ERROR: invalid filter ({$filter})"); }
                    $options["filters.{$count}"] = $filter;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('DescribeSnapshots', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeSnapshots()

    /**
     * @param array $ids
     * @param array $filters
     *
     * @return string
     * @throws \Exception
     */
    public function describeTags($ids = Array(), $filters = Array()) {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($ids) {
                $count = 1;
                foreach ($ids as $id) {
                    if (! preg_match($this->_resourceIdType, $id)) {throw new \Exception("ERROR: invalid resourceId ({$id})"); }
                    $options["ids.{$count}"] = $id;
                    $count++;
                }
            }
            if ($filters) {
                $count = 1;
                foreach ($filters as $filter) {
                    if (! preg_match($this->_genericString, $filter)) {throw new \Exception("ERROR: invalid filter ({$filter})"); }
                    $options["filters.{$count}"] = $filter;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('DescribeTags', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeTags()

    /**
     * @param array $filters
     *
     * @return string
     * @throws \Exception
     */
    public function describeVdcCapabilities($filters = Array()) {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($filters) {
                $count = 1;
                foreach ($filters as $filter) {
                    if (! preg_match($this->_genericString, $filter)) {throw new \Exception("ERROR: invalid filter ({$filter})"); }
                    $options["filters.{$count}"] = $filter;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('DescribeVdcCapabilities', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeVdcCapabilities()

    /**
     * @param array $ids
     * @param array $filters
     *
     * @return string
     * @throws \Exception
     */
    public function describeVnets($ids = Array(), $filters = Array()) {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($ids) {
                $count = 1;
                foreach ($ids as $id) {
                    if (! preg_match($this->_vnetIdType, $id)) {throw new \Exception("ERROR: invalid vnetId ({$id})"); }
                    $options["ids.{$count}"] = $id;
                    $count++;
                }
            }
            if ($filters) {
                $count = 1;
                foreach ($filters as $filter) {
                    if (! preg_match($this->_genericString, $filter)) {throw new \Exception("ERROR: invalid filter ({$filter})"); }
                    $options["filters.{$count}"] = $filter;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('DescribeVnets', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeVnets()

    /**
     * @param array $ids
     * @param array $filters
     *
     * @return string
     * @throws \Exception
     */
    public function describeVolumes($ids = Array(), $filters = Array()) {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($ids) {
                $count = 1;
                foreach ($ids as $id) {
                    if (! preg_match($this->_volumeIdType, $id)) {throw new \Exception("ERROR: invalid volumeId ({$id})"); }
                    $options["ids.{$count}"] = $id;
                    $count++;
                }
            }
            if ($filters) {
                $count = 1;
                foreach ($filters as $filter) {
                    if (! preg_match($this->_genericString, $filter)) {throw new \Exception("ERROR: invalid filter ({$filter})"); }
                    $options["filters.{$count}"] = $filter;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('DescribeVolumes', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeVolumes()

    /**
     * @param array $ids
     * @param array $filters
     *
     * @return string
     * @throws \Exception
     */
    public function describeVserverMetrics($ids = Array(), $filters = Array()) {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($ids) {
                $count = 1;
                foreach ($ids as $id) {
                    if (! preg_match($this->_vserverIdType, $id)) {throw new \Exception("ERROR: invalid vserverId ({$id})"); }
                    $options["ids.{$count}"] = $id;
                    $count++;
                }
            }
            if ($filters) {
                $count = 1;
                foreach ($filters as $filter) {
                    if (! preg_match($this->_genericString, $filter)) {throw new \Exception("ERROR: invalid filter ({$filter})"); }
                    $options["filters.{$count}"] = $filter;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('DescribeVserverMetrics', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeVserverMetrics()

    /**
     * @param array $ids
     * @param array $filters
     *
     * @return string
     * @throws \Exception
     */
    public function describeVservers($ids = Array(), $filters = Array()) {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // handle our optional variables
            if ($ids) {
                $count = 1;
                foreach ($ids as $id) {
                    if (! preg_match($this->_vserverIdType, $id)) {throw new \Exception("ERROR: invalid vserverId ({$id})"); }
                    $options["ids.{$count}"] = $id;
                    $count++;
                }
            }
            if ($filters) {
                $count = 1;
                foreach ($filters as $filter) {
                    if (! preg_match($this->_genericString, $filter)) {throw new \Exception("ERROR: invalid filter ({$filter})"); }
                    $options["filters.{$count}"] = $filter;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('DescribeVservers', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeVservers()

    /**
     * @return string
     */
    public function describeVserverTypes() {

        // this could get messy
        try {

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // perform our request
            $result = $this->_IAASRequest('DescribeVserverTypes', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // describeVserverTypes()

    /**
     * @param string $vserverId
     * @param array  $volumeIds
     * @param int    $force
     *
     * @return string
     * @throws \Exception
     */
    public function detachVolumesFromVserver($vserverId = "", $volumeIds = Array(), $force = 0) {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_vserverIdType, $vserverId)) { throw new \Exception("ERROR: invalid vserverId ({$vserverId})"); }
            if (! is_array($volumeIds)) { throw new \Exception("ERROR: volumeIds is not an array"); }
            foreach ($volumeIds as $id) {
                if (! preg_match($this->_volumeIdType, $id)) { throw new \Exception("ERROR: invalid volumeId ({$id})"); }
            }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();
            $options['vserverId'] = $vserverId;

            // iterate over our volume Ids
            $count = 1;
            foreach ($volumeIds as $id) {
                $options["volumeId.{$count}"] = $id;
            }

            // set our optional params
            if ($force) { $options['force'] = $force; }

            // perform our request
            $result = $this->_IAASRequest('DetachVolumesFromVserver', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // detachVolumesFromVserver()

    /**
     * @param string $keyName
     * @param string $keyFileName
     *
     * @return string
     * @throws \Exception
     */
    public function importKeyPair($keyName = "", $keyFileName = "") {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_genericString, $keyName)) { throw new \Exception("ERROR: invalid vserverId ({$keyName})"); }
            if (! preg_match($this->_genericString, $keyFileName)) { throw new \Exception("ERROR: invalid vserverId ({$keyFileName})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'keyName'     => $keyName,
                'keyFileName' => $keyFileName
            );

            // perform our request
            $result = $this->_IAASRequest('DetachVolumesFromVserver', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // importKeyPair()

    /**
     * @param string $name
     * @param string $description
     * @param string $url
     * @param int    $shared
     *
     * @return string
     * @throws \Exception
     */
    public function importVolume($name = "", $description = "", $url = "", $shared = 0) {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_genericString, $name)) { throw new \Exception("ERROR: invalid name ({$name})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'name' => $name,
            );

            // set our optional params
            if ($description) { $options['description'] = $description; }
            if ($url) { $options['url'] = $url; }
            if ($shared) { $options['shared'] = $shared; }

            // perform our request
            $result = $this->_IAASRequest('ImportVolume', $options);

        } // try

            // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // importVolume()

    /**
     * @param string $resource
     * @param array  $attributes
     *
     * @return string
     * @throws \Exception
     */
    public function modifyAttributes($resource = "", $attributes = Array()) {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_resourceIdType, $resource)) { throw new \Exception("ERROR: invalid resourceId ({$resource})"); }
            if (! is_array($attributes)) { throw new \Exception("ERROR: attributes param not an array"); }
            if (count($attributes) < 1) { throw new \Exception("ERROR: attributes param empty"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();
            $options['resource'] = $resource;

            // iterate over our attributes
            $count = 1;
            foreach ($attributes as $key => $val) {

                // check our keys and values
                if (! preg_match($this->_genericString, $key)) { throw new \Exception("ERROR: invalid attribute name ({$key})"); }
                if (! preg_match($this->_genericString, $val)) { throw new \Exception("ERROR: invalid attribute value ({$val})}"); }

                // store our keys and values
                $options["attributes.{$count}.name"]  = $key;
                $options["attributes.{$count}.value"] = $val;
            }

            // perform our request
            $result = $this->_IAASRequest('ModifyAttributes', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // modifyAttributes()

    /**
     * @param array $vserverIds
     *
     * @return string
     * @throws \Exception
     */
    public function rebootVservers($vserverIds = Array()) {

        // this could get messy
        try {

            // check out our required params
            if (! is_array($vserverIds)) { throw new \Exception("ERROR: vserverIds param not an array"); }
            if (count($vserverIds) < 1) { throw new \Exception("ERROR: vserverIds param empty"); }
            foreach ($vserverIds as $id) {
                if (! preg_match($this->_vserverIdType, $id)) { throw new \Exception("ERROR: invalid vserverId ({$id})"); }
            }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // iterate over our attributes
            $count = 1;
            foreach ($vserverIds as $id) {
                $options["vserverId.{$count}"]  = $id;
            }

            // perform our request
            $result = $this->_IAASRequest('RebootVservers', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // rebootVservers()

    /**
     * @param string $vserverId
     * @param string $key
     *
     * @return string
     * @throws \Exception
     */
    public function receiveMessageFromVserver($vserverId = "", $key = "") {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_vserverIdType, $vserverId)) { throw new \Exception("ERROR: invalid vserverId ({$vserverId})"); }
            if (! preg_match($this->_genericString, $key)) { throw new \Exception("ERROR: invalid key ({$key})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'vserverId' => $vserverId,
                'key'       => $key
            );

            // perform our request
            $result = $this->_IAASRequest('ReceiveMessageFromVserver', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // receiveMessageFromVserver()

    /**
     * @param string $publicKey
     * @param string $account
     * @param string $forUser
     *
     * @return string
     * @throws \Exception
     */
    public function registerAccessKey($publicKey = "", $account = "", $forUser = "") {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_genericString, $publicKey)) { throw new \Exception("ERROR: invalid publicKey ({$publicKey})"); }
            if (! preg_match($this->_accountIdType, $account)) { throw new \Exception("ERROR: invalid key ({$account})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'publicKey' => $publicKey,
                'account'   => $account
            );

            // set our optional params
            if ($forUser) {
                if (! preg_match($this->_genericString, $forUser)) { throw new \Exception("ERROR: invalid publicKey ({$publicKey})"); }
                $options['forUser'] = $forUser;
            }

            // perform our request
            $result = $this->_AKMRequest('RegisterAccessKey', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // registerAccessKey()

    /**
     * @param string $keyName
     * @param string $publicKey
     *
     * @return string
     * @throws \Exception
     */
    public function registerKeyPair($keyName = "", $publicKey = "") {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_genericString, $publicKey)) { throw new \Exception("ERROR: invalid publicKey ({$publicKey})"); }
            if (! preg_match($this->_genericString, $keyName)) { throw new \Exception("ERROR: invalid keyName ({$keyName})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'publicKey' => $publicKey,
                'keyName'   => $keyName
            );

            // perform our request
            $result = $this->_IAASRequest('RegisterKeyPair', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // registerKeyPair()

    /**
     * @param string $name
     * @param string $description
     * @param string $url
     *
     * @return string
     * @throws \Exception
     */
    public function registerServerTemplateUrl($name = "", $description = "", $url = "") {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_genericString, $name)) { throw new \Exception("ERROR: invalid name ({$name})"); }
            if (! preg_match($this->_genericString, $url)) { throw new \Exception("ERROR: invalid url ({$url})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'name' => $name,
                'url'  => $url
            );

            // set our optional params
            if ($description) {
                if (! preg_match($this->_genericString, $description)) { throw new \Exception("ERROR: invalid description ({$description})"); }
                $options['description'] = $description;
            }

            // perform our request
            $result = $this->_IAASRequest('RegisterServerTemplateUrl', $options);

        } // try

            // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // registerServerTemplateUrl()

    /**
     * @param string $name
     * @param string $description
     * @param string $vserverId
     *
     * @return string
     * @throws \Exception
     */
    public function registerServerTemplateVserver($name = "", $description = "", $vserverId = "") {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_genericString, $name)) { throw new \Exception("ERROR: invalid name ({$name})"); }
            if (! preg_match($this->_vserverIdType, $vserverId)) { throw new \Exception("ERROR: invalid url ({$vserverId})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'name'      => $name,
                'vserverId' => $vserverId
            );

            // set our optional params
            if ($description) {
                if (! preg_match($this->_genericString, $description)) { throw new \Exception("ERROR: invalid description ({$description})"); }
                $options['description'] = $description;
            }

            // perform our request
            $result = $this->_IAASRequest('RegisterServerTemplateVserver', $options);

        } // try

            // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // registerServerTemplateVserver()

    /**
     * @param string $name
     * @param string $description
     * @param string $url
     *
     * @return string
     * @throws \Exception
     */
    public function registerServerTemplatesFromAssembly($name = "", $description = "", $url = "") {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_genericString, $name)) { throw new \Exception("ERROR: invalid name ({$name})"); }
            if (! preg_match($this->_genericString, $url)) { throw new \Exception("ERROR: invalid url ({$url})"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'name' => $name,
                'url'  => $url
            );

            // set our optional params
            if ($description) {
                if (! preg_match($this->_genericString, $description)) { throw new \Exception("ERROR: invalid description ({$description})"); }
                $options['description'] = $description;
            }

            // perform our request
            $result = $this->_IAASRequest('RegisterServerTemplatesFromAssembly', $options);

        } // try

            // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // registerServerTemplatesFromAssembly()

    /**
     * @param string $vnet
     * @param array  $ipAddresses
     *
     * @return string
     * @throws \Exception
     */
    public function releaseIpAddresses($vnet = "", $ipAddresses = Array()) {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_vnetIdType, $vnet)) { throw new \Exception("ERROR: invalid vnet id ({$vnet})"); }
            if (! is_array($ipAddresses)) { throw new \Exception("ERROR: param ipAddresses not array"); }
            if (count($ipAddresses) < 1) { throw new \Exception("ERROR: param ipAddresses empty"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'vnet' => $vnet
            );

            // set our optional params
            $count = 1;
            foreach ($ipAddresses as $ip) {
                if (! preg_match($this->_genericString, $ip)) { throw new \Exception("ERROR: invalid ip ({$ip})"); }
                $options["ipAddresses.{$count}"] = $ip;
                $count++;
            }

            // perform our request
            $result = $this->_IAASRequest('ReleaseIpAddresses', $options);

        } // try

            // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // releaseIpAddresses()

    /**
     * @param string $name
     * @param string $description
     * @param string $serverTemplateId
     * @param array  $ipAddresses
     * @param string $keyName
     * @param string $vserverType
     * @param array  $vnets
     * @param array  $volumes
     * @param string $distGroup
     * @param string $ha
     * @param string $messages
     * @param string $hostname
     * @param string $rootPassword
     *
     * @return string
     * @throws \Exception
     */
    public function runVserver(
        $name = "", // required
        $description = "",
        $serverTemplateId = "", // required
        $ipAddresses = Array(), // required
        $keyName = "",
        $vserverType = "", // required
        $vnets = Array(), // required
        $volumes = Array(),
        $distGroup = "",
        $ha = "",
        $messages = "",
        $hostname = "",
        $rootPassword = ""
    ) {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_genericString, $name)) { throw new \Exception("ERROR: invalid name ({$name})"); }
            if (! preg_match($this->_serverTemplateIdType, $serverTemplateId)) { throw new \Exception("ERROR: invalid name ({$serverTemplateId})"); }
            if (! is_array($ipAddresses)) { throw new \Exception("ERROR: param ipAddresses not array"); }
            if (count($ipAddresses) < 1) { throw new \Exception("ERROR: param ipAddresses empty"); }
            if (! preg_match($this->_genericString, $vserverType)) { throw new \Exception("ERROR: invalid name ({$vserverType})"); }
            if (! is_array($vnets)) { throw new \Exception("ERROR: param vnets not array"); }
            if (count($vnets) < 1) { throw new \Exception("ERROR: param vnets empty"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'name' => $name,
                'serverTemplateId' => $serverTemplateId,
                'vserverType' => $vserverType
            );
            
            // add in required ipAddresses
            $count = 1;
            foreach ($ipAddresses as $ip) {
                if (! preg_match($this->_genericString, $ip)) { throw new \Exception("ERROR: invalid ip ({$ip})"); }
                $options["ipAddresses.{$count}"] = $ip;
                $count++;
            }

            // add in required vnets
            $count = 1;
            foreach ($vnets as $vnet) {
                if (! preg_match($this->_vnetIdType, $vnet)) { throw new \Exception("ERROR: invalid vnet ({$vnet})"); }
                $options["vnets.{$count}"] = $vnet;
                $count++;
            }

            // add in our easy optional params
            if ($description) { $options['description'] = $description; }
            if ($keyName) { $options['keyName'] = $keyName; }
            if ($distGroup) { $options['distGroup'] = $distGroup; }
            if ($ha) { $options['ha'] = $ha; }
            if ($messages) { $options['messages'] = $messages; }
            if ($rootPassword) { $options['rootPassword'] = $rootPassword; }

            // add in our more complex optional params
            if ($hostname) {
                if (! preg_match($this->_hostnameType, $hostname)) { throw new \Exception("ERROR: invalid hostname ({$hostname})"); }
                $options['hostname'] = $hostname;
            }

            if ($volumes) {
                if (! is_array($volumes)) { throw new \Exception("ERROR: optional param volumes not array"); } 
                if (count($volumes) < 1) { throw new \Exception("ERROR: optional param volumes empty"); }

                // iterate over our volumes
                $count = 1;
                foreach ($volumes as $volume) {
                    if (! preg_match($this->_volumeIdType, $volume)) { throw new \Exception("ERROR: invalid volume ({$volume})"); }
                    $options["volumes.{$count}"] = $volume;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('RunVserver', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // runVserver()

    /**
     * @param string $name
     * @param string $description
     * @param string $serverTemplateId
     * @param int    $num
     * @param array  $vnets
     * @param string $keyName
     * @param string $vserverType
     * @param array  $volumes
     * @param string $distGroup
     * @param string $ha
     * @param string $messages
     * @param string $hostname
     * @param string $rootPassword
     *
     * @return string
     * @throws \Exception
     */
    public function runVservers(
        $name = "",
        $description = "",
        $serverTemplateId = "",
        $num = 1,
        $vnets = Array(),
        $keyName = "",
        $vserverType = "",
        $volumes = Array(),
        $distGroup = "",
        $ha = "",
        $messages = "",
        $hostname = "",
        $rootPassword = ""
    ) {


        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_genericString, $name)) { throw new \Exception("ERROR: invalid name ({$name})"); }
            if (! preg_match($this->_serverTemplateIdType, $serverTemplateId)) { throw new \Exception("ERROR: invalid name ({$serverTemplateId})"); }
            if (! preg_match($this->_genericString, $vserverType)) { throw new \Exception("ERROR: invalid name ({$vserverType})"); }
            if (! is_array($vnets)) { throw new \Exception("ERROR: param vnets not array"); }
            if (count($vnets) < 1) { throw new \Exception("ERROR: param vnets empty"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'name' => $name,
                'serverTemplateId' => $serverTemplateId,
                'vserverType' => $vserverType
            );

            // add in required vnets
            $count = 1;
            foreach ($vnets as $vnet) {
                if (! preg_match($this->_vnetIdType, $vnet)) { throw new \Exception("ERROR: invalid vnet ({$vnet})"); }
                $options["vnets.{$count}"] = $vnet;
                $count++;
            }

            // add in our easy optional params
            if ($description) { $options['description'] = $description; }
            if ($keyName) { $options['keyName'] = $keyName; }
            if ($distGroup) { $options['distGroup'] = $distGroup; }
            if ($ha) { $options['ha'] = $ha; }
            if ($num) { $options['num'] = $num; }
            if ($messages) { $options['messages'] = $messages; }
            if ($rootPassword) { $options['rootPassword'] = $rootPassword; }

            // add in our more complex optional params
            if ($hostname) {
                if (! preg_match($this->_hostnameType, $hostname)) { throw new \Exception("ERROR: invalid hostname ({$hostname})"); }
                $options['hostname'] = $hostname;
            }

            if ($volumes) {
                if (! is_array($volumes)) { throw new \Exception("ERROR: optional param volumes not array"); }
                if (count($volumes) < 1) { throw new \Exception("ERROR: optional param volumes empty"); }

                // iterate over our volumes
                $count = 1;
                foreach ($volumes as $volume) {
                    if (! preg_match($this->_volumeIdType, $volume)) { throw new \Exception("ERROR: invalid volume ({$volume})"); }
                    $options["volumes.{$count}"] = $volume;
                    $count++;
                }
            }

            // perform our request
            $result = $this->_IAASRequest('RunVservers', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // runVservers()

    /**
     * @param string $vserverId
     * @param array  $messages
     *
     * @return string
     * @throws \Exception
     */
    public function sendMessagesToVserver($vserverId = "", $messages = Array()) {

        // this could get messy
        try {

            // check out our required params
            if (! preg_match($this->_vserverIdType, $vserverId)) { throw new \Exception("ERROR: invalid vserverId ({$vserverId})"); }
            if (! is_array($messages)) { throw new \Exception("ERROR: param messages not array"); }
            if (count($messages) < 1) { throw new \Exception("ERROR: param messages empty"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array(
                'vserverId' => $vserverId
            );

            // set our optional params
            $count = 1;
            foreach ($messages as $message) {
                if (! preg_match($this->_genericString, $message)) { throw new \Exception("ERROR: invalid message ({$message})"); }
                $options["messages.{$count}"] = $message;
                $count++;
            }

            // perform our request
            $result = $this->_IAASRequest('SendMessageToVserver', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // sendMessageToVserver()

    /**
     * @param array $vserverIds
     *
     * @return string
     * @throws \Exception
     */
    public function startVservers($vserverIds = Array()) {

        // this could get messy
        try {

            // check out our required params
            if (! is_array($vserverIds)) { throw new \Exception("ERROR: param vserverIds not array"); }
            if (count($vserverIds) < 1) { throw new \Exception("ERROR: param vserverIds empty"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // set our required vserverIds
            $count = 1;
            foreach ($vserverIds as $vserverId) {
                if (! preg_match($this->_vserverIdType, $vserverId)) { throw new \Exception("ERROR: invalid vserverId ({$vserverId})"); }
                $options["vserverIds.{$count}"] = $vserverId;
                $count++;
            }

            // perform our request
            $result = $this->_IAASRequest('StartVservers', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // startVservers()

    /**
     * @param array $vserverIds
     * @param int   $force
     *
     * @return string
     * @throws \Exception
     */
    public function stopVservers($vserverIds = Array(), $force = 0) {

        // this could get messy
        try {

            // check out our required params
            if (! is_array($vserverIds)) { throw new \Exception("ERROR: param vserverIds not array"); }
            if (count($vserverIds) < 1) { throw new \Exception("ERROR: param vserverIds empty"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // set our required vserverIds
            $count = 1;
            foreach ($vserverIds as $vserverId) {
                if (! preg_match($this->_vserverIdType, $vserverId)) { throw new \Exception("ERROR: invalid vserverId ({$vserverId})"); }
                $options["vserverIds.{$count}"] = $vserverId;
                $count++;
            }

            // set our optional params
            if ($force) { $options['force'] = $force; }

            // perform our request
            $result = $this->_IAASRequest('StopVservers', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // stopVservers()

    /**
     * @param array $vserverIds
     * @param int   $force
     *
     * @return string
     * @throws \Exception
     */
    public function terminateVservers($vserverIds = Array(), $force = 0) {

        // this could get messy
        try {

            // check out our required params
            if (! is_array($vserverIds)) { throw new \Exception("ERROR: param vserverIds not array"); }
            if (count($vserverIds) < 1) { throw new \Exception("ERROR: param vserverIds empty"); }

            //
            // we should be good at this point
            //

            // set our required params
            $options = Array();

            // set our required vserverIds
            $count = 1;
            foreach ($vserverIds as $vserverId) {
                if (! preg_match($this->_vserverIdType, $vserverId)) { throw new \Exception("ERROR: invalid vserverId ({$vserverId})"); }
                $options["vserverIds.{$count}"] = $vserverId;
                $count++;
            }

            // set our optional params
            if ($force) { $options['force'] = $force; }

            // perform our request
            $result = $this->_IAASRequest('TerminateVservers', $options);

        } // try

        // handle any exception
        catch (\Exception $e) { $result = $e->getMessage();  }

        // perform our request and return our response
        return $result;

    } // terminateVservers()

} // class Client
