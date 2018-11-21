# Wordpress ConvertLoop Plugin

This plugins integrates Wordpress with Convertloop.

## Development setup

```bash
cd /path/to/wp/wp-content/plugins/
git clone git@bitbucket.org:dazzet/convertloop-wordpress
cd convertloop-wordpress
composer install
```

## Deployment on a test server

You can save this script in a file called `deploy.sh` changing the `path` and the `username` of the remote server

```bash
REMOTE_USER=myusername
REMOTE_SERVER=example.com
REMOTE_PATH=/path/to/wp/wp-content/plugins/`basename ${PWD}` # No trailing '/'
EXCLUDE="--exclude=.* --exclude=*.zip --exclude=*.md --exclude=composer* --exclude=*.sh"

echo "Syncing to ${REMOTE_PATH} on ${REMOTE_SERVER}"
sleep 3

composer dump-autoload --no-dev -o

rsync -avz -e ssh --delete ${EXCLUDE} ./* ${REMOTE_USER}@${REMOTE_SERVER}:${REMOTE_PATH}/
```

## Create zip plugin file

```bash
composer dump-autoload --no-dev -o
composer zip
```

## Translation

You can use loco-translate for the string extraction and translation. Be sure to save the `.pot` file in the `languages` directory
