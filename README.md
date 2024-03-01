<p align="center">
    <picture>
      <source media="(prefers-color-scheme: dark)" srcset="https://jaya.tech/images/logo-white.png" />
      <source media="(prefers-color-scheme: light)" srcset="https://jaya.tech/images/logo-black.png" />
      <img alt="logo" src="https://jaya.tech/images/logo-black.png" />
    </picture>
</p>

## Getting Started
This application has two modules: a credit card payment management Rest API using [Laravel](https://laravel.com/) and a web interface for making credit card payments using [React](https://react.dev/).

### API
The API its prefixed with '**/rest**' and counts with the following endpoints:

- \[POST\] **/payments** - create a payment
- \[​GET\] **/payments** - paginated payments list
- \[​GET\] **/payments/{id}** - payment details
- \[PATCH\] **/payments/{id}** - confirm a payment
- \[DELETE\] **/payments/{id}** - cancel a payment

Default url: [http://localhost/rest](http://localhost/rest)

To access the API you need to have a valid OAuth2 JWT token.

OAuth2 API default url: [http://localhost/oauth](http://localhost/oauth)

The OAuth2 support it's implemented via [Laravel Passport](https://laravel.com/docs/10.x/passport), for convenience the install script creates a default client whose credentials can be used to generate the access tokens.

If you want to create another client:
```sh
./vendor/bin/sail artisan passport:client --client
```

#### Create OAuth2 JWT token
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

### Web Interface
Default url: [http://localhost](http://localhost)

That's the welcome page:
<img alt="welcome page" src="https://github.com/gabriel2m/jaya-credit-card-payment/blob/master/docs/img/welcome.png?raw=true" />

In order to access the payment form you need to create a user on the register page:
<img alt="register page" src="https://github.com/gabriel2m/jaya-credit-card-payment/blob/master/docs/img/register.png?raw=true" />

Once created you're redirected to the dashboard page were is the payment form:
<img alt="dashboard page" src="https://github.com/gabriel2m/jaya-credit-card-payment/blob/master/docs/img/dashboard.png?raw=true" />

#### Mailpit
[Mailpit](https://mailpit.axllent.org) is the default application email host, you can use him to access the sent emails.

Default url: [http://localhost:8025](http://localhost:8025)

### CLI
For the docker environment and cli commands we use [Sail](https://laravel.com/docs/10.x/sail). The commands list:
```sh
./vendor/bin/sail
```
If want to configure a shell alias:
```sh
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```
Start the application:
```sh
./vendor/bin/sail up -d
```
For more details access the sail [documentation](https://laravel.com/docs/10.x/sail).

## Install
To install this application first you have to have installed **[docker](https://docs.docker.com/engine/install)** and **[docker compose](https://docs.docker.com/compose/install)**.

Once you have them you just need to run the **install** script.

```sh
sh install
```

**Don't forget** to save Client ID and Client secret.

After install you **need** to set the following environment variables in your .env file:
- **VITE_MERCADO_PAGO_PUBLIC_KEY** - mercado pago public key, you can learn how to get this value on: [https://www.mercadopago.com.br/help/20214](https://www.mercadopago.com.br/help/20214)

You can also edit:
- **NOTIFICATION_URL** - the notification url used on payment creation (default: https://webhook.site/19cd79e4-2df8-4ff1-80c6-647f6172f801)
- **CURRENCY** - the default currency used to manage the payment transaction amount, needs to be a valid currency code - see  [ISO 4217](https://en.wikipedia.org/wiki/ISO_4217) (default: BRL)

See more configs in the Laravel [documentation](https://laravel.com/docs/10.x).
