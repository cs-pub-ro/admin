#!/bin/bash

source ./config

if test $# -ne 1; then
	echo -e "Usage: $0 site_name (vhost)\n"
	echo -e "  Examples:\n\t$0 elf.cs.pub.ro\n\t$0 www.rosedu.org\n"
	exit 1
fi

SITE_NAME=$1
SSL_SITE_NAME=$1-ssl
SITE_FILE=$APACHE_SITES/$SITE_NAME
SSL_SITE_FILE=$APACHE_SITES/$SSL_SITE_NAME

KEY_FILE=$KEY_PATH/$SITE_NAME.key
CRT_FILE=$CRT_PATH/$SITE_NAME.crt

if ! test -f $SITE_FILE; then
	echo "error: No such vhost ($SITE_NAME). $SITE_FILE mising."
	exit 1
fi

if ! test -f $KEY_FILE; then
	echo "error: No key file ($KEY_FILE)."
	exit 1
fi

if ! test -f $CRT_FILE; then
	echo "error: No certificate file ($CRT_FILE)."
	exit 1
fi

cat $SITE_FILE | grep -v '^<\/VirtualHost>' > $SSL_SITE_FILE

cat >> $SSL_SITE_FILE <<__END__
	#   SSL Engine Switch:
	#   Enable/Disable SSL for this virtual host.
	SSLEngine on

	#   Specify certificate file and private key file for SSL.
	SSLCertificateFile	$CRT_FILE
	SSLCertificateKeyFile	$KEY_FILE

	<FilesMatch "\.(cgi|shtml|phtml|php)$">
		SSLOptions +StdEnvVars
	</FilesMatch>
	<Directory /usr/lib/cgi-bin>
		SSLOptions +StdEnvVars
	</Directory>

	#   Similarly, one has to force some clients to use HTTP/1.0 to workaround
	#   their broken HTTP/1.1 implementation. Use variables "downgrade-1.0" and
	#   "force-response-1.0" for this.
	BrowserMatch ".*MSIE.*" \\
		nokeepalive ssl-unclean-shutdown \\
		downgrade-1.0 force-response-1.0

</VirtualHost>
</IfModule>
__END__

sed -i 's/<VirtualHost.*$/<IfModule mod_ssl.c>\n<VirtualHost *:443>/' $SSL_SITE_FILE

a2ensite $SSL_SITE_NAME
/etc/init.d/apache2 reload
