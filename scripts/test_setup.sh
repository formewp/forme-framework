#!/usr/bin/env bash

# check if wp-test directory exists first and if so then abort
if [ -d "wp-test" ]; then
  echo "The wp-test directory already exists"
  exit 0
fi

# also make sure that wp cli is installed
WPCLI_EXISTS='command -v wp'
if ! $WPCLI_EXISTS &> /dev/null
then
echo "You need to install wp-cli globally to run the configuration script"
exit 0
fi

# install forme base feeding via composer create-project with --no-script so it doesn't run all the scripts
composer create-project forme/base wp-test --no-scripts

# then run composer install-wordpress
cd wp-test
composer install-wordpress
cd ..

pwd=`pwd`

# then run wp config create manually setting dbname, dbuser, dbpass and dbprefix
wp config create --dbname=$(pwd)"/wp-test/testing" --dbuser=dbuser --dbpass=password --dbprefix=wptests_ --skip-check

# wp config set constants WP_ENV, FORME_PRIVATE_ROOT, DB_DIR, DB_FILE, DISABLE_WP_CRON
wp config set WP_ENV testing
wp config set FORME_PRIVATE_ROOT $(pwd)"/"
wp config set DB_DIR $(pwd)"/wp-test/"
wp config set DB_FILE testing.sqlite3
wp config set DISABLE_WP_CRON true --raw

# requires & wrap forme_private_root in if statements
file="wp-test/public/wp-config.php"
# if  sed --version succceds then this is probably a linux system
if sed --version >/dev/null 2>&1; then
sed -i '/\/\* That'\''s all, stop editing\! Happy publishing\. \*\//a\
\
require_once FORME_PRIVATE_ROOT.'"'"'/vendor/autoload.php'"'"';\
' $file

sed -i '/define( '\''FORME_PRIVATE_ROOT'\''/i\
\
if (!defined('"'"'FORME_PRIVATE_ROOT'"'"')) {\
' $file

sed -i '/define( '\''FORME_PRIVATE_ROOT'\''/a\
}\
' $file
# otherwise this is probably a mac, we need to add '' because of ancient sed
else
sed -i '' '/\/\* That'\''s all, stop editing\! Happy publishing\. \*\//a\
\
require_once FORME_PRIVATE_ROOT.'"'"'/vendor/autoload.php'"'"';\
' $file

sed -i '' '/define( '\''FORME_PRIVATE_ROOT'\''/i\
\
if (!defined('"'"'FORME_PRIVATE_ROOT'"'"')) {\
' $file

sed -i '' '/define( '\''FORME_PRIVATE_ROOT'\''/a\
}\
' $file
fi
echo "Success: Require autoload and test bootstrap files"

# run composer init-dot-env
cd wp-test
composer init-dot-env
cd ..

# copy over db.php files
cp stubs/db.php wp-test/public/wp-content/db.php

# wp core install
wp core install --url="http://localhost:8000" --title="Test Site" --admin_user="admin" --admin_password="p455w0rd" --admin_email="moussaclarke@gmail.com"

# activate the plugin
#wp plugin activate s3-transmit-plugin
