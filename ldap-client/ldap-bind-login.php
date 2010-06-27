<?php

/**
 * Obtinere detalii login prin LDAP
 * 
 * @credits heri, razvand
 */
function do_login($user, $pass)
{
	/// Constante
	$LDAP_SERVER = "ldaps://ldap.grid.pub.ro/";
	$LDAP_REQ_OU = array("Calculatoare", "Profesori");	
	$LDAP_BIND_USER = "uid=aaaa,dc=cs,dc=curs,dc=pub,dc=ro";
	$LDAP_BIND_PASS = "bbbb";

	// Prima conexiune:
	$ds = ldap_connect($LDAP_SERVER) or die("Can't connect to ldap server.\n");
	if (!@ldap_bind($ds,$LDAP_BIND_USER, $LDAP_BIND_PASS)) {
		ldap_close($ds);
		die("Can't connect to ".$LDAP_SERVER."\n");
		return false;
	}
	$sr = ldap_search($ds, "dc=cs,dc=curs,dc=pub,dc=ro", "(uid=" . $user . ")");  
	if (ldap_count_entries($ds, $sr) > 1)
		die("Multiple entries with the same uid in LDAP database??");
	if (ldap_count_entries($ds, $sr) < 1) {
		ldap_close($ds);
		return false;
	}
	
	$info = ldap_get_entries($ds, $sr);
	$dn = $info[0]["dn"];
	ldap_close($ds);

	// A doua conexiune:
	$ds = ldap_connect($LDAP_SERVER) or die("Can't connect to ldap server\n");
	if (!@ldap_bind($ds, $dn, $pass) or $pass == '') {
		ldap_close($ds);
		return false;
	}
	
	// Verific apartenenta la Student, Asistent etc
	$info[0]['ou_ok'] = apartine( $dn, $LDAP_REQ_OU );
	
	//print_r($info); die;
	// Returnez info obtinut
	return $info[0];
}
 
/**
 * Verifica apartenenta unui user la un anumit grup
 * De fapt lucru pe siruri
 * @param $dn = "ou=Student,ou=People..."
 * @param $ou = array ("Student", etc
 * @return true or false
 */
function apartine($dn, $ou)
{
	if (!is_array($ou))
		$ou = array ($ou);
		
	$gasit = false;
	$cuvinte = explode(',', $dn);
	foreach ($cuvinte as $c) {
		$parts = explode("=", $c);
		$key = $parts[0];
		$value = $parts[1];
		
		if (strtolower($key) == "ou" && in_array($value, $ou) )
			$gasit = true;
	}
	return $gasit;
}

// Incerc conectare cu date din forma post
$user = "razvan.deaconescu";
$pass = "xyztuvw";

$info = do_login($user, $pass);

// Daca userul nu exista
if (!$info) {
	echo "error: Utilizator inexistent";
	die;
}
// Daca nu am acces (Asistent, Student etc)
if (!$info['ou_ok']) {
	echo "student";
	die;
}

// Creez user_info din informatiile primite prin LDAP:
$user_info = array ( 
	'id' => $info['uidnumber'][0],
	'firstname' => $info['givenname'][0],
	'lastname' => $info['sn'][0],
	'email' => $info['mail'][0],
	'roleid' => (apartine($info['dn'], "Asistent")? 1 : 4) // Deocamdata asa. 
	);

print_r($user_info);

?>
