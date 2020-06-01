# oc-graphql
OpenCart GraphQL API Plugin

## How to install:
* Download needed release from [Releases](https://github.com/malsapp/opencart-graphql/releases)
* Go to admin panel >> Extensions >> Extension Installer
* Upload the downloaded zip file
* In case of failure to upload, then install Opencart Quickfix [Opencart Quickfix](https://www.opencart.com/index.php?route=marketplace/extension/info&extension_id=18892) by uploading the XML file included within the extension archive.
* Setting for mobile provider should be configured in plugin settings the resides in the following path: Extensions >> Modules >> Shopz Graphql.

### API Docs and usage:
* API endpoint url is: `https://yoursitedomain.tld/index.php?route=api/graphql/usage`
* Setting this URL as the endpoint in Graphiql, Graphql Playground or any other graphql viewer, you would be able to interact with the API and check the schema docs, supported queries and mutations.