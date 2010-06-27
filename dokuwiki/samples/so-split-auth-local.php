<?php
/*
 * Dokuwiki's Main Configuration File - Local Settings 
 * Auto-generated by config plugin 
 * Run for user: razvan
 * Date: Mon, 28 Dec 2009 11:41:30 +0200
 */

$conf['title'] = 'SO Wiki';
$conf['start'] = 'home';
$conf['youarehere'] = 1;
$conf['useacl'] = 1;
$conf['superuser'] = '@admin';
$conf['disableactions'] = 'register';
$conf['sneaky_index'] = 1;
$conf['securecookie'] = 0;
$conf['userewrite'] = '1';
$conf['useslash'] = 1;
$conf['sepchar'] = '-';
$conf['plugin']['indexmenu']['page_index'] = 'home:index';
$conf['plugin']['indexmenu']['skip_index'] = '/(home|playground)/';
$conf['plugin']['indexmenu']['skip_file'] = '/^:home:index.txt$/';
$conf['plugin']['creole']['precedence'] = 'creole';


$conf['authtype'] = 'split';

$conf['auth']['split']['login_auth'] = 'ldap';  # the auth backend for authentication
$conf['auth']['split']['groups_auth'] = 'plain'; # the auth backend that supplies groups
$conf['auth']['split']['merge_groups'] = false; # should groups from login auth also be included
$conf['auth']['split']['use_login_auth_for_users'] = true; # Should login auth be used for supplying the list of users for usermanager
$conf['auth']['split']['use_login_auth_for_name'] = true; # Should login auth supply user name, or only used if groups auth provides an empty name
$conf['auth']['split']['use_login_auth_for_mail'] = true; # Should login auth supply email address, or only used if groups auth provides empty email.


$conf['auth']['ldap']['port'] = '636';
$conf['auth']['ldap']['server'] = 'ldaps://swarm.cs.pub.ro';
$conf['auth']['ldap']['usertree'] = 'ou=People,dc=swarm,dc=cs,dc=pub,dc=ro';
$conf['auth']['ldap']['grouptree'] = 'ou=Group,dc=swarm,dc=cs,dc=pub,dc=ro';
$conf['auth']['ldap']['userfilter'] = '(&(uid=%{user})(objectClass=posixAccount))';
$conf['auth']['ldap']['groupfilter'] = '(&(objectClass=posixGroup)(|(gidNumber=%{gid})(memberUID=%{user})))';
$conf['auth']['ldap']['version'] = '3';
$conf['auth']['ldap']['mapping']['name'] = 'cn';
$conf['auth']['ldap']['mapping']['grps'] = 'array(\'memberof\' => \'/CN=(.+?),/i\')';
#$conf['auth']['ldap']['debug'] = '1';

// end auto-generated content
