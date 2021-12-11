git filter-branch --env-filter '
NEW_NAME="Sasha Keskin"
EMAIL="mouss42490@gmail.com"


if [ "$GIT_COMMITTER_EMAIL" = "$EMAIL" ]
then
    export GIT_COMMITTER_NAME="$NEW_NAME"
fi
if [ "$GIT_AUTHOR_EMAIL" = "$EMAIL" ]
then
    export GIT_AUTHOR_NAME="$NEW_NAME"
fi
' --tag-name-filter cat -- --branches --tags
