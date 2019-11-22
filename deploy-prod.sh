git pull origin master

# Récupére les librairies
composer install --no-dev

#vide le cache

druch cr

#metà jour database
drush updb -y

#export les config
drush csex prod -y

#import les configs

drush cim -y

drush cr

