#! /bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )"/.. && pwd )"
PLUGINSLUG="$(basename $DIR)"
TEMPPATH="/tmp/$PLUGINSLUG"
ZIP_PATH="$DIR/../$PLUGINSLUG.zip"
EXCLUDE_FOLDERS=( 
    'bin' 
    '.gitlab-ci.yml' 
    'phpunit.xml.dist' 
    'phpunit.xml' 
    'tests' 
    '.git' 
    '.gitignore' 
    '.gitattributes'
    'Gruntfile.js'
    'nbproject'
    'node_modules'
    'package.json'
    'phpcs.xml.dist'
    'README.md'
    'readme.sh'
    '.DS_Store'
);
EXCLUDE_STRING=""
for i in "${EXCLUDE_FOLDERS[@]}"
do
    echo "Excluding $i"
    EXCLUDE_STRING+=" --exclude=$i"
done
rsync -r --delete $EXCLUDE_STRING $DIR/ $TEMPPATH/

cd $TEMPPATH

echo "Zipping plugin..."
zip -rq $ZIP_PATH ./

echo "Zip file created at "$(dirname $DIR/$PLUGINSLUG)".zip"

echo "Cleaning up..."
rm -rf $TEMPPATH
echo "FIN!"