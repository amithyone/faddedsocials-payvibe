# External API Documentation

## Base URL
```
https://fadded.net/api
```

## Authentication

All API endpoints require authentication using an API key in the request header.

### Header
```
X-API-Key: your_api_key_here
```

### Getting Your API Key

Contact the system administrator to obtain your API key. The API key should be kept secure and not shared publicly.

---

## Git Asset Management API (Product Management)

### 1. List Products with Stock

Get a list of all products with their available stock (number of unsold items).

**Endpoint:** `GET /api/git/products/list`

**Headers:**
```
X-API-Key: your_api_key_here
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| category_id | integer | No | Filter by category ID |
| status | integer | No | Filter by product status (1 = active, 0 = inactive) |
| min_stock | integer | No | Only show products with at least this many items in stock |
| only_in_stock | boolean | No | If true, only show products with available stock |
| limit | integer | No | Number of items per page (max 500, default 50) |
| page | integer | No | Page number (default 1) |

**Example Request:**
```bash
curl -H "X-API-Key: your_api_key_here" \
     "https://fadded.net/api/git/products/list?only_in_stock=true&limit=50"
```

**Example Response:**
```json
{
    "success": true,
    "data": {
        "products": [
            {
                "product_id": 123,
                "product_name": "Product Name",
                "product_amount": 5000.00,
                "category_id": 1,
                "status": 1,
                "available_stock": 25,
                "description": "Product description..."
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 50,
            "total": 100,
            "total_pages": 2
        }
    }
}
```

---

### 2. Retrieve/Pull Products

Pull products from inventory. Products can be archived (marked as sold) or removed (deleted).

**Endpoint:** `POST /api/git/assets/retrieve`

**Headers:**
```
X-API-Key: your_api_key_here
Content-Type: application/json
```

**Request Body:**
```json
{
    "asset_id": 123,
    "quantity": 5,
    "action": "archive",
    "processed_by": "system_1"
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| asset_id | integer | Yes | Product ID to pull from |
| quantity | integer | Yes | Number of items to pull (min 1, max 1000) |
| action | string | Yes | Action to perform: "archive" (mark as sold) or "remove" (delete) |
| processed_by | string | No | Identifier for tracking who/what processed this (default: "system") |

**Example Request:**
```bash
curl -X POST https://fadded.net/api/git/assets/retrieve \
     -H "X-API-Key: your_api_key_here" \
     -H "Content-Type: application/json" \
     -d '{
       "asset_id": 123,
       "quantity": 5,
       "action": "archive",
       "processed_by": "external_system_1"
     }'
```

**Example Response:**
```json
{
    "success": true,
    "message": "5 assets retrieved successfully",
    "data": {
        "asset_id": 123,
        "asset_name": "Product Name",
        "asset_amount": 5000.00,
        "retrieved_count": 5,
        "asset_details": [
            {
                "id": 456,
                "product_id": 123,
                "product_name": "Product Name",
                "product_amount": 5000.00,
                "details": "Product detail data...",
                "status": "archived"
            }
        ]
    }
}
```

**Error Responses:**
- `400` - Validation error or insufficient stock
- `404` - Product not found
- `500` - Server error

---

### 3. List Asset Logs

View history of all pulled/retrieved products.

**Endpoint:** `GET /api/git/assets/logs`

**Headers:**
```
X-API-Key: your_api_key_here
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| asset_id | integer | No | Filter by product ID |
| process_type | string | No | Filter by process type: "archive" or "remove" |
| processed_by | string | No | Filter by processor identifier |
| date_from | string | No | Filter from date (YYYY-MM-DD) |
| date_to | string | No | Filter to date (YYYY-MM-DD) |
| limit | integer | No | Number of items per page (max 500, default 50) |
| page | integer | No | Page number (default 1) |

**Example Request:**
```bash
curl -H "X-API-Key: your_api_key_here" \
     "https://fadded.net/api/git/assets/logs?asset_id=123&limit=50"
```

**Example Response:**
```json
{
    "success": true,
    "data": {
        "logs": [
            {
                "id": 789,
                "asset_id": 123,
                "asset_name": "Product Name",
                "asset_detail_id": 456,
                "process_type": "archive",
                "processed_by": "external_system_1",
                "asset_data": {
                    "product_detail_id": 456,
                    "product_id": 123,
                    "details": "Product detail data...",
                    "created_at": "2024-01-15 10:30:00"
                },
                "created_at": "2024-01-15 10:30:00"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 50,
            "total": 200,
            "total_pages": 4
        }
    }
}
```

---

## SEO Management API (Deposit Management)

### 4. List Deposits (Analytics)

Get a list of all deposits with filtering options.

**Endpoint:** `GET /api/seo/analytics/list` or `POST /api/seo/analytics/list`

**Headers:**
```
X-API-Key: your_api_key_here
Content-Type: application/json (for POST)
```

**Query Parameters (GET) or Request Body (POST):**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| status | integer | No | Filter by status: 0=Initiated, 1=Success, 2=Pending, 3=Rejected |
| method_code | integer | No | Filter by payment gateway method code |
| user_id | integer | No | Filter by user ID |
| date_from | string | No | Filter from date (YYYY-MM-DD) |
| date_to | string | No | Filter to date (YYYY-MM-DD) |
| min_amount | decimal | No | Minimum amount filter |
| max_amount | decimal | No | Maximum amount filter |
| limit | integer | No | Number of items per page (max 500, default 100) |
| page | integer | No | Page number (default 1) |

**Example Request (GET):**
```bash
curl -H "X-API-Key: your_api_key_here" \
     "https://fadded.net/api/seo/analytics/list?status=1&limit=100"
```

**Example Request (POST):**
```bash
curl -X POST https://fadded.net/api/seo/analytics/list \
     -H "X-API-Key: your_api_key_here" \
     -H "Content-Type: application/json" \
     -d '{
       "status": 1,
       "date_from": "2024-01-01",
       "limit": 100
     }'
```

**Example Response:**
```json
{
    "success": true,
    "data": {
        "deposits": [
            {
                "id": 1001,
                "user_id": 50,
                "username": "user123",
                "amount": 5000.00,
                "charge": 175.00,
                "final_amo": 4825.00,
                "trx": "TRX123456789",
                "status": 1,
                "method_code": 118,
                "method_name": "XtraPay",
                "created_at": "2024-01-15 10:30:00",
                "detail": {}
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 100,
            "total": 500,
            "total_pages": 5
        }
    }
}
```

---

### 5. Get Single Deposit

Get details of a specific deposit by ID.

**Endpoint:** `GET /api/seo/analytics/{id}`

**Headers:**
```
X-API-Key: your_api_key_here
```

**Example Request:**
```bash
curl -H "X-API-Key: your_api_key_here" \
     "https://fadded.net/api/seo/analytics/1001"
```

**Example Response:**
```json
{
    "success": true,
    "data": {
        "id": 1001,
        "user_id": 50,
        "username": "user123",
        "amount": 5000.00,
        "charge": 175.00,
        "final_amo": 4825.00,
        "trx": "TRX123456789",
        "status": 1,
        "method_code": 118,
        "method_name": "XtraPay",
        "created_at": "2024-01-15 10:30:00",
        "updated_at": "2024-01-15 10:35:00",
        "detail": {}
    }
}
```

---

### 6. Bulk Delete Deposits (Cleanup)

Delete multiple deposits in batches (maximum 100 per request).

**Endpoint:** `POST /api/seo/cleanup/batch`

**Headers:**
```
X-API-Key: your_api_key_here
Content-Type: application/json
```

**Request Body (Option 1 - Delete by IDs):**
```json
{
    "confirm": true,
    "deposit_ids": [1001, 1002, 1003, 1004, 1005]
}
```

**Request Body (Option 2 - Delete by Filters):**
```json
{
    "confirm": true,
    "filters": {
        "status": 3,
        "date_from": "2024-01-01",
        "date_to": "2024-01-31"
    },
    "limit": 100
}
```

**Request Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| confirm | boolean | Yes | Must be set to `true` to confirm deletion |
| deposit_ids | array | Yes* | Array of deposit IDs to delete (max 100) |
| filters | object | Yes* | Filter object for batch deletion (max 100) |
| limit | integer | No | Maximum number to delete when using filters (max 100, default 100) |

*Either `deposit_ids` OR `filters` must be provided, not both.

**Example Request (Delete by IDs):**
```bash
curl -X POST https://fadded.net/api/seo/cleanup/batch \
     -H "X-API-Key: your_api_key_here" \
     -H "Content-Type: application/json" \
     -d '{
       "confirm": true,
       "deposit_ids": [1001, 1002, 1003]
     }'
```

**Example Request (Delete by Filters):**
```bash
curl -X POST https://fadded.net/api/seo/cleanup/batch \
     -H "X-API-Key: your_api_key_here" \
     -H "Content-Type: application/json" \
     -d '{
       "confirm": true,
       "filters": {
         "status": 3,
         "date_from": "2024-01-01"
       },
       "limit": 50
     }'
```

**Example Response:**
```json
{
    "success": true,
    "message": "3 deposits deleted successfully",
    "deleted_count": 3,
    "deleted_ids": [1001, 1002, 1003]
}
```

**Error Responses:**
- `400` - Validation error or missing confirmation
- `404` - No deposits found matching filters
- `500` - Server error

---

## Error Responses

All endpoints return errors in the following format:

```json
{
    "success": false,
    "message": "Error description here"
}
```

**HTTP Status Codes:**
- `200` - Success
- `400` - Bad Request (validation errors, missing parameters)
- `401` - Unauthorized (invalid or missing API key)
- `404` - Not Found (resource not found)
- `422` - Validation Error (detailed validation errors)
- `500` - Internal Server Error

**Example Error Response (401):**
```json
{
    "success": false,
    "message": "Invalid or missing API key"
}
```

**Example Error Response (422 - Validation):**
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "asset_id": ["The asset id field is required."],
        "quantity": ["The quantity must be at least 1."]
    }
}
```

---

## Usage Examples

### PHP Example

```php
<?php

$apiKey = 'your_api_key_here';
$baseUrl = 'https://fadded.net/api';

// List products
$ch = curl_init($baseUrl . '/git/products/list?only_in_stock=true');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: ' . $apiKey
]);
$response = curl_exec($ch);
$products = json_decode($response, true);

// Pull products
$data = [
    'asset_id' => 123,
    'quantity' => 5,
    'action' => 'archive',
    'processed_by' => 'php_script_1'
];
$ch = curl_init($baseUrl . '/git/assets/retrieve');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: ' . $apiKey,
    'Content-Type: application/json'
]);
$response = curl_exec($ch);
$result = json_decode($response, true);
```

### Python Example

```python
import requests

api_key = 'your_api_key_here'
base_url = 'https://fadded.net/api'

headers = {
    'X-API-Key': api_key
}

# List products
response = requests.get(
    f'{base_url}/git/products/list',
    headers=headers,
    params={'only_in_stock': True, 'limit': 50}
)
products = response.json()

# Pull products
data = {
    'asset_id': 123,
    'quantity': 5,
    'action': 'archive',
    'processed_by': 'python_script_1'
}
response = requests.post(
    f'{base_url}/git/assets/retrieve',
    headers={**headers, 'Content-Type': 'application/json'},
    json=data
)
result = response.json()
```

### JavaScript/Node.js Example

```javascript
const axios = require('axios');

const apiKey = 'your_api_key_here';
const baseUrl = 'https://fadded.net/api';

const headers = {
    'X-API-Key': apiKey
};

// List products
axios.get(`${baseUrl}/git/products/list`, {
    headers,
    params: { only_in_stock: true, limit: 50 }
})
.then(response => {
    const products = response.data;
    console.log(products);
})
.catch(error => {
    console.error(error.response.data);
});

// Pull products
axios.post(`${baseUrl}/git/assets/retrieve`, {
    asset_id: 123,
    quantity: 5,
    action: 'archive',
    processed_by: 'nodejs_script_1'
}, {
    headers: { ...headers, 'Content-Type': 'application/json' }
})
.then(response => {
    const result = response.data;
    console.log(result);
})
.catch(error => {
    console.error(error.response.data);
});
```

---

## Rate Limiting

Currently, there are no rate limits implemented. However, please use the API responsibly and avoid excessive requests.

---

## Support

For API key requests, technical support, or questions, please contact the system administrator.

---

## Changelog

### Version 1.0.0
- Initial API release
- Git Asset Management API (products, retrieval, logs)
- SEO Management API (deposits, analytics, cleanup)


