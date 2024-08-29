## Laravel 10 RESTful API

### Endpoint

#### User

- `GET /api/user/register` - Register
- `GET /api/user/login` - Login

#### Category

- `GET /api/categories` - Get all categories
- `POST /api/category` - Create category
- `PUT /api/category/{id}` - Update category
- `DELETE /api/category/{id}` - Delete category

#### Product

- `GET /api/products?category=&min_price=&max_price=` - Get all products (query filter optional)
- `GET /api/product/{id}` - Get product by id
- `POST /api/product` - Create product
- `PUT /api/product/{id}` - Update product
- `DELETE /api/product/{id}` - Delete product

### Installation

#### Clone the repository

```bash
git clone https://github.com/KylianBalpe/coding-test-task2-laravel-restfulapi.git <your-folder-name>
```

```bash
cd <your-folder-name>
```

#### Install dependencies

```bash
composer install
```

#### Create a copy of your .env file

```bash
cp .env.example .env
```

#### Generate an app encryption key

```bash
php artisan key:generate
```

#### Create an empty database for the application

```bash
php artisan migrate
```

#### Unit Test (optional)

```bash
php artisan test
```

#### Start the local development server

```bash
php artisan serve
```
