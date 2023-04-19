<?php 
date_default_timezone_set('Asia/Kolkata');
class Sendgrid_Mail{
    
    function sendMail( $data = array() ){
        $url = 'https://api.sendgrid.com/';
        $user = 'admin@expertinstitute.in';
        $pass = 'password@2019@)!(';
        
        $json_string = array('to' => $data['to_list']);
       $fromName = (isset($data['fromName']) && !empty($data['fromName'])) ? $data['fromName'] : 'EXPERT™';
        $params = array(
            'api_user'  => $user,
            'api_key'   => $pass,
            'x-smtpapi' => json_encode($json_string),
            'to'        => $data['to'],
            'subject'   => $data['subject'],
            'html'      => $data['message'],
            //'from'      => 'admin@expertinstitute.in',
            'from'      => $data['from'],
            //'fromname'  => 'EXPERT™'
            'fromname'  => $fromName
          );
        

        $request =  $url.'api/mail.send.json';
        
        // Generate curl request
        $session = curl_init($request);
        // Tell curl to use HTTP POST
        curl_setopt ($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        // Tell PHP not to use SSLv3 (instead opting for TLS)
        curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        
        // obtain response
        $response = curl_exec($session);
        curl_close($session);

	error_log( "\n" . print_r( date('y-m-d H:i:s') . ' ' . json_encode($json_string['to']),true) . ':' . print_r($response,true), 3, 'sendGridMail.log' );			
    }

    function getLeadEmailSettings()
    {
        mysql_set_charset('utf8');
        $leadSettings = array('leadSubject'=>'EXPERT™','leadEmail'=>'admin@expertinstitute.in','fromName'=>'EXPERT™');
        $sqlEmail = "SELECT setting_group,setting_value,from_name FROM email_settings WHERE setting_group IN('leadEmail','leadSubject') AND status=1";
        $result = mysql_query($sqlEmail);
        while ($row = mysql_fetch_assoc($result)) {
            if ($row['setting_group'] == 'leadSubject') {
                $leadSettings['leadSubject'] = $row['setting_value'];
            }
            if ($row['setting_group'] == 'leadEmail') {
                $leadSettings['leadEmail'] = $row['setting_value'];
            }
            if (strlen($row['from_name'])) {
                $leadSettings['fromName'] = $row['from_name'];
            }
        }
        return $leadSettings;
    }

    function getAdmEmailSettings()
    {
        $admissionSettings = array('admissionEmail'=>'EXPERT™','admissionSubject'=>'admin@expertinstitute.in','fromName'=>'EXPERT™');
        mysql_set_charset('utf8');
        $sqlEmail = "SELECT setting_group,setting_value,from_name FROM email_settings WHERE setting_group IN('admissionEmail','admissionSubject') AND status=1";
        $result = mysql_query($sqlEmail);
        while ($row = mysql_fetch_assoc($result)) {
            if ($row['setting_group'] == 'admissionSubject') {
                $admissionSettings['admissionSubject'] = $row['setting_value'];
            }
            if ($row['setting_group'] == 'admissionEmail') {
                $admissionSettings['admissionEmail'] = $row['setting_value'];
            }
            if (strlen($row['from_name'])) {
                $admissionSettings['fromName'] = $row['from_name'];
            }
        }
        return $admissionSettings;
    }
}

