
# SyncIO

## Assumptions

- All products have a recognisable structure with images and variants
- Extra attributes allowed, but ignored
- Payload that does not conform is invalid and will be rejected
- If product ID is different, do not compare changes as it would not be an apples-to-apples comparison
- The following attributes are expected to change:
  - title
  - description
  - image position
  - image url
  - variant sku
  - variant barcode
  - variant image
  - variant quantity
  - images can be added, updated and removed
  - variants can be added, updated and removed

## Getting Started

```shell
composer install
php artisan serve

php artisan test
```

## API

The api requires the following headers to be set:

```shell
Content-Type: application/json
Accept: application/json
```

### Update product in store.

```http request
PUT http://127.0.0.1:8000/api/products/432232523
```
