git fetch upstream
git rebase upstream/main

IF %ERRORLEVEL% EQ 0 (
  git push -f origin main
)
