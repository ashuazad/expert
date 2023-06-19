<?php
class communication extends db{
    public function markDefaultPerType($id, $api_type) {
        $sqlClearDefault = "UPDATE sms_api SET status = 0 WHERE type = '".$api_type."'";
        mysql_query($sqlClearDefault);
        $sqlMarkDefault = "UPDATE sms_api SET status = 1 WHERE id = '".$id."'";
        mysql_query($sqlMarkDefault);
        return true;
    }

    public function validateAPI($id) {
        $apiDetails = false;
        $apiDetails = $this->getData(array('status','type'),'sms_api', "id = '".$id."'");
        return $apiDetails;
    }
}