#!/bin/bash

# Script to add template- prefix to all route names in navigation files

# List of navigation files to update
files=(
  "resources/js/navigation/vertical/apps-and-pages.js"
  "resources/js/navigation/vertical/charts.js"
  "resources/js/navigation/vertical/forms.js"
  "resources/js/navigation/vertical/others.js"
  "resources/js/navigation/vertical/ui-elements.js"
  "resources/js/navigation/horizontal/apps.js"
)

# Patterns to replace (route names that should have template- prefix)
# We'll use sed to add template- prefix to route names

for file in "${files[@]}"; do
  if [ -f "$file" ]; then
    echo "Updating $file..."
    
    # Replace route names with template- prefix
    # This will match patterns like: to: 'apps-xxx' or to: { name: 'apps-xxx'
    sed -i "s/to: 'apps-/to: 'template-apps-/g" "$file"
    sed -i "s/to: 'pages-/to: 'template-pages-/g" "$file"
    sed -i "s/to: 'components-/to: 'template-components-/g" "$file"
    sed -i "s/to: 'forms-/to: 'template-forms-/g" "$file"
    sed -i "s/to: 'charts-/to: 'template-charts-/g" "$file"
    sed -i "s/to: 'tables-/to: 'template-tables-/g" "$file"
    sed -i "s/to: 'wizard-examples-/to: 'template-wizard-examples-/g" "$file"
    sed -i "s/to: 'extensions-/to: 'template-extensions-/g" "$file"
    
    # Replace in name: 'xxx' patterns
    sed -i "s/name: 'apps-/name: 'template-apps-/g" "$file"
    sed -i "s/name: 'pages-/name: 'template-pages-/g" "$file"
    sed -i "s/name: 'components-/name: 'template-components-/g" "$file"
    sed -i "s/name: 'forms-/name: 'template-forms-/g" "$file"
    sed -i "s/name: 'charts-/name: 'template-charts-/g" "$file"
    sed -i "s/name: 'tables-/name: 'template-tables-/g" "$file"
    sed -i "s/name: 'wizard-examples-/name: 'template-wizard-examples-/g" "$file"
    sed -i "s/name: 'extensions-/name: 'template-extensions-/g" "$file"
    
    echo "✓ Updated $file"
  else
    echo "⚠ File not found: $file"
  fi
done

echo "Done!"
