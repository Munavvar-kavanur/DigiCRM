# DigiCRM Mobile App API Documentation

**Base URL:** `https://crm.digibayt.com/api`

## Authentication

All API requests (except Login) require a Bearer Token in the Authorization header.
`Authorization: Bearer <your_token>`

### 1. Login
*   **Endpoint:** `/login`
*   **Method:** `POST`
*   **Body Parameters:**
    *   `email`: (string, required) User's email address.
    *   `password`: (string, required) User's password.
*   **Response:** Returns user object and access token.

### 2. Logout
*   **Endpoint:** `/logout`
*   **Method:** `POST`
*   **Headers:** `Authorization: Bearer <token>`
*   **Response:** Success message.

### 3. Get User Profile
*   **Endpoint:** `/user`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer <token>`
*   **Response:** Returns authenticated user details.

---

## Dashboard

### Get Dashboard Data
*   **Endpoint:** `/dashboard`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer <token>`
*   **Response:** Returns KPIs (Total Clients, Ongoing Projects, Outstanding Revenue, Overdue Invoices), Recent Projects, and Recent Invoices.

---

## Core Resources

For all resource endpoints, standard CRUD operations are supported.

### Clients
*   **List Clients:** `GET /clients`
*   **Create Client:** `POST /clients`
    *   Body: `name`, `email`, `phone`, `address`, `branch_id`
*   **Get Client:** `GET /clients/{id}`
*   **Update Client:** `PUT /clients/{id}`
*   **Delete Client:** `DELETE /clients/{id}`

### Projects
*   **List Projects:** `GET /projects`
*   **Create Project:** `POST /projects`
    *   Body: `name`, `client_id`, `start_date`, `deadline`, `status`, `branch_id`
*   **Get Project:** `GET /projects/{id}`
*   **Update Project:** `PUT /projects/{id}`
*   **Delete Project:** `DELETE /projects/{id}`

### Invoices
*   **List Invoices:** `GET /invoices`
*   **Create Invoice:** `POST /invoices`
    *   Body: `client_id`, `project_id` (optional), `issue_date`, `due_date`, `total_amount`, `status`, `branch_id`, `items` (array of items)
*   **Get Invoice:** `GET /invoices/{id}`
*   **Update Invoice:** `PUT /invoices/{id}`
*   **Delete Invoice:** `DELETE /invoices/{id}`

### Estimates
*   **List Estimates:** `GET /estimates`
*   **Create Estimate:** `POST /estimates`
    *   Body: `client_id`, `project_id` (optional), `estimate_date`, `expiry_date`, `total_amount`, `status`, `branch_id`, `items` (array of items)
*   **Get Estimate:** `GET /estimates/{id}`
*   **Update Estimate:** `PUT /estimates/{id}`
*   **Delete Estimate:** `DELETE /estimates/{id}`

### Expenses
*   **List Expenses:** `GET /expenses`
*   **Create Expense:** `POST /expenses`
    *   Body: `description`, `amount`, `date`, `expense_category_id`, `expense_payer_id`, `branch_id`
*   **Get Expense:** `GET /expenses/{id}`
*   **Update Expense:** `PUT /expenses/{id}`
*   **Delete Expense:** `DELETE /expenses/{id}`

### Tasks
*   **List Tasks:** `GET /tasks`
    *   Query Param: `project_id` (optional) to filter by project.
*   **Create Task:** `POST /tasks`
    *   Body: `title`, `project_id`, `status`, `due_date`
*   **Get Task:** `GET /tasks/{id}`
*   **Update Task:** `PUT /tasks/{id}`
*   **Delete Task:** `DELETE /tasks/{id}`

### Employees
*   **List Employees:** `GET /employees`
*   **Create Employee:** `POST /employees`
    *   Body: `name`, `email`, `password`, `branch_id`, `employee_type_id`
*   **Get Employee:** `GET /employees/{id}`
*   **Update Employee:** `PUT /employees/{id}`
*   **Delete Employee:** `DELETE /employees/{id}`

### Payrolls
*   **List Payrolls:** `GET /payrolls`
    *   Query Param: `user_id` (optional) to filter by employee.
*   **Create Payroll:** `POST /payrolls`
    *   Body: `user_id`, `payroll_type_id`, `amount`, `payment_date`, `status`
*   **Get Payroll:** `GET /payrolls/{id}`
*   **Update Payroll:** `PUT /payrolls/{id}`
*   **Delete Payroll:** `DELETE /payrolls/{id}`

---

## Response Format

**Success Response:**
```json
{
    "success": true,
    "data": { ... },
    "message": "Operation successful."
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Error message description.",
    "data": { ...validation errors... } // Optional
}
```
