# Product Docs

## Create Product

### Endpoint: `POST /api/product`

    Content-Type: application/json
    Authorization: Bearer token (admin role)

#### Request body

```json
{
    "name": "required, string, max:255",
    "description": "required, string",
    "price": "required, integer",
    "category_id": "required, integer"
}
```

#### Response

- `201`
    ```json
    {
        "ok": true,
        "status": 201,
        "message": "Product created successfully",
        "data": {
            "id": 1,
            "name": "Product",
            "description": "Product Description",
            "price": 5000,
            "category": "Category 2"
        }
    }
    ```
- `422`
    ```json
    {
        "ok": false,
        "status": 422,
        "message": "Validation error",
        "errors": {
            "name": [
                "The name field is required."
            ],
            "description": [
                "The description field is required."
            ],
            "price": [
                "The price field is required."
            ],
            "category_id": [
                "The category id field is required."
            ]
        }
    }
    ```
- `404`
    ```json
    {
        "ok": false,
        "status": 404,
        "message": "Category not found"
    }
    ```

- `401`
    ```json
    {
        "ok": false,
        "status": 401,
        "message": "Unauthenticated"
    }
    ```

- `403`
    ```json
    {
        "ok": false,
        "status": 403,
        "message": "Unauthorized"
    }
    ```

## Update Product

### Endpoint: `PUT /api/product/{id}`

    Content-Type: application/json
    Authorization: Bearer token (admin role)

#### Request body

```json
{
    "name": "required, string, max:255",
    "description": "required, string",
    "price": "required, integer",
    "category_id": "required, integer"
}
```

#### Response

- `200`
    ```json
    {
        "ok": true,
        "status": 200,
        "message": "Product updated successfully",
        "data": {
            "id": 1,
            "name": "Product Update",
            "description": "Product Description Update",
            "price": 9000,
            "category": "Category 2"
        }
    }
    ```
- `422`
    ```json
    {
        "ok": false,
        "status": 422,
        "message": "Validation error",
        "errors": {
            "name": [
                "The name field is required."
            ],
            "description": [
                "The description field is required."
            ],
            "price": [
                "The price field is required."
            ],
            "category_id": [
                "The category id field is required."
            ]
        }
    }
    ```
- `404`
    ```json
    {
        "ok": false,
        "status": 404,
        "message": "Product not found"
    }
    ```
    ```json
    {
        "ok": false,
        "status": 404,
        "message": "Category not found"
    }
    ```

- `401`
    ```json
    {
        "ok": false,
        "status": 401,
        "message": "Unauthenticated"
    }
    ```

- `403`
    ```json
    {
        "ok": false,
        "status": 403,
        "message": "Unauthorized"
    }
    ```

## Get Product

### Endpoint: `GET /api/product/{id}`

    Content-Type: application/json
    Authorization: Bearer token

#### Response

- `200`
    ```json
    {
        "ok": true,
        "status": 200,
        "message": "Product retrieved successfully",
        "data": {
            "id": 1,
            "name": "Product Update",
            "description": "Product Description Update",
            "price": 9000,
            "category": "Category 2"
        }
    }
    ```

- `404`
    ```json
    {
        "ok": false,
        "status": 404,
        "message": "Product not found"
    }
    ```

- `401`
    ```json
    {
        "ok": false,
        "status": 401,
        "message": "Unauthenticated"
    }
    ```

## Delete Product

### Endpoint: `DELETE /api/product/{id}`

    Content-Type: application/json
    Authorization: Bearer token (admin role)

#### Response

- `200`
    ```json
    {
        "ok": true,
        "status": 200,
        "message": "Product deleted successfully"
    }
    ```

- `404`
    ```json
    {
        "ok": false,
        "status": 404,
        "message": "Product not found"
    }
    ```

- `401`
    ```json
    {
        "ok": false,
        "status": 401,
        "message": "Unauthenticated"
    }
    ```

- `403`
    ```json
    {
        "ok": false,
        "status": 403,
        "message": "Unauthorized"
    }
    ```

## List Product

### Endpoint: `GET /api/products?category=&min_price=&max_price=`

    Content-Type: application/json
    Authorization: Bearer token

### Query Parameters

- `category`: string (optional)
- `min_price`: integer (optional)
- `max_price`: integer (optional)

#### Response

- `200`
    ```json
    {
        "ok": true,
        "status": 200,
        "message": "Products retrieved successfully",
        "data": []
    }
    ```

- `401`
    ```json
    {
        "ok": false,
        "status": 401,
        "message": "Unauthenticated"
    }
    ```
