# Laravel Inventory & Sales API

A Laravel-based REST API for managing products, providers, categories, batches, storages, clients, orders, and refunds.

The system tracks purchased products by batch, manages warehouse stock, automatically uses the oldest available batch when creating client orders, supports partial and full refunds, and calculates remaining stock and profit per batch.

## Features

- Product and category management
- Parent-child category structure
- Provider-based categories
- Product purchasing and batch creation
- Storage inventory management
- FIFO batch selection when selling products
- Client order management
- Client refunds
- Purchase refunds
- Remaining storage calculation by date
- Profit calculation per batch
- Database transactions for important operations
- Request validation
- Feature tests for core business logic

## Business Workflow

### Purchase

Products are purchased from a provider and added to a batch.

Each batch contains:

- Provider
- Storage
- Purchase date
- Products
- Quantity
- Purchase price

Purchased quantities are also added to the selected storage.

### Client Orders

When a client places an order, the frontend only sends:

- `client_id`
- `product_id`
- `qty`

The backend automatically selects batches using FIFO (First In, First Out).

The oldest available batch is used first.

If the requested quantity cannot be fulfilled from one batch, the system continues with the next available batch.

## Tests

Feature tests have been written to verify the core business logic of the application.

The tests cover:

- Purchasing products and adding them to storage
- Creating client orders using FIFO batch selection
- Splitting orders between multiple batches
- Client refunds and returning products to storage
- Purchase refunds
- Preventing refunds from exceeding available quantities
- Returning available products
- Calculating remaining storage by date

Run all tests:

```bash
php artisan test
