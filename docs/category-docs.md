# Category Docs

## Create

### Endpoint: `POST /api/category`

    Content-Type: application/json
    Authorization: Bearer token (admin role)

#### Request body

```json
{
    "name": "required, string, max:100"
}
```

#### Response

- `200`
    ```json
    {
        "ok": true,
        "status": 201,
        "message": "User created successfully",
        "data": {
            "id": 1,
            "name": "Category name"
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
            ]
        }
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

## List

### Endpoint: `POST /api/categories`

    Content-Type: application/json
    Authorization: Bearer token

#### Response

- `200`
    ```json
    {
        "ok": true,
        "status": 200,
        "message": "Categories retrieved successfully",
        "data": [
            {
                "id": 1,
                "name": "Category 1"
            }
        ]
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

## Update

### Endpoint: `PUT /api/category/{id}`

    Content-Type: application/json
    Authorization: Bearer token (admin role)

#### Request body

```json
{
    "name": "required, string, max:100"
}
```

#### Response

- `200`
    ```json
    {
        "ok": true,
        "status": 200,
        "message": "Category updated successfully",
        "data": {
            "id": 1,
            "name": "Category Update"
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
            ]
        }
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

## Delete

### Endpoint: `DELETE /api/category/{id}`

    Authorization: Bearer token (admin role)

#### Response

- `200`
    ```json
    {
        "ok": true,
        "status": 200,
        "message": "Category deleted successfully"
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
