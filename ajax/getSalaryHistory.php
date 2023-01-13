<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
session_start();
if(!$_SESSION['id']){
    header('Location: ' . constant('BASE_URL'));
    exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj = new db();
$record = array();

$date = 'CURDATE()';
$incentWhere = "MONTH(a.last_receipt_date) = MONTH(CURDATE()) AND YEAR(a.last_receipt_date) = YEAR(CURDATE())";
$sqlIncentive = "SELECT 
	            	TRUNCATE(SUM(IF(a.insentive_amt='',ROUND((a.total_fee*l.insentive/100), 2),a.insentive_amt)),0) AS insentive_cal	  
                FROM 
                    admission a, 
                    login_accounts l 
                WHERE 
                    a.emp_id=l.id  AND
                    a.due_fee = 0 AND ";

if (empty($_POST['whereCnd'])) {
    $sqlIncentive .= $incentWhere;
} else {
    $sqlIncentive .= $_POST['whereCnd'];
}
$histryWhere = "MONTH(payment_month) = MONTH(CURDATE()) AND YEAR(payment_month) = YEAR(CURDATE())";
if (!empty($_POST['whereHistory'])) {
    $histryWhere = $_POST['whereHistory'];
}
if (!empty($_POST['noDate']) && $_POST['noDate'] == '1') {
    $histryWhere .= " AND (MONTH(payment_month) = MONTH(CURDATE()) AND YEAR(payment_month) = YEAR(CURDATE())) ";
}
$branchWhere = 'branch_id IS NOT NULL';
if (!empty($_POST['onlyBranch'])) {
    $branchWhere = $_POST['onlyBranch'];
}

$whereEmpId = '';
if (!empty($_POST['whereEmpId'])) {
    $whereEmpId = " " . $_POST['whereEmpId'];
}
$empHistoryFlag = 0;
if (!empty($_POST['empHistoryFlag'])) {
    $empHistoryFlag = $_POST['empHistoryFlag'];
}

$sql = "SELECT * FROM    
            (SELECT 
                id AS history_id,
                emp_id,
                branch_id,
                salary,
                insentive,
                target,
                (SELECT CONCAT(la.first_name,' ',la.last_name) FROM login_accounts as la WHERE la.id = empHyt.emp_id) AS name,
                IF(status IS NULL ,'Pending',status) AS status,
                IF(action_date IS NULL ,'NONE',DATE_FORMAT( action_date,'%d-%m-%y')) AS action_date,
                DATE_FORMAT(payment_month,'%M-%Y') AS payment_month 
            FROM 
                emp_payment_history as empHyt
            WHERE 
            " . $histryWhere . "  ORDER BY empHyt.payment_month DESC ) AS salaryHistory      
            UNION
            SELECT
                'NA' AS history_id,  
                l.id AS emp_id,
                branch_id,
                l.salary, 
                'NA' AS insentive, 
                'NA' AS target,
                CONCAT(first_name,' ',last_name)  AS name,
                'Pending' AS status,
                'NONE' AS action_date,
                'NONE' AS payment_month
            FROM 
                login_accounts as l 
            WHERE 
                l.id NOT IN
                    (SELECT 
                        emp_id 
                    FROM 
                        emp_payment_history AS empHyt 
                    WHERE 
                       " . $histryWhere . "
                     )
                AND
                l.status = 1       
                AND 
                  " . $branchWhere . $whereEmpId ."  
                ";

/*echo $sql;
exit;*/
$record = array();
$totalPayment = 0;
$totalIncentive = 0;
$totalTarget = 0;
$totalSalaryPerM = 0;

if ($empHistoryFlag == 2) {
    $srchData = explode('|',$_POST['empHistorySearch']);
    //Get Emp Salary Details
    $sqlEmpDtl = "SELECT
                        '0' AS history_id,  
                        l.id AS emp_id,
                        branch_id,
                        l.salary, 
                        '0' AS target,
                        CONCAT(first_name,' ',last_name)  AS name,
                        'Pending' AS status,
                        'NONE' AS action_date,
                        'NONE' AS payment_month 
                      FROM 
                        login_accounts AS l 
                      WHERE 
                        id = " . $srchData[2];
    $resultEmpDtl = mysql_query($sqlEmpDtl);
    $rowEmpDtl = mysql_fetch_assoc($resultEmpDtl);
    //Get Emp Salary Details


    $result = mysql_query($sql);
    $incentDateData = array();
    while($row = mysql_fetch_assoc($result)) {
        $incentDateData[$row['pd']] = $row;
    }
    $monthRange = getMonthRange($srchData[0],$srchData[1]);
    $whereMonth = implode("','" , $monthRange);
    usort($monthRange,'date_sort');
    rsort($monthRange);
    $sqlPayHistory = "SELECT 
                                empHyt.id AS history_id,
                                emp_id,
                                empHyt.branch_id,
                                empHyt.salary,
                                empHyt.insentive,
                                empHyt.target,
                                IF(empHyt.status IS NULL ,'Pending',empHyt.status) AS status,
                                IF(action_date IS NULL ,'NONE',DATE_FORMAT( action_date,'%d-%m-%y')) AS action_date,
                                DATE_FORMAT(payment_month,'%M-%Y') AS payment_month,
                                CONCAT(l.first_name,' ',l.last_name)  AS name,
                                total_salary AS totalSalary,
                                IF(action_date IS NULL ,'NONE',DATE_FORMAT( action_date,'%d-%m-%y')) AS action_date,
                                DATE_FORMAT(payment_month,'%Y-%m') AS hstry_month                                
                            FROM 
                                emp_payment_history as empHyt,
                                login_accounts AS l  
                            WHERE 
                                empHyt.emp_id = l.id AND
                                DATE_FORMAT(empHyt.payment_month,'%Y-%m') IN('" . $whereMonth . "') AND emp_id = " . $srchData[2];

    $resultPayHistory = mysql_query($sqlPayHistory);
    $rowPayHistory = array();
    if  (mysql_num_rows($resultPayHistory)) {
        while ($eachPayHistory = mysql_fetch_assoc($resultPayHistory)) {
            $rowPayHistory[$eachPayHistory['hstry_month']] = $eachPayHistory;
        }
    }
    $row = array();
    foreach ($monthRange as $month) {
        if(array_key_exists($month,$rowPayHistory)){
            $row[] =  $rowPayHistory[$month];
        }else{
            $sqlInsentive_cal = "SELECT 
                                    DATE_FORMAT(a.last_receipt_date,'%M-%Y') AS payment_month,
                                    TRUNCATE(SUM(IF(a.insentive_amt='',ROUND((a.total_fee*l.insentive/100), 2),a.insentive_amt)),0) AS insentive_cal 
                                FROM 
                                    admission a, 
                                    login_accounts l 
                                WHERE 
                                    a.emp_id=l.id AND 
                                    a.due_fee = 0 AND 
                                    (DATE_FORMAT(a.last_receipt_date,'%Y-%m') = '" . $month . "') AND  
                                    a.emp_id = ".$srchData[2];

            $resultInsentive_cal = mysql_query($sqlInsentive_cal);
            if (mysql_num_rows($resultInsentive_cal)>0) {
                $rowInsentive_cal = mysql_fetch_assoc($resultInsentive_cal);
                if (is_null($rowInsentive_cal['insentive_cal'])) {
                    $rowInsentive_cal['insentive_cal'] = 0;
                }
                if (is_null($rowInsentive_cal['payment_month'])) {
                    $dateM=date_create($month);
                    $rowInsentive_cal['payment_month'] = date_format($dateM,"F-Y");
                }
            }
            $rowEmpDtl['insentive'] = $rowInsentive_cal['insentive_cal'];
            $rowEmpDtl['totalSalary'] = intval($rowEmpDtl['salary']) + intval($rowEmpDtl['insentive']) + intval($rowEmpDtl['target']);
            $rowEmpDtl['payment_month'] = $rowInsentive_cal['payment_month'];
            $row[] = $rowEmpDtl;
        }
    }
    foreach ($row as $eachRecord) {
        $totalPayment += $eachRecord['totalSalary'];
        $totalIncentive += $eachRecord['insentive'];
        $totalTarget += $eachRecord['target'];
        $totalSalaryPerM += $eachRecord['salary'];
    }
    $record['rows'] = $row;
   // print_r($row);
} else {
    if (!empty($_POST['onlyBranch'])) {
        $srchData = explode('|',$_POST['empHistorySearch']);
        $monthRange = getMonthRange($srchData[0],$srchData[1]);
        usort($monthRange,'date_sort');
        rsort($monthRange);
        $branchId = $_POST['branchId'];
        $rowBranch = array();
        foreach ($monthRange as $eachMonth) {
            $sqlBranchMonth = "SELECT 
                                    empHyt.id AS history_id,
                                    emp_id,
                                    empHyt.branch_id,
                                    empHyt.salary,
                                    empHyt.insentive,
                                    empHyt.target,
                                    IF(empHyt.status IS NULL ,'Pending',empHyt.status) AS status,
                                    IF(action_date IS NULL ,'NONE',DATE_FORMAT( action_date,'%d-%m-%y')) AS action_date,
                                    DATE_FORMAT(payment_month,'%M-%Y') AS payment_month,
                                    CONCAT(l.first_name,' ',l.last_name)  AS name,
                                    total_salary AS totalSalary
                                FROM 
                                    emp_payment_history as empHyt,
                                    login_accounts AS l  
                                WHERE 
                                    empHyt.emp_id = l.id AND
                                    DATE_FORMAT(empHyt.payment_month,'%Y-%m') ='".$eachMonth."' AND empHyt.branch_id = " . $branchId .
                                " UNION 
                                SELECT
                                    'NA' AS history_id,  
                                    l.id AS emp_id,
                                    branch_id,
                                    l.salary, 
                                    'NA' AS insentive, 
                                    'NA' AS target,
                                    'Pending' AS status,
                                    'NONE' AS action_date,                                    
                                    DATE_FORMAT('".$eachMonth."-01','%M-%Y') AS payment_month,                                    
                                    CONCAT(first_name,' ',last_name)  AS name,
                                    'NONE' AS totalSalary
                                FROM 
                                    login_accounts as l 
                                WHERE 
                                    l.id NOT IN
                                        (SELECT 
                                            emp_id 
                                        FROM 
                                            emp_payment_history AS empHyt 
                                        WHERE 
                                           DATE_FORMAT(empHyt.payment_month,'%Y-%m') ='".$eachMonth."' AND empHyt.branch_id = " . $branchId . " 
                                         )
                                    AND
                                    l.status = 1       
                                    AND                 
                                    branch_id = " . $branchId;
                //echo $sqlBranchMonth;
                $resultBranchMonth =mysql_query($sqlBranchMonth);
                while($rowBranchMonth = mysql_fetch_assoc($resultBranchMonth)) {
                    if ($rowBranchMonth['insentive'] == 'NA') {
                        $sqlInsentive_cal = "SELECT 
                                    TRUNCATE(SUM(IF(a.insentive_amt='',ROUND((a.total_fee*l.insentive/100), 2),a.insentive_amt)),0) AS insentive_cal 
                                FROM 
                                    admission a, 
                                    login_accounts l 
                                WHERE 
                                    a.emp_id=l.id AND 
                                    a.due_fee = 0 AND 
                                    (DATE_FORMAT(a.last_receipt_date,'%Y-%m') = '" . $eachMonth . "') AND  
                                    a.emp_id = ".$rowBranchMonth['emp_id'];
                        //echo $sqlInsentive_cal;
                        $resultInsentive_cal = mysql_query($sqlInsentive_cal);
                        $rowInsentive_cal = mysql_fetch_assoc($resultInsentive_cal);
                        if ($rowInsentive_cal['insentive_cal'] == null) {
                            $rowBranchMonth['insentive'] = 0;
                        } else {
                            $rowBranchMonth['insentive'] = $rowInsentive_cal['insentive_cal'];
                        }
                    }
                    if ($rowBranchMonth['target'] == 'NA') {
                        $rowBranchMonth['target'] = 0;
                    }
                    if ($rowBranchMonth['totalSalary'] == 'NONE') {
    $rowBranchMonth['totalSalary'] = $rowBranchMonth['insentive']+$rowBranchMonth['target']+$rowBranchMonth['salary'];
                    }
                    $totalPayment += $rowBranchMonth['totalSalary'];
                    $totalIncentive += $rowBranchMonth['insentive'];
                    $totalTarget += $rowBranchMonth['target'];
                    $totalSalaryPerM += $rowBranchMonth['salary'];
                    $rowBranch[] = $rowBranchMonth;
                }
        }
        $record['rows'] = $rowBranch;
        //print_r($rowBranch);
    } else {
        $result = mysql_query($sql);
        if  (mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) {
                $inctSql = '';
                /* Get Incentive */
                if ($row['insentive'] == 'NA') {
                    if (empty($_POST['whereCnd'])) {
                        $inctSql = $sqlIncentive . " AND a.emp_id = " . $row['emp_id'];
                    }
                    if (!empty($_POST['onlyBranch'])) {
                        $inctSql = $sqlIncentive . " AND a.emp_id = " . $row['emp_id'];
                    }
                    $inctSql = $sqlIncentive . " AND a.emp_id = " . $row['emp_id'];
                    /*echo "<br>";
                    var_dump($inctSql);
                    echo "<br>";*/
                    $inctSum = mysql_fetch_assoc(mysql_query($inctSql));
                    $row['insentive'] = (is_null($inctSum['insentive_cal'])) ? 0 : $inctSum['insentive_cal'];
                }
                /* Get Incentive */
                $row['totalSalary'] = 0;
                $row['totalSalary'] += intval($row['salary']) + intval($row['insentive']) + intval($row['target']);
                $row['target'] = ($row['target'] == 'NA') ? 0 : $row['target'];
                $row['history_id'] = ($row['history_id'] == 'NA') ? 0 : $row['history_id'];
                $totalPayment += $row['totalSalary'];
                $totalIncentive += $row['insentive'];
                $totalTarget += $row['target'];
                $totalSalaryPerM += $row['salary'];
                $record['rows'][] = $row;
            }
        }
    }
}

$record['totalPayment'] = $totalPayment;
$record['totalIncentive'] = $totalIncentive;
$record['totalTarget'] = $totalTarget;
$record['totalSalaryPerM'] = $totalSalaryPerM;

//$record['sql'] = $sql;
echo json_encode($record);
?>
