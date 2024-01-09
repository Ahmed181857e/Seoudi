#  Custom Login Module

## Overview

The  Custom Login module extends the Magento 2 GraphQL API to provide additional functionality related to customer login and store configuration.

## Features

- **IsLoggedIn Field:** Adds a new GraphQL field (`isLoggedIn`) to the Root Query in Magento GraphQL API to check if the customer is logged in.

- **Custom Field in Store Configuration:** Adds a new configuration field (`customField`) in the store configuration, accessible through the `storeConfig` Magento GraphQL query.

## Installation


## Usage

### GraphQL Queries

#### IsLoggedIn:

```graphql
{
  isLoggedIn
}
```
## Configuration

### Store Configuration

The module adds a new section under `System > Configuration > General > Seoudi Configuration` named "Custom Login Configuration," where you can configure the "Custom Field." You can edit the custom field value based on your requirements.
## Installation

1. Create a directory `Seoudi/Customlogin` inside `app/code` if it doesn't exist..
2. Clone this repository inside the directory using the command: `git clone https://github.com/Ahmed181857e/magento2-order-source-device.git .`
3. Run  `bin/magento module:enable Seoudi_Customlogin`.
4. Run `bin/magento setup:di:compile`.
5. Run `bin/magento cache:clean`.
