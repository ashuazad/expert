<?php
class config extends db {
    private $configs = array();
    function __construct()
    {
        $dbObj = new db();
        $dbObj->connectDB();
        $query = mysql_query("SELECT * FROM configs");
        while($result = mysql_fetch_array($query)){
            $this->configs[$result['config_key']] = array(
                                                            'config_value' => $result['config_value'],
                                                            'config_group' => $result['config_group']
                                                         );
        }
    }

    function getConfigValue( $keyName ) {
       return (!empty($this->configs[$keyName]) && isset($this->configs[$keyName])) ? $this->configs[$keyName] : false;
    }

    function setConfigKey( $keyName, $value, $groupName) {
        if(!empty($this->configs[$keyName]) && isset($this->configs[$keyName])){
            mysql_query("UPDATE configs SET config_value = '" . $value . "', config_group = '" . $groupName . "' WHERE config_key = '" . $keyName . "'");
        }else{
            mysql_query("INSERT INTO configs (`id`, `config_key`, `config_group`, `config_value`) VALUES (NULL, '" . $keyName . "', '" . $value . "', '" . $groupName . "')");
        }
    }
}