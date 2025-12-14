#!/bin/bash

# Script to add template- prefix to route names in Vue components and other files

echo "Updating route references in template pages and views..."

# Update .vue files in template directory
find resources/js/pages/template -name "*.vue" -type f -exec sed -i \
  -e "s/name: 'apps-/name: 'template-apps-/g" \
  -e "s/name: 'pages-/name: 'template-pages-/g" \
  -e "s/name: 'components-/name: 'template-components-/g" \
  -e "s/name: 'forms-/name: 'template-forms-/g" \
  -e "s/name: 'charts-/name: 'template-charts-/g" \
  -e "s/name: 'tables-/name: 'template-tables-/g" \
  -e "s/name: 'wizard-examples-/name: 'template-wizard-examples-/g" \
  -e "s/name: 'extensions-/name: 'template-extensions-/g" \
  -e "s/useRoute('apps-/useRoute('template-apps-/g" \
  -e "s/useRoute('pages-/useRoute('template-pages-/g" \
  -e "s/useRoute('components-/useRoute('template-components-/g" \
  -e "s/useRoute('forms-/useRoute('template-forms-/g" \
  -e "s/useRoute('charts-/useRoute('template-charts-/g" \
  -e "s/useRoute('tables-/useRoute('template-tables-/g" \
  -e "s/useRoute('wizard-examples-/useRoute('template-wizard-examples-/g" \
  -e "s/useRoute('extensions-/useRoute('template-extensions-/g" \
  {} \;

# Update .vue files in views directory (for template-related views)
find resources/js/views -name "*.vue" -type f -exec sed -i \
  -e "s/name: 'apps-/name: 'template-apps-/g" \
  -e "s/name: 'pages-/name: 'template-pages-/g" \
  -e "s/name: 'components-/name: 'template-components-/g" \
  -e "s/name: 'forms-/name: 'template-forms-/g" \
  -e "s/name: 'charts-/name: 'template-charts-/g" \
  -e "s/name: 'tables-/name: 'template-tables-/g" \
  -e "s/name: 'wizard-examples-/name: 'template-wizard-examples-/g" \
  -e "s/name: 'extensions-/name: 'template-extensions-/g" \
  {} \;

# Update fake API handlers
find resources/js/plugins/fake-api -name "*.js" -type f -exec sed -i \
  -e "s/name: 'apps-/name: 'template-apps-/g" \
  -e "s/name: 'pages-/name: 'template-pages-/g" \
  -e "s/name: 'components-/name: 'template-components-/g" \
  -e "s/name: 'forms-/name: 'template-forms-/g" \
  -e "s/name: 'charts-/name: 'template-charts-/g" \
  -e "s/name: 'tables-/name: 'template-tables-/g" \
  -e "s/name: 'wizard-examples-/name: 'template-wizard-examples-/g" \
  -e "s/name: 'extensions-/name: 'template-extensions-/g" \
  {} \;

echo "✓ Updated route references in Vue components and fake API handlers"
echo "Done!"
