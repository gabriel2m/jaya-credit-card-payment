<p align="center">
    <picture>
      <source media="(prefers-color-scheme: dark)" srcset="https://jaya.tech/images/logo-white.png" />
      <source media="(prefers-color-scheme: light)" srcset="https://jaya.tech/images/logo-black.png" />
      <img alt="logo" src="https://jaya.tech/images/logo-black.png" />
    </picture>
</p>

## About

This application was created using the Laravel and React frameworks has two modules: a credit card payment management Rest API and a React web interface for making credit card payments.

### API

The API its prefixed with '**rest**' and counts with the following endpoints:

- \[POST\] **/payments** - create a payment
- \[​GET\] **/payments** - payments list
- \[​GET\] **/payments/{id}** - payment details
- \[PATCH\] **/payments/{id}** - confirm a payment
- \[DELETE\] **/payments/{id}** - cancel a payment

API default root url: [http://localhost/rest/](http://localhost/rest/)

## Install

To install this application first you have to have installed **[docker](https://docs.docker.com/engine/install)** and **[docker compose](https://docs.docker.com/compose/install)**.

Once you have them you just need to run the **install** script.

```sh
sh install
```

After install you **need** to set the following environment variables on your .env file:
- **VITE_MERCADO_PAGO_PUBLIC_KEY** - mercado pago public key, you can learn how to get this value on: [https://www.mercadopago.com.br/help/20214](https://www.mercadopago.com.br/help/20214)

You can also edit:
- **NOTIFICATION_URL** - the notification url used on payment creation (default: https://webhook.site/19cd79e4-2df8-4ff1-80c6-647f6172f801)
- **CURRENCY** - the default currency used to manage the payment transaction amount, needs to be a valid currency code - see  [ISO 4217](https://en.wikipedia.org/wiki/ISO_4217) (default: BRL)
