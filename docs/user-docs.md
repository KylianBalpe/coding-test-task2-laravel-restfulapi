# User Docs

## Register

### Endpoint: `POST /api/user/register`

`Content-Type: application/json`

#### Request body

```json
{
    "name": "required, string, max:255",
    "email": "required, string, email, max:100",
    "password": "required, string, min:8, max:100"
}
```

#### Response

- `201`
    ```json
    {
        "ok": true,
        "status": 201,
        "message": "User created successfully",
        "data": {
            "name": "John Doe",
            "email": "email@example.com"
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
            "email": [
                "The email field is required."
            ],
            "password": [
                "The password field is required."
            ]
        }
    }
    ```
- `400`
    ```json
    {
       "ok": false,
       "status": 400,
       "message": "Email already exists"
    }
    ```

## Login

### Endpoint: `POST /api/user/login`

`Content-Type: application/json`

#### Request body

```json
{
    "email": "required, string, email, max:100",
    "password": "required, string, min:8, max:100"
}
```

#### Response

- `200`
    ```json
    {
        "ok": true,
        "status": 200,
        "message": "User logged in successfully",
        "data": {
            "name": "John Doe",
            "email": "email@exampl.com",
            "token": "token"
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
            "email": [
                "The email field is required."
            ],
            "password": [
                "The password field is required."
            ]
        }
    }
    ```
- `400`
    ```json
    {
       "ok": false,
       "status": 401,
       "message": "Email or password is incorrect"
    }
    ```
