#!/bin/bash

# Script to add layout metadata to all template pages

find resources/js/pages/template -name "*.vue" -type f | while read file; do
  # Check if file already has definePage
  if ! grep -q "definePage" "$file"; then
    # Check if file has <script setup>
    if grep -q "<script setup>" "$file"; then
      # Add definePage after <script setup>
      sed -i '/<script setup>/a\
definePage({\
  meta: {\
    layout: '\''template'\'',\
  },\
})\
' "$file"
      echo "Updated: $file"
    fi
  fi
done

echo "Done!"
