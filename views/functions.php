<?php

    /**
     * VARIABLES AND BINDINGS
     */

    function connect() {
        $domainname= "deneme.lab";
        $user = "administrator@".$domainname;
        $pass = "123123Aa";
        $server = 'ldaps://192.168.1.69';
        $port="636";
        $binddn = "DC=deneme,DC=lab";
        $searchbase = "DC=deneme,DC=lab";

        $ldap = ldap_connect($server);
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_NEVER);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
    
        $bind=ldap_bind($ldap, $user, $pass);
        if (!$bind) {
            exit('Binding failed');
        }

        return $ldap;
    }

    /**
     * ---List Users--- Tab 
     */

    function listUsers() {
        $ldap = connect();
        $filter = "objectClass=user";
        $search = ldap_search($ldap, "CN=Users,DC=deneme,DC=lab", $filter);
        $entries = ldap_get_entries($ldap,$search);

        $count = ldap_count_entries($ldap, $search);
        $data = [];
        for($i=0 ; $i<$count ; $i++){
            $nameItem = $entries[$i]["name"][0];
            $data[] = [
                "name" => $nameItem
            ];
        }
        ldap_close($ldap);
        return $data;
    }

    function showListedUsers(){
        $data = [];
        $data = listUsers();
        return view('table', [
            "value" => $data,
            "title" => ["Users"],
            "display" => ["name"],
        ]); 
    }

    /**
     * ---List PCs--- Tab
     */

     function listComputers(){
        $ldap = connect();
        $filter = "objectClass=computer";
        $search = ldap_search($ldap, "CN=Users,DC=deneme,DC=lab", $filter);
        $entries = ldap_get_entries($ldap,$search);

        $count = ldap_count_entries($ldap, $search);
        $data = [];
        for($i=0 ; $i<$count ; $i++){
            $nameItem = $entries[$i]["name"][0];
            $data[] = [
                "name" => $nameItem
            ];
        }
        ldap_close($ldap);
        return $data;
     }

     function showListedComputers(){
        $data = [];
        $data = listComputers();
        return view('table', [
            "value" => $data,
            "title" => ["PCs"],
            "display" => ["name"],
        ]); 
    }

    /**
     * ---Admin Attributes--- Tab
     */

     function listAdminAttributes(){
        $ldap = connect();
        $cn="administrator";
        $dn_user="CN=".$cn;

        $search = ldap_search($ldap, "DC=deneme,DC=lab", $dn_user);
        $entries = ldap_get_entries($ldap,$search);

        $data=[];
        for($i=0 ; $i<$entries[0]["count"] ; $i++){
            $name = $entries[0][$i];
            for($j=0 ; $j<$entries[0][$name]["count"] ; $j++){
                $value = $entries[0][$name][$j];
                $data[] = [
                    "name" => $name,
                    "value" => $value
                ];
            }
        }
        ldap_close($ldap);
        return $data;
     }

     function showListedAdminAttributes(){
        $data = [];
        $data = listAdminAttributes();
        return view('table', [
            "value" => $data,
            "title" => ["Attribute Name", "Attribute Value"],
            "display" => ["name", "value"],
        ]); 
    }


/*    // SEARCH
    ldapsearchuser("administrator",$searchbase,$domainname,$ldap);
    ldapsearch("cn=administrator",$searchbase,$domainname,$ldap,["cn","samaccountname"]);    
    
    tab1("cn=administrator",$searchbase,$domainname,$ldap,["cn","samaccountname"]);

    function index()
    {
        $myArray = [
            "key1" => "value", 
        ];
        print_r($myArray);
        return view('index');
    }
    
    function index(){
        return view('index');
    }
*/
/*
    function ldapsearch($filter,$dn,$domainname,$ldap,$attributes) {
        $search = ldap_search($ldap, $dn, $filter, $attributes);
        $info = ldap_get_entries($ldap, $search);
        print_r($info);
    }
    
    function ldapsearchuser($cn,$dn,$domainname,$ldap) {
        $dn_user="CN=".$cn;
        $search = ldap_search($ldap, $dn, $dn_user);
        $info = ldap_get_entries($ldap, $search);

        $data = [];

        for ($i=0; $i<5; $i++) { 
            $data[] = [
                "users" => $info[0]['memberof'][$i],
            ];
        }
        
        return $data;
    }
*/

function tab1() {

}
?>