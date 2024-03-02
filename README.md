<p align="center">
    <picture>
      <source media="(prefers-color-scheme: dark)" srcset="https://jaya.tech/images/logo-white.png" />
      <source media="(prefers-color-scheme: light)" srcset="https://jaya.tech/images/logo-black.png" />
      <img alt="logo" src="https://jaya.tech/images/logo-black.png" />
    </picture>
</p>

## Table of contents
* [Getting Started](#getting-started)
  * [API](#api)
      * [Crating a OAuth2 JWT token](#crating-a-oauth2-jwt-token)
      * [Swagger](#swagger)
          * [api-docs.json](#api-docsjson)
          * [Swagger UI](#swagger-ui)
  * [Web Interface](#web-interface)
      * [Mailpit](#mailpit)
  * [CLI](#cli)
* [Install](#install)
* [Test](#test)
  
## Getting Started
This application has two modules: a credit card payment management Rest API using [Laravel](https://laravel.com/) and a responsive web interface for making credit card payments using [React](https://react.dev/).

### API
The API it's prefixed with '**/rest**' and counts with the following endpoints:

- \[POST\] **/payments** - create a payment
- \[​GET\] **/payments** - paginated payments list
- \[​GET\] **/payments/{id}** - payment details
- \[PATCH\] **/payments/{id}** - confirm a payment
- \[DELETE\] **/payments/{id}** - cancel a payment

Default url: [http://localhost/rest](http://localhost/rest)

The full documentation it's available via [Swagger](https://swagger.io/) as shown below.

To access the API you need to have a valid OAuth2 JWT token.

OAuth2 API default url: [http://localhost/oauth](http://localhost/oauth)

The OAuth2 support it's implemented via [Laravel Passport](https://laravel.com/docs/10.x/passport), for convenience the install script creates a default client whose credentials can be used to generate the access tokens.

But you can create another client if you want or in case you lost the default client credentials:
```sh
./vendor/bin/sail artisan passport:client --client
```

#### Crating a OAuth2 JWT token
Default url: [http://localhost/oauth/token](http://localhost/oauth/token)

You can create a token by making a **POST** request to the token endpoint using the following payload:
```json
{
  "grant_type": "client_credentials",
  "client_id": "CLIENT_ID",
  "client_secret": "CLIENT_SECRET",
  "scope": "*"
}
```

For more details access the Laravel Passport [documentation](https://laravel.com/docs/10.x/passport).

#### Swagger
The API documentation it's supported using [Swagger](https://swagger.io/) and you have two ways to access it:

##### api-docs.json
A json file with the OpenAPI v3 documentation of the API: [api-docs.json](https://raw.githubusercontent.com/gabriel2m/jaya-credit-card-payment/master/storage/api-docs/api-docs.json)

##### Swagger UI
[Swagger UI](https://swagger.io/tools/swagger-ui/) it's a web interface that allows to visualize and interact with the API’s resources.

Default url: [http://localhost/rest/docs](http://localhost/rest/docs)

In order to successfully make requests via Swagger UI first you **need** to generate a OAuth2 JWT token. You can do that by clicking on the **Authorize** button:
<img alt="welcome page" src="https://github.com/gabriel2m/jaya-credit-card-payment/blob/master/docs/img/swagger-ui-authorize-btn.png?raw=true" />

Then you just have to provide your client credentials and click on the Authorize button and your further requests will use the generated token:
<img alt="welcome page" src="https://github.com/gabriel2m/jaya-credit-card-payment/blob/master/docs/img/swagger-ui-authorize-modal.png?raw=true" />

### Web Interface
Default url: [http://localhost](http://localhost)

That's the welcome page:
<img alt="welcome page" src="https://github.com/gabriel2m/jaya-credit-card-payment/blob/master/docs/img/welcome.png?raw=true" />

In order to access the payment form you need to create a user on the register page:
<img alt="register page" src="https://github.com/gabriel2m/jaya-credit-card-payment/blob/master/docs/img/register.png?raw=true" />

Once created you're redirected to the dashboard page were is the payment form:
<img alt="dashboard page" src="https://github.com/gabriel2m/jaya-credit-card-payment/blob/master/docs/img/dashboard.png?raw=true" />

#### Mailpit
[Mailpit](https://mailpit.axllent.org) is the default application email host and you can use him to access the sent emails.

Default url: [http://localhost:8025](http://localhost:8025)

### CLI
For the docker environment and cli commands we use [Sail](https://laravel.com/docs/10.x/sail). 

See the commands list:
```sh
./vendor/bin/sail
```
Case you want to configure a shell alias:
```sh
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```
Start application command:
```sh
./vendor/bin/sail up -d
```
For more details access the sail [documentation](https://laravel.com/docs/10.x/sail).

## Install
To install this application first you have to have installed **[docker](https://docs.docker.com/engine/install)** and **[docker compose](https://docs.docker.com/compose/install)**.

Once you have them you can run the **install** script.

```sh
sh install
```

**Don't forget** to save Client ID and Client secret.

After install you **need** to set the following environment variables in your .env file:
- **VITE_MERCADO_PAGO_PUBLIC_KEY** - mercado pago public key, you can learn how to get this value on: [https://www.mercadopago.com.br/help/20214](https://www.mercadopago.com.br/help/20214)

You can also edit:
- **NOTIFICATION_URL** - the notification url used on payment creation (default: https://webhook.site/19cd79e4-2df8-4ff1-80c6-647f6172f801)
- **CURRENCY** - the default currency used to manage the payment transaction amount, needs to be a valid [ISO 4217](https://en.wikipedia.org/wiki/ISO_4217) currency code (default: BRL)

You can see more configs in the Laravel [documentation](https://laravel.com/docs/10.x/configuration).

Once installed you can access all the applications links listed on the sections above.

## Test
Command to run the application tests:
```sh
./vendor/bin/sail artisan test
```
Command to run eslint:
```sh
./vendor/bin/sail npm run lint
```
