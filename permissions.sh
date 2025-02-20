#!/bin/bash

#find . -type f -exec chmod 644 {} \;
#find public/pages/ -type f -name "*.php" -exec chmod 644 {} \;
#find config/ -type f -name "*.php" -exec chmod 600 {} \;
#find storage/ -type f -name "*.php" -exec chmod 600 {} \;
find . -type f -name "*.sh" -exec chmod +x {} \;
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod +x permissions.sh
