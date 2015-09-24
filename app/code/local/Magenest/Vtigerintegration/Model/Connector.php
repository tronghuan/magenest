<?php

class Magenest_Vtigerintegration_Model_Connector {

    public $_vtiger_fields = array();
    public $_magento_fields = array();
    public $_type;
    protected $sessionId;
    protected $userId;
    protected $userName;
    protected $accessKey;
    protected $url;

    public function __construct() {
        $this->sessionId = Mage::getStoreConfig('vtigerintegration/sessionId');
        $this->userId = Mage::getStoreConfig('vtigerintegration/userId');
        $this->url = Mage::getStoreConfig('vtigerintegration/auth/client_id');

        $this->userName = Mage::getStoreConfig('vtigerintegration/auth/user_id');
        $this->accessKey = Mage::getStoreConfig('vtigerintegration/auth/security_token');
        if (!$this->sessionId && !$this->userId) {
            $response = $this->getConnector();
            $this->userId = $response['result']['userId'];
            $this->sessionId = $response['result']['sessionName'];
        }
    }

    public function getMagentoFields() {
        $model = Mage::getModel('vtigerintegration/field')->load($this->_type, 'type');
        $this->_magento_fields = serialize($model->getMagentoFields($this->_type));
        return unserialize($this->_magento_fields);
    }

    public function getVtigerFields() {
//        echo $this->_type;die();
        $this->_vtiger_fields = $this->getField($this->_type);
        return $this->_vtiger_fields;
    }

    public function getField($elementType) {
        try {
            $ch = $this->getConnector();
            $sessionId = $ch['result']['sessionName'];
            // $elementType = 'Contacts';

            $url = 'http://vtigercrm.magenest.com/webservice.php';

            $params = "operation=describe&sessionName=$sessionId&elementType=$elementType";
            $curl = curl_init($url);
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url . '?' . $params,
            ));

            $json_response = curl_exec($curl);
            $result = json_decode($json_response, true);
            curl_close($curl);

            // curl_setopt($curl, CURLOPT_HEADER, false);
            // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($curl, CURLOPT_HTTPGET, true);
            // //curl_setopt($curl, CURLOPT_POST , true);
            // // curl_setopt($curl, CURLOPT_HTTPHEADER, "Content-type: application/json");
            // curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            // $result = curl_exec ( $curl );
            // curl_close ( $curl );
            //print_r($result);
            $field_vtiger = array();
            foreach ($result['result']['fields'] as $key => $value) {

                $str_name = $value['name'];
                $label = $value['label'];
                $field_vtiger[$str_name] = $label;
            }
            //print_r($field_vtiger);
            return $field_vtiger;
            // print_r($result['result']['fields']);
        } catch (Exception $exception) {
            
        }
    }

    //Send data Vtiger CRM
    public function sendCurlRequest($data, $moduleName) {
        try {
            $sessionId = $this->sessionId;
            $data['assigned_user_id'] = $this->userId;
            $url = $this->url;
            $objectJson = json_encode($data);

            $params = "sessionName=$sessionId&operation=create&element=$objectJson&elementType=$moduleName";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // // curl_setopt($curl, CURLOPT_HTTPGET, true);
            curl_setopt($curl, CURLOPT_POST, true);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, "Content-type: application/json");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

            $result = curl_exec($curl);
            curl_close($curl);
            return $result;
        } catch (Exception $exception) {
            
        }
    }

    //Conector Vtiger
    public function getConnector() {
        $userName = $this->userName;
        $url = $this->url;
        $params = 'operation=getchallenge&username='.$userName;
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url . '?' . $params,
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        $response = json_decode($resp, true);

        $challengeToken = $response['result']['token'];

        $accessKey = $this->accessKey;
        $token = md5($challengeToken . $accessKey);
        $params = 'operation=login&username='.$userName.'&accessKey='.$token;
        // $params = 'operation=getchallenge&username=admin';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // // curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $json_response = curl_exec($curl);
        $response = json_decode($json_response, true);
        curl_close($curl);

        $userId = $response['result']['userId'];
        $sessionId = $response['result']['sessionName'];
        $config = Mage::getModel('core/config');
        $config->saveConfig('vtigerintegration/sessionId', $sessionId, 'default', 0);
        $config->saveConfig('vtigerintegration/userId', $userId, 'default', 0);

        return $response;
    }

    // convert from Magento to Vtiger
    public function getSource($data, $mapping) {
        $source = array();
        foreach ($data as $magento_field => $data_to_vtiger) {
            $vtiger_field = $this->getMap($magento_field, $mapping);

            //if(isset($data[$magento_field])&&$data[$magento_field])
            //{
            if (!empty($vtiger_field)) {
                $source[$vtiger_field] = $data_to_vtiger;
            }
            //}
        }
        return $source;
    }

    /**
     * @param $string $vtiger_field
     * @return $string
     */
    public function getMap($magento_field, $type) {
        $collection = Mage::getModel('vtigerconnector/map')->getCollection()
                ->addFieldToFilter('type', $type)
                ->addFieldToFilter('magento', $magento_field)
                ->addFieldToFilter('status', 1)
                ->getFirstItem();
        $vtiger_field = $collection->getVtiger();
        return $vtiger_field;
    }

    /*
     * curl query params
     */

    public function curl($params) {
        $url = $this->url;
        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url . '?' . $params,
        ));

        $json_response = curl_exec($curl);
        $result = json_decode($json_response, true);
        curl_close($curl);
        return $result;
    }

    public function searchRecords($table, $field, $value) {

        $model = $this->getConnector();
        $userId = $model['result']['userId'];
        $sessionId = $model['result']['sessionName'];
        $query = "SELECT * FROM " . $table . " WHERE " . $field . " = '" . $value . "';";
        $queryRecord = urlencode($query);
        $params = "sessionName=$sessionId&operation=query&query=$queryRecord";

        $result = $this->curl($params);
        if (!empty($result['result'])) {
            $resultId = $result['result'][0]['id'];
            return $resultId;
        } else {
            return false;
        }
    }

    public function updateRecords($table, $id, $object) {
        $sessionId = $this->sessionId;

        $query = "select * from $table where id=$id;";
        $queryParam = urlencode($query);
        $url = 'http://vtigercrm.magenest.com/webservice.php';
        $params = "sessionName=$sessionId&operation=query&query=$queryParam";

        $curl = curl_init($url);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url . '?' . $params,
        ));

        $response = curl_exec($curl);
        $jsonResponse = json_decode($response, true);
        $retrievedObject = $jsonResponse['result'];
        curl_close($curl);

        foreach ($retrievedObject[0] as $key => $value) {
            foreach ($object as $key1 => $value1) {
                if ($key === $key1) {
                    $retrievedObject[0][$key] = $value1;
                }
            }
        }

        $objectJson = json_encode($retrievedObject[0]);
        $updateParams = "sessionName=$sessionId&operation=update&element=$objectJson";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // // curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, "Content-type: application/json");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $updateParams);

        $response = curl_exec($curl);
        $jsonResponse = json_decode($response, true);
        $updatedObject = $jsonResponse['result'];
        curl_close($curl);
    }

    public function deleteRecords($id) {

        $sessionId = $this->sessionId;

        $url = $this->url;
        $params = "sessionName=$sessionId&operation=delete&id=$id";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // // curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, "Content-type: application/json");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $response = curl_exec($curl);
        $jsonResponse = json_decode($response, true);
        curl_close($curl);
    }

}
