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
        $result = ldap_search($ldap, "CN=Users,DC=deneme,DC=lab", $filter);
        $entries = ldap_get_entries($ldap, $result);
        $count = ldap_count_entries($ldap, $result);
        $data = [];
        for($i=0 ; $i<$count ; $i++){
            $nameItem = $entries[$i]["name"][0];
            $data[] = [
                "name" => $nameItem
            ];
        }
        ldap_close($ldap);
        return view('table', [
            "value" => $data,
            "title" => ["Users"],
            "display" => ["name"],
            "menu" => [
                "Delete User" => [
                    "target" => "deleteUser",
                    "icon" => "fa-trash"
                ],

                "Edit User" =>[
                    "target" => "showEditNameModal",
                    "icon" => "fa-edit"
                ],
            ],
        ]);
    }

    /**
     * Right Click Delete User Function on List User Tab
     */

    function deleteUser() {
        $ldap = connect();
        $cn = request('userName');
        $dn_user="CN=".$cn;
        $dn = $dn_user . ",CN=Users,DC=deneme,DC=lab";
        if(ldap_delete($ldap, $dn)) {
            ldap_close($ldap);
            return respond("User is successfully deleted!");
        }
        else {
            ldap_close($ldap);
            return respond("ERROR! User cannot be deleted!");
        }
    }

    function editUser() {
        $ldap = connect();
        $cn = request('userName');
        $dn_user = "CN=".$cn;
        $newUsrName = request('newUsrName');
        
    }

    function addUser() {
        $ldap = connect();
        $cn = request('userName');
        $dn_user = "CN=".$cn;
        $dn = $dn_user . ",CN=Users,DC=deneme,DC=lab";
        
        $entry["objectclass"][0] = "top";
        $entry["objectclass"][1] = "person";
        $entry["objectclass"][2] = "organizationalPerson";
        $entry["objectclass"][3] = "user"; 
        
        if(ldap_add($ldap, $dn, $entry)) {
            ldap_close($ldap);
            return respond("User is successfully added!");
        }
        else {
            ldap_close($ldap);
            return respond("ERROR! User cannot be added!");
        }
    }

    /**
     * ---List PCs--- Tab
     */

     function listComputers(){
        $ldap = connect();
        $filter = "objectClass=computer";
        $result = ldap_search($ldap, "CN=Users,DC=deneme,DC=lab", $filter);
        $entries = ldap_get_entries($ldap,$result);

        $count = ldap_count_entries($ldap, $result);
        $data = [];
        for($i=0 ; $i<$count ; $i++){
            $nameItem = $entries[$i]["name"][0];
            $data[] = [
                "name" => $nameItem
            ];
        }
        ldap_close($ldap);
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


        /*
        print_r($attributes);
        
        $result = ldap_search($ldap, "DC=deneme,DC=lab", $dn_user);
        
        if(isset($attributes)) {
//            print_r($attributes);
            $attributesArray = explode(",", $attributes);
 //           print_r($attributesArray);
            $result = ldap_search($ldap, "DC=deneme,DC=lab", $dn_user, $attributesArray);
        }
        else{
//            print_r("set edilmedi");
            $result = ldap_search($ldap, "DC=deneme,DC=lab", $dn_user);
        }
        */
        /*
        if (str_contains(strval($attributes), ',')) {
            $attributesArray = explode(",", $attributes);
            $result = ldap_search($ldap, "DC=deneme,DC=lab", $dn_user, $attributesArray);
        }
        else{
            if(strlen($attributes) != 0) {
                $result = ldap_search($ldap, "DC=deneme,DC=lab", $dn_user, $attributes);
            }  
            else {
                $result = ldap_search($ldap, "DC=deneme,DC=lab", $dn_user);
            }
        }
        */
//        $attributesArray = explode(",", $attributes);
//        $result = ldap_search($ldap, "DC=deneme,DC=lab", $dn_user, $attributesArray);
        
        $result = ldap_search($ldap, "DC=deneme,DC=lab", $dn_user);
        $entries = ldap_get_entries($ldap,$result);
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
        return view('table', [
            "value" => $data,
            "title" => ["Attribute Name", "Attribute Value"],
            "display" => ["name", "value"],
        ]);
     }

     function showListedAdminAttributes(){
        $data = [];

        $attributes = request('attributes');
        $data = listAdminAttributes($attributes);

        return view('table', [
            "value" => $data,
            "title" => ["Attribute Name", "Attribute Value"],
            "display" => ["name", "value"],
        ]);
    }

?>