#! /bin/bash
# A modification of Dean Clatworthy's deploy script as found here: https://github.com/deanc/wordpress-plugin-git-svn
# The difference is that this script lives in the plugin's git repo & doesn't require an existing SVN repo.

# main config
export DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )"/.. && pwd )"
export PLUGINSLUG="$(basename $DIR)"  #must match with wordpress.org plugin slug
export MAINFILE="$PLUGINSLUG.php" # this should be the name of your main php file in the wordpress plugin

##### YOU CAN STOP EDITING HERE #####

# git config
GITPATH="$DIR/" # this file should be in the base of your git repository

# svn config
SVNPATH="/tmp/$PLUGINSLUG" # path to a temp SVN repo. No trailing slash required and don't add trunk.
SVNURL="https://plugins.svn.wordpress.org/$PLUGINSLUG/" # Remote SVN repo on wordpress.org, with no trailing slash

# Detect svn username based on url
SVNUSER=$(cat ~/.subversion/auth/svn.simple/* | grep -A4 $(echo $SVNURL | awk -F// '{print $2}' | cut     -d'/' -f1) | tail -n1)
if [ -z "$SVNUSER" ]
then
	SVNUSER="saurabhshukla"
fi


# Let's begin...
echo ".........................................."
echo
echo "Preparing to deploy wordpress plugin assets"
echo
echo ".........................................."
echo

cd $GITPATH
#!/bin/bash

echo
echo "Creating local copy of SVN repo ..."
svn co $SVNURL $SVNPATH

echo "Exporting plugin assets from git to the assets of SVN"
git archive --format=tar HEAD:org-assets/ | (cd $SVNPATH/assets/ && tar xf -)

echo "Ignoring github specific files and deployment script"
svn propset svn:ignore "*.?
*.??
*.???
*.[!p]??
*.?[!n]?
*.??[!g]
*.????*" "$SVNPATH/assets/"

echo -e "Enter a commit message: \c"
read COMMITMSG

echo "Changing directory to SVN and committing to assets"
cd $SVNPATH/assets/
# Add all new files that are not set to be ignored
svn status | grep -v "^.[ \t]*\..*" | grep "^?" | awk '{print $2}' | xargs svn add
svn commit --username=$SVNUSER -m "$COMMITMSG"

echo "Removing temporary directory $SVNPATH"
rm -fr $SVNPATH/

echo "*** FIN ***"
