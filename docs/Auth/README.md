# Login API Endpoint Documentation

## Overview

Authenticates a user and returns a bearer token for subsequent API requests.

**Endpoint:** `POST /api/v1/login`

## Request

### Headers

```
Content-Type: application/json
Accept: application/json
```

### Body Parameters

| Parameter  | Type   | Required | Description          |
| ---------- | ------ | -------- | -------------------- |
| `email`    | string | Yes      | User's email address |
| `password` | string | Yes      | User's password      |

### Example Request

```json
{
    "email": "admin@shaqexpress.com",
    "password": "your-password"
}
```

## Responses

### Success Response (200 OK)

```json
{
    "message": "Login Successful!",
    "data": {
        "user": {
            "id": "9d8ca543-8b0b-4016-a13c-25b86d8b4dbf",
            "name": "Naj Admin",
            "email": "admin@shaqexpress.com",
            "email_verified_at": "2024-11-22T11:12:16.000000Z",
            "role": "Administrator",
            "created_at": "2024-11-22T11:12:16.000000Z",
            "updated_at": "2024-11-22T11:12:16.000000Z"
        },
        "token": "8|ZDQFyDRDZsJLMxNhjtbBPaVV0aurQkIIzL6QpAPPeca1dfa7"
    }
}
```

### Error Responses

#### Invalid Credentials (401 Unauthorized)

```json
{
    "message": "Invalid credentials",
    "errors": {
        "email": ["These credentials do not match our records."]
    }
}
```

#### Validation Error (422 Unprocessable Entity)

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required.",
            "The email must be a valid email address."
        ]
    }
}
```

## Usage Notes

1. The returned token should be included in subsequent API requests as a Bearer token:

    ```
    Authorization: Bearer 8|ZDQFyDRDZsJLMxNhjtbBPaVV0aurQkIIzL6QpAPPeca1dfa7
    ```

2. The token has no expiration by default. Store it securely.

3. Email verification status is included in the response (`email_verified_at`).

## Example Usage

### cURL

```bash
curl -X POST \
  'http://your-domain.com/api/v1/login' \
  -H 'Content-Type: application/json' \
  -H 'Accept: application/json' \
  -d '{
    "email": "admin@shaqexpress.com",
    "password": "your-password"
  }'
```

### JavaScript (Fetch)

```javascript
const response = await fetch("http://your-domain.com/api/v1/login", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
    body: JSON.stringify({
        email: "admin@shaqexpress.com",
        password: "your-password",
    }),
});

const data = await response.json();
```
