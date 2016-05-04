<?php
    include "config.php";
    
    // descriptors of database connections
    // $rt_mysqli - to theirs db
    // $info_mysqli - to our db
    $rt_mysqli;   
    $info_mysqli;
    
    // establish 2 database connectionos and write descriptors 
    // into $rt_mysqli and $info_mysqli
    function establishConnections() {
        global $rt_db_host; 
        global $rt_db_user;        
        global $rt_db_password;
        global $rt_db_name; 

        global $rt_mysqli;

        global $info_db_host; 
        global $info_db_user;        
        global $info_db_password;
        global $info_db_name; 

        global $info_mysqli;
    
        $rt_mysqli = mysqli_connect($rt_db_host, $rt_db_user, $rt_db_password, $rt_db_name);
        if (mysqli_connect_errno($rt_mysqli)) {
            die($rt_mysqli->connect_error);
        }
        
        $info_mysqli = mysqli_connect($info_db_host, $info_db_user, $info_db_password, $info_db_name);
        if (mysqli_connect_errno($info_mysqli)) {
            die($info_mysqli->connect_error);
        }
    }

    function closeConnections() {
        global $rt_mysqli, $info_mysqli;
        
        mysqli_close($rt_mysqli);
        mysqli_close($info_mysqli);
    }
    
    // Run select query to `person` table for getting rows only with `account_type==seller`
    function getSellers() {
        global $info_mysqli;
        $errorMsg = "Error while querying sellers' logins.";
        $query = "select id, login from person where access_type=1;";
        
        if ($result = mysqli_query($info_mysqli, $query)) {
            return $result;
        } else {
            die($errorMsg);
        }
    }
   
    // This function gets a particular seller, runs query to `kmn_sl_nach `
    // to get all info about this seller. Then it updates or inserts approriate rows in `sells` table.
    function updateSales($seller) {
        global $rt_mysqli, $info_mysqli;
        global $rt_table_name;
        
        $errorNoSellerMsg = "Login field is null";
        $errorBadSelectionMsg = "Error while selectiong data from " . $rt_table_name;
        $errorUpdateMsg = "Error while updating data in sells table for " . $seller["login"];
        
        if ($seller) {
            $query_select_sales = "select period, sum(tariff) as tariff, sum(inv_charges) as inv_charges from " .
                    $rt_table_name . " where login=\"" . $seller["login"] . "\" group by period;";     
                    
            if ($result = mysqli_query($rt_mysqli, $query_select_sales)) {
                while($row = mysqli_fetch_assoc($result)) { 
                    $period = $row["period"];
                    $seller_id = $seller["id"];
                    $value = 0;
                    $tariff = $row["tariff"];
                    $inv_charges = $row["inv_charges"];
                    
                    if ($tariff != null) {
                        $value = $value + $tariff;
                    }
                    if ($inv_charges != null) {
                        $value = $value + $inv_charges;
                    }
                                        
                    $query_update = "update sells set value=" . $value . " where seller=" . $seller_id . " and date=\"" . $period . "\";";
                        
                    $query_insert = "insert into sells (date, seller, value) values(\"" . $period . "\", " . $seller_id . ", " . $value . ");";
                    
                    mysqli_query($info_mysqli, $query_update);
                    if (mysqli_affected_rows($info_mysqli) == 0) {
                        mysqli_query($info_mysqli, $query_insert);
                        if (mysqli_affected_rows($info_mysqli) == 0) {
                            die($errorUpdateMsg);
                        }
                    }
                } 
                mysqli_free_result($result); 
            } else {
                die($errorBadSelectionMsg);
            }        
        } else {
            die($errorNoSellerMsg);
        }
    }
   
    // Main function. You must call it for updating `sells` table.
    function fetchData() {
        establishConnections();
        
        $sellers = getSellers();
              
        while($row = mysqli_fetch_assoc($sellers)) {         
            updateSales($row);
        } 
        mysqli_free_result($sellers); 
       
        closeConnections();
    }
?>