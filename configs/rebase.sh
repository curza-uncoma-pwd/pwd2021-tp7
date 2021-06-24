#!/bin/sh
set -e

git fetch upstream
git rebase upstream/main
git push -f origin main
