#!/usr/bin/env bash

echo "php-cs-fixer pre commit hook start"

PHP_CS_FIXER="./tools/php-cs-fixer"
HAS_PHP_CS_FIXER=false

if command -v $PHP_CS_FIXER &> /dev/null
then
    HAS_PHP_CS_FIXER=true
fi

# exclude plate files
if $HAS_PHP_CS_FIXER; then
    FILES=`git diff --cached --name-only --diff-filter=AM | grep '\.php' | sed '/plate/d'`
    	if [ -z "$FILES" ]
    	then
    		  echo "No php files needing cs checks in this commit."
    	else
    		  $PHP_CS_FIXER fix --verbose --config=.php-cs-fixer.dist ${FILES}
    		  git add ${FILES}
    	fi
else
    echo ""
    echo "Please install php-cs-fixer before committing, e.g.:"
    echo ""
    echo "phive install php-cs-fixer"
    echo ""
    exit 1
fi

echo "php-cs-fixer pre commit hook finish"
