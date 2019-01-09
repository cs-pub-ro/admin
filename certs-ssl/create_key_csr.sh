#!/bin/bash

source ./config

if test $# -ne 1; then
	echo -e "Usage: $0 domain_name\n"
	echo -e "  Examples:\n\t$0 elf.cs.pub.ro\n\t$0 www.rosedu.org\n"
	exit 1
fi

DOMAIN_NAME=$1

setup()
{
	echo -n "* Creating base folders if not existent ... "
	mkdir $KEY_PATH &> /dev/null
	mkdir $CSR_PATH &> /dev/null
	mkdir $CRT_PATH &> /dev/null
	echo "OK"
}

generate()
{
	echo "* openssl: creating private key ... "
	openssl genrsa -out $KEY_PATH/$DOMAIN_NAME.key 4096
	echo -e "* openssl: created private key.\n"

	echo "* openssl: creating Certificate Signing Request ..."
	openssl req -new -key $KEY_PATH/$DOMAIN_NAME.key -out $CSR_PATH/$DOMAIN_NAME.csr
	echo -e "* openssl: create Certificate Signing Request.\n"
}

setup
generate

cat <<__END__
* Created private key in $KEY_PATH/$DOMAIN_NAME.key
* Created CSR in $KEY_PATH/$DOMAIN_NAME.csr

* Please contact a Certification Authority (CA) for signing your CSR. Store the certificate file in $KEY_PATH/$DOMAIN_NAME.crt and then run the enable_crt.sh script.

__END__

exit 0
