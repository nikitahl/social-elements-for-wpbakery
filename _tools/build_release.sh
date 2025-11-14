#!/bin/bash

# Define the zip file name
ZIP_FILE="social-elements-for-wpbakery.zip"

# Remove any existing zip file to avoid conflicts
if [ -f "$ZIP_FILE" ]; then
  rm "$ZIP_FILE"
fi

# Add the designated files and folders to the zip file directly
zip -r "$ZIP_FILE" \
  *.php \
  readme.txt \
  assets \
  includes \
  languages \
  -x "*.DS_Store" "ci/*" "_tools/*" ".github/*" "README.md" ".gitignore" "composer.js" "LICENSE" # Exclude unnecessary files like macOS metadata
