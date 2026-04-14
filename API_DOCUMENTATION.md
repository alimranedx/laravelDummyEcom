# E-commerce API Documentation

This documentation provides necessary guidelines and endpoint details for implementing the APIs on the frontend/client applications. The API uses JSON payloads for both requests and responses.

## Base URL
If you are developing locally, your base URL will be:
`http://localhost:8000/api`

In production, it will be:
`https://yourdomain.com/api`

## Authentication

This API utilizes **JWT (JSON Web Tokens)** for securing protected routes.
When accessing a protected endpoint, you **must** pass the JWT token in your HTTP headers like so:
```http
Authorization: Bearer <your_access_token>
```

---

## Authentication Endpoints

### 1. Register User
Registers a new user account.

- **Endpoint:** `POST /auth/register`
- **Access:** Public
- **Request Body:**
  ```json
  {
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }
  ```
- **Success Response (201 Created):**
  ```json
  {
    "message": "User successfully registered",
    "user": { ... }
  }
  ```
- **Error Response (400 Bad Request):** Returns validation errors.

### 2. Login User
Authenticates a user and returns a JWT token.

- **Endpoint:** `POST /auth/login`
- **Access:** Public
- **Request Body:**
  ```json
  {
    "email": "jane@example.com",
    "password": "password123"
  }
  ```
- **Success Response (200 OK):**
  ```json
  {
    "access_token": "eyJ0eXAi...",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": { ... }
  }
  ```
- **Error Response (401 Unauthorized):** Returning `{"error": "Unauthorized"}` if credentials do not match.

### 3. Forgot Password
Triggers a password reset link to be sent to the user email.

- **Endpoint:** `POST /auth/forgot-password`
- **Access:** Public
- **Request Body:**
  ```json
  {
    "email": "jane@example.com"
  }
  ```

### 4. Reset Password
Resets the password utilizing a valid token previously sent to the email.

- **Endpoint:** `POST /auth/reset-password`
- **Access:** Public
- **Request Body:**
  ```json
  {
    "email": "jane@example.com",
    "token": "reset_token_value_here",
    "password": "newpassword123!",
    "password_confirmation": "newpassword123!"
  }
  ```

### 5. Get Authenticated User (Profile)
Retrieves the logged-in user's profile information.

- **Endpoint:** `GET /auth/me`
- **Access:** Protected (Requires JWT Token)
- **Response:** Returns the JSON object of the currently authenticated user.

### 6. Refresh Token
Renews an expired JWT token and provides a new one without needing to login again.

- **Endpoint:** `POST /auth/refresh`
- **Access:** Protected (Requires valid or recently expired JWT Token)
- **Response:** Returns a new `access_token` and `expires_in` details identical to Login.

### 7. Logout
Invalidates the active JWT token, logging the user out.

- **Endpoint:** `POST /auth/logout`
- **Access:** Protected (Requires JWT Token)
- **Response:** `{"message": "User successfully signed out"}`

---

## Product Catalog Endpoints

### 1. Retrieve Product List
Fetches a paginated listing of all active products.

- **Endpoint:** `GET /products`
- **Access:** Public
- **Query Parameters:**
  - `page` (optional, integer): Page number for pagination (e.g., `?page=2`)
  - `search` (optional, string): Filters products based on keyword match in their name or description (e.g., `?search=laptop`)
- **Success Response (200 OK):** Returns a standard Laravel paginated JSON object containing an array of products.

### 2. View Single Product Details
Fetches detailed information for a specific product item by ID.

- **Endpoint:** `GET /products/{id}`
- **Access:** Public
- **Success Response (200 OK):** Returns the specific product JSON object.
- **Error Response (404 Not Found):** Returns `{"message": "Product not found"}` if the product `id` does not exist.

---

## User Account / Dashboard Endpoints

### 1. User Dashboard Metrics
Retrieves dashboard summary statistics and recent order data for the logged-in user.

- **Endpoint:** `GET /user/dashboard`
- **Access:** Protected (Requires JWT Token)
- **Success Response (200 OK):**
  ```json
  {
      "user": { ... },
      "statistics": {
          "total_orders": 12
      },
      "latest_orders": [
        { ... },
        { ... }
      ]
  }
  ```

### 2. View Order History
Fetches a paginated list of all orders tied to the logged-in user account.

- **Endpoint:** `GET /user/orders`
- **Access:** Protected (Requires JWT Token)
- **Query Parameters:**
  - `page` (optional, integer): Defaults to page 1
- **Success Response (200 OK):** Returns a paginated JSON object of user orders.

### 3. View Single Order Details
Fetches complete details for a specific order, including its products.

- **Endpoint:** `GET /user/orders/{id}`
- **Access:** Protected (Requires JWT Token)
- **Success Response (200 OK):** Returns the order object with related items and product data.

### 4. Cancel Order
Allows the user to cancel a pending order.

- **Endpoint:** `POST /api/user/orders/{id}/cancel`
- **Access:** Protected (Requires JWT Token)
- **Success Response (200 OK):** `{"success": true, "message": "Order cancelled successfully"}`
- **Error Response (400 Bad Request):** Returns error if order is not in `pending` status.

---

## Checkout & Processing Endpoints

### 1. Process Order (Checkout)
Creates a new order and prepares it for fulfillment. This endpoint handles stock validation and calculation.

- **Endpoint:** `POST /user/checkout`
- **Access:** Protected (Requires JWT Token)
- **Request Body:**
  ```json
  {
    "items": [
      { "product_id": 1, "quantity": 2 },
      { "product_id": 5, "quantity": 1 }
    ],
    "shipping_address": "123 Main St, Springfield",
    "payment_method": "Stripe"
  }
  ```
- **Success Response (201 Created):** Returns the newly created order object.
- **Error Response (400 Bad Request):** Returns error if stock is insufficient.

---

## Wishlist Endpoints

### 1. Retrieve Wishlist
Fetches all products currently saved in the user's wishlist.

- **Endpoint:** `GET /user/wishlist`
- **Access:** Protected (Requires JWT Token)
- **Success Response (200 OK):** `{"success": true, "data": [...]}`

### 2. Add to Wishlist
Saves a product to the user's wishlist.

- **Endpoint:** `POST /user/wishlist`
- **Access:** Protected (Requires JWT Token)
- **Request Body:**
  ```json
  {
    "product_id": 10
  }
  ```
- **Success Response (201 Created):** `{"success": true, "message": "Product added to wishlist successfully"}`

### 3. Remove from Wishlist
Deletes a product from the user's wishlist.

- **Endpoint:** `DELETE /user/wishlist/{product_id}`
- **Access:** Protected (Requires JWT Token)
- **Success Response (200 OK):** `{"success": true, "message": "Product removed from wishlist successfully"}`
