<?php
    /**
     * Test LDAP connectivity
     *
     * @author Razvan Deaconescu <razvan.deaconescu@cs.pub.ro>
     */

    function my_ldap_test() {
        /// Constante
        $LDAP_SERVER = "ldaps://ldap.grid.pub.ro/";
        $LDAP_REQ_OU = array("Calculatoare", "Profesori");  
        $LDAP_BIND_USER = "aaaa,dc=cs,dc=curs,dc=pub,dc=ro";
        $LDAP_BIND_PASS = "bbbb";

        // disable certificate check
        putenv('LDAPTLS_REQCERT=never') or die("Failed to setup the env.\n");
    
        $ds = ldap_connect($LDAP_SERVER) or die("Can't connect to ldap server.\n");
        echo("Preparing to bind.\n");
        if (!@ldap_bind($ds,$LDAP_BIND_USER, $LDAP_BIND_PASS)) {
            ldap_close($ds);
            echo("Can't connect to ".$LDAP_SERVER."\n");
            return false;
        }

        echo("All OK.\n");
        ldap_close($ds);
    }

    my_ldap_test();
?>
