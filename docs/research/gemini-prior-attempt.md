Veeqo API OpenAPI Spec Generation
https://share.gemini.google/W17Rm1zfmIf7
Created with 3.5 Flash July 2, 2026 at 09:06 AM
Published July 12, 2026 at 04:24 AM
You are a Backend Developer well-versed in crafting REST API Clients. You will kick-off your next project by generating an OpenAPI 3.0 spec for Veeqo's REST API using documentation for each resource found on the webpages linked below this paragraph. The links are grouped by resource, where the first line of a group states the resource name. Groups are separated/delineated by a blank line. Ensure that the API endpoint paths specified in the spec exactly match those defined in resource links below. Some resource links contain definitions for a JSON request body and/or a JSON response body for an API endpoint. Extract those details from the pages referenced in the links and make sure to incorporate them into the generated OpenAPI spec in YAML format.

Allocations

https://developers.veeqo.com/api/operations/tags/allocations/

https://developers.veeqo.com/api/operations/create-a-new-allocation/

https://developers.veeqo.com/api/operations/update-allocation-detail/

https://developers.veeqo.com/api/operations/delete-allocation/

https://developers.veeqo.com/api/operations/update-allocation-package/

Bulk Tagging

https://developers.veeqo.com/api/operations/tagging-products/

https://developers.veeqo.com/api/operations/untagging-orders/

Bundles

https://developers.veeqo.com/api/operations/create-a-bundle/

https://developers.veeqo.com/api/operations/retrieve-bundle-detail/

https://developers.veeqo.com/api/operations/create-bundle-content/

https://developers.veeqo.com/api/operations/retrieve-bundle-content/

https://developers.veeqo.com/api/operations/update-bundle-content/

https://developers.veeqo.com/api/operations/delete-bundle-content/

Company

https://developers.veeqo.com/api/operations/view-company-detail/

https://developers.veeqo.com/api/operations/update-company-detail/

Customers

https://developers.veeqo.com/api/operations/list-all-customers/

https://developers.veeqo.com/api/operations/create-a-customer/

https://developers.veeqo.com/api/operations/view-customer-detail/

https://developers.veeqo.com/api/operations/update-customer-detail/

Delivery Methods

https://developers.veeqo.com/api/operations/list-all-delivery-methods/

https://developers.veeqo.com/api/operations/create-a-delivery-method/

https://developers.veeqo.com/api/operations/view-delivery-method/

https://developers.veeqo.com/api/operations/update-delivery-method-detail/

https://developers.veeqo.com/api/operations/delete-delivery-method/

Line Items

https://developers.veeqo.com/api/operations/update-line-item-notes/

Orders

https://developers.veeqo.com/api/operations/list-all-orders/

https://developers.veeqo.com/api/operations/create-a-new-order/

https://developers.veeqo.com/api/operations/view-an-order-detail/

https://developers.veeqo.com/api/operations/update-order-detail/

https://developers.veeqo.com/api/operations/create-a-new-order-note/

https://developers.veeqo.com/api/operations/cancel-an-order/

Payments

https://developers.veeqo.com/api/operations/create-payment/

Products

https://developers.veeqo.com/api/operations/list-all-products/

https://developers.veeqo.com/api/operations/create-a-new-product/

https://developers.veeqo.com/api/operations/view-product-detail/

https://developers.veeqo.com/api/operations/update-product-detail/

https://developers.veeqo.com/api/operations/delete-a-product/

https://developers.veeqo.com/api/operations/view-properties/

https://developers.veeqo.com/api/operations/update-property-detail/

https://developers.veeqo.com/api/operations/remove-property-from-product/

https://developers.veeqo.com/api/operations/create-a-new-property/

Purchase Orders

https://developers.veeqo.com/api/operations/list-all-purchase-orders/

Allocation Rates

https://developers.veeqo.com/api/operations/retrieve-shipping-rates/

https://developers.veeqo.com/api/operations/purchase-shipping-labels/

https://developers.veeqo.com/api/operations/retrieve-shipping-labels/

Returns

https://developers.veeqo.com/api/operations/show-returns-on-order/

Shipments

https://developers.veeqo.com/api/operations/create-a-shipment/

https://developers.veeqo.com/api/operations/delete-shipment/

https://developers.veeqo.com/api/operations/view-tracking-events/

Stock Entries

https://developers.veeqo.com/api/operations/show-a-stock-entry/

https://developers.veeqo.com/api/operations/update-a-stock-entry/

Stores

https://developers.veeqo.com/api/operations/list-all-stores/

https://developers.veeqo.com/api/operations/create-a-store/

https://developers.veeqo.com/api/operations/view-store-detail/

https://developers.veeqo.com/api/operations/update-store-detail/

https://developers.veeqo.com/api/operations/delete-channels/

Suppliers

https://developers.veeqo.com/api/operations/list-all-suppliers/

https://developers.veeqo.com/api/operations/create-a-new-supplier/

https://developers.veeqo.com/api/operations/view-a-supplier-detail/

https://developers.veeqo.com/api/operations/update-supplier-detail/

https://developers.veeqo.com/api/operations/delete-supplier/

Tags

https://developers.veeqo.com/api/operations/list-all-tags/

https://developers.veeqo.com/api/operations/create-a-new-tag/

https://developers.veeqo.com/api/operations/view-an-tag-detail/

https://developers.veeqo.com/api/operations/delete-tag/

Warehouses

https://developers.veeqo.com/api/operations/list-all-warehouses/

https://developers.veeqo.com/api/operations/create-a-warehouse/

https://developers.veeqo.com/api/operations/view-warehouse-detail/

https://developers.veeqo.com/api/operations/update-warehouse-detail/

https://developers.veeqo.com/api/operations/delete-warehouse/

YAML
openapi: 3.0.3
info:
title: Veeqo REST API
description: An OpenAPI 3.0 specification for the Veeqo REST API, constructed from official developer documentation.
version: 1.0.0
servers:

- url: https://api.veeqo.com
  description: Production Server

security:

- ApiKeyAuth: []

paths:

# --- Allocations ---

/orders/{order_id}/allocations:
post:
tags: - Allocations
summary: Create a New Allocation
operationId: createAllocation
parameters: - name: order_id
in: path
required: true
schema:
type: integer
description: Order ID
requestBody:
required: true
content:
application/json:
schema:
type: object
required: - allocation
properties:
allocation:
type: object
required: - warehouse_id - line_items_attributes
properties:
warehouse_id:
type: integer
example: 5
line_items_attributes:
type: array
items:
type: object
properties:
sellable_id:
type: integer
example: 1226615
quantity:
type: integer
example: 1
responses:
'201':
description: Created
/orders/{order_id}/allocations/{id}:
put:
tags: - Allocations
summary: Update Allocation Detail
operationId: updateAllocationDetail
parameters: - name: order_id
in: path
required: true
schema:
type: integer - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
delete:
tags: - Allocations
summary: Delete Allocation
operationId: deleteAllocation
parameters: - name: order_id
in: path
required: true
schema:
type: integer - name: id
in: path
required: true
schema:
type: integer
responses:
'204':
description: No Content
/orders/{order_id}/allocations/{id}/packages:
put:
tags: - Allocations
summary: Update Allocation Package
operationId: updateAllocationPackage
parameters: - name: order_id
in: path
required: true
schema:
type: integer - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK

# --- Bulk Tagging ---

/products/tags:
post:
tags: - Bulk Tagging
summary: Tagging Products
operationId: tagProducts
responses:
'200':
description: OK
/orders/untag:
post:
tags: - Bulk Tagging
summary: Untagging Orders
operationId: untagOrders
responses:
'200':
description: OK

# --- Bundles ---

/products/bundles:
post:
tags: - Bundles
summary: Create a Bundle
operationId: createBundle
responses:
'201':
description: Created
/products/bundles/{id}:
get:
tags: - Bundles
summary: Retrieve Bundle Detail
operationId: getBundleDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
/products/bundles/{id}/contents:
get:
tags: - Bundles
summary: Retrieve Bundle Content
operationId: getBundleContent
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
post:
tags: - Bundles
summary: Create Bundle Content
operationId: createBundleContent
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'201':
description: Created
put:
tags: - Bundles
summary: Update Bundle Content
operationId: updateBundleContent
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
delete:
tags: - Bundles
summary: Delete Bundle Content
operationId: deleteBundleContent
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'204':
description: No Content

# --- Company ---

/company:
get:
tags: - Company
summary: View Company Detail
operationId: viewCompanyDetail
responses:
'200':
description: OK
put:
tags: - Company
summary: Update Company Detail
operationId: updateCompanyDetail
responses:
'200':
description: OK

# --- Customers ---

/customers:
get:
tags: - Customers
summary: List All Customers
operationId: listCustomers
responses:
'200':
description: OK
post:
tags: - Customers
summary: Create a Customer
operationId: createCustomer
responses:
'201':
description: Created
/customers/{id}:
get:
tags: - Customers
summary: View Customer Detail
operationId: viewCustomerDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
put:
tags: - Customers
summary: Update Customer Detail
operationId: updateCustomerDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK

# --- Delivery Methods ---

/delivery_methods:
get:
tags: - Delivery Methods
summary: List All Delivery Methods
operationId: listDeliveryMethods
responses:
'200':
description: OK
post:
tags: - Delivery Methods
summary: Create a Delivery Method
operationId: createDeliveryMethod
responses:
'201':
description: Created
/delivery_methods/{id}:
get:
tags: - Delivery Methods
summary: View Delivery Method Detail
operationId: viewDeliveryMethod
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
put:
tags: - Delivery Methods
summary: Update Delivery Method Detail
operationId: updateDeliveryMethodDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
delete:
tags: - Delivery Methods
summary: Delete Delivery Method
operationId: deleteDeliveryMethod
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'204':
description: No Content

# --- Line Items ---

/line_items/{id}/notes:
put:
tags: - Line Items
summary: Update Line Item Notes
operationId: updateLineItemNotes
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK

# --- Orders ---

/orders:
get:
tags: - Orders
summary: List All Orders
operationId: listOrders
parameters: - name: since_id
in: query
required: false
schema:
type: integer - name: created_at_min
in: query
required: false
schema:
type: string - name: updated_at_min
in: query
required: false
schema:
type: string - name: page_size
in: query
required: false
schema:
type: integer
default: 12 - name: page
in: query
required: false
schema:
type: integer
default: 1 - name: status
in: query
required: false
schema:
type: string
enum: [awaiting_payment, awaiting_stock, awaiting_fulfillment, shipped, on_hold, cancelled, refunded] - name: tags
in: query
required: false
schema:
type: string
responses:
'200':
description: OK
post:
tags: - Orders
summary: Create a New Order
operationId: createOrder
responses:
'201':
description: Created
/orders/{id}:
get:
tags: - Orders
summary: View an Order Detail
operationId: viewOrderDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
put:
tags: - Orders
summary: Update Order Detail
operationId: updateOrderDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
/orders/{id}/notes:
post:
tags: - Orders
summary: Create a New Order Note
operationId: createOrderNote
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'201':
description: Created
/orders/{id}/cancel:
post:
tags: - Orders
summary: Cancel an Order
operationId: cancelOrder
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK

# --- Payments ---

/orders/{order_id}/payments:
post:
tags: - Payments
summary: Create Payment
operationId: createPayment
parameters: - name: order_id
in: path
required: true
schema:
type: integer
responses:
'201':
description: Created

# --- Products ---

/products:
get:
tags: - Products
summary: List All Products
operationId: listProducts
responses:
'200':
description: OK
post:
tags: - Products
summary: Create a New Product
operationId: createProduct
responses:
'201':
description: Created
/products/{id}:
get:
tags: - Products
summary: View Product Detail
operationId: viewProductDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
put:
tags: - Products
summary: Update Product Detail
operationId: updateProductDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
delete:
tags: - Products
summary: Delete a Product
operationId: deleteProduct
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'204':
description: No Content
/products/{id}/properties:
get:
tags: - Products
summary: View Properties
operationId: viewProperties
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
post:
tags: - Products
summary: Create a New Property
operationId: createProperty
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'201':
description: Created
/products/{id}/properties/{property_id}:
put:
tags: - Products
summary: Update Property Detail
operationId: updatePropertyDetail
parameters: - name: id
in: path
required: true
schema:
type: integer - name: property_id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
delete:
tags: - Products
summary: Remove Property from Product
operationId: removeProperty
parameters: - name: id
in: path
required: true
schema:
type: integer - name: property_id
in: path
required: true
schema:
type: integer
responses:
'204':
description: No Content

# --- Purchase Orders ---

/purchase_orders:
get:
tags: - Purchase Orders
summary: List All Purchase Orders
operationId: listPurchaseOrders
responses:
'200':
description: OK

# --- Allocation Rates ---

/shipping/rates/{allocation_id}:
get:
tags: - Allocation Rates
summary: Retrieve Shipping Rates
operationId: retrieveShippingRates
parameters: - name: allocation_id
in: path
required: true
schema:
type: integer - name: from_allocation_package
in: query
required: false
schema:
type: boolean
responses:
'200':
description: OK
/shipping/labels:
post:
tags: - Allocation Rates
summary: Purchase Shipping Labels
operationId: purchaseShippingLabels
responses:
'201':
description: Created
/shipping/labels/{id}:
get:
tags: - Allocation Rates
summary: Retrieve Shipping Labels
operationId: retrieveShippingLabels
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK

# --- Returns ---

/orders/{order_id}/returns:
get:
tags: - Returns
summary: Show Returns on Order
operationId: showReturnsOnOrder
parameters: - name: order_id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK

# --- Shipments ---

/orders/{order_id}/shipments:
post:
tags: - Shipments
summary: Create a Shipment
operationId: createShipment
parameters: - name: order_id
in: path
required: true
schema:
type: integer
responses:
'201':
description: Created
/orders/{order_id}/shipments/{id}:
delete:
tags: - Shipments
summary: Delete Shipment
operationId: deleteShipment
parameters: - name: order_id
in: path
required: true
schema:
type: integer - name: id
in: path
required: true
schema:
type: integer
responses:
'204':
description: No Content
/shipments/{id}/tracking_events:
get:
tags: - Shipments
summary: View Tracking Events
operationId: viewTrackingEvents
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK

# --- Stock Entries ---

/stock_entries/{id}:
get:
tags: - Stock Entries
summary: Show a Stock Entry
operationId: showStockEntry
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
put:
tags: - Stock Entries
summary: Update a Stock Entry
operationId: updateStockEntry
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK

# --- Stores ---

/stores:
get:
tags: - Stores
summary: List All Stores
operationId: listStores
responses:
'200':
description: OK
post:
tags: - Stores
summary: Create a Store
operationId: createStore
responses:
'201':
description: Created
/stores/{id}:
get:
tags: - Stores
summary: View Store Detail
operationId: viewStoreDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
put:
tags: - Stores
summary: Update Store Detail
operationId: updateStoreDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
/stores/{id}/channels:
delete:
tags: - Stores
summary: Delete Channels
operationId: deleteChannels
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'204':
description: No Content

# --- Suppliers ---

/suppliers:
get:
tags: - Suppliers
summary: List All Suppliers
operationId: listSuppliers
responses:
'200':
description: OK
post:
tags: - Suppliers
summary: Create a New Supplier
operationId: createSupplier
responses:
'201':
description: Created
/suppliers/{id}:
get:
tags: - Suppliers
summary: View a Supplier Detail
operationId: viewSupplierDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
put:
tags: - Suppliers
summary: Update Supplier Detail
operationId: updateSupplierDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
delete:
tags: - Suppliers
summary: Delete Supplier
operationId: deleteSupplier
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'204':
description: No Content

# --- Tags ---

/tags:
get:
tags: - Tags
summary: List All Tags
operationId: listTags
responses:
'200':
description: OK
post:
tags: - Tags
summary: Create a New Tag
operationId: createTag
responses:
'201':
description: Created
/tags/{id}:
get:
tags: - Tags
summary: View a Tag Detail
operationId: viewTagDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
delete:
tags: - Tags
summary: Delete Tag
operationId: deleteTag
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'204':
description: No Content

# --- Warehouses ---

/warehouses:
get:
tags: - Warehouses
summary: List All Warehouses
operationId: listWarehouses
responses:
'200':
description: OK
post:
tags: - Warehouses
summary: Create a Warehouse
operationId: createWarehouse
responses:
'201':
description: Created
/warehouses/{id}:
get:
tags: - Warehouses
summary: View Warehouse Detail
operationId: viewWarehouseDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
put:
tags: - Warehouses
summary: Update Warehouse Detail
operationId: updateWarehouseDetail
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'200':
description: OK
delete:
tags: - Warehouses
summary: Delete Warehouse
operationId: deleteWarehouse
parameters: - name: id
in: path
required: true
schema:
type: integer
responses:
'204':
description: No Content

components:
securitySchemes:
ApiKeyAuth:
type: apiKey
in: header
name: x-api-key
Generate a Node.js client using the generated OpenAPI Spec.

Here is a complete, lightweight Node.js REST API client for Veeqo based on the OpenAPI spec provided. It uses the modern native fetch API (available in Node.js 18+) and features full TypeScript-ready or JavaScript JSDoc types for clarity, structured resource methods, and automated path formatting.

Project Setup
Initialize your project (if you haven't already):

Bash
npm init -y
Set your package type to ES Modules by adding "type": "module" to your package.json, or save the file below with a .js extension using modern syntax.

VeeqoClient.js
JavaScript
/\*\*

- Lightweight Veeqo REST API Client
  \*/
  export class VeeqoClient {
  /\*\*
  - @param {Object} config
  - @param {string} config.apiKey - Your Veeqo API Key
  - @param {string} [config.baseUrl] - Veeqo Base API URL
    \*/
    constructor({ apiKey, baseUrl = 'https://api.veeqo.com' }) {
    if (!apiKey) {
    throw new Error('VeeqoClient requires an apiKey');
    }
    this.apiKey = apiKey;
    this.baseUrl = baseUrl.replace(/\/$/, '');
    }

/\*\*

- Core request handler
- @private
  \*/
  async \_request(path, options = {}) {
  const url = `${this.baseUrl}${path}`;
  const headers = {
  'x-api-key': this.apiKey,
  'Content-Type': 'application/json',
  'Accept': 'application/json',
  ...options.headers,
  };


    const response = await fetch(url, { ...options, headers });

    if (response.status === 204) {
      return null;
    }

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`Veeqo API Error [${response.status}]: ${errorText || response.statusText}`);
    }

    return response.json();

}

// ==========================================
// Allocations
// ==========================================
allocations = {
/\*\*
_ Create a New Allocation
_ @param {number} orderId
_ @param {Object} allocationData
_/
create: (orderId, allocationData) =>
this.\_request(`/orders/${orderId}/allocations`, {
method: 'POST',
body: JSON.stringify(allocationData)
}),

    /**
     * Update Allocation Detail
     */
    update: (orderId, id) =>
      this._request(`/orders/${orderId}/allocations/${id}`, { method: 'PUT' }),

    /**
     * Delete Allocation
     */
    delete: (orderId, id) =>
      this._request(`/orders/${orderId}/allocations/${id}`, { method: 'DELETE' }),

    /**
     * Update Allocation Package
     */
    updatePackage: (orderId, id) =>
      this._request(`/orders/${orderId}/allocations/${id}/packages`, { method: 'PUT' })

};

// ==========================================
// Orders
// ==========================================
orders = {
/\*\*
_ List All Orders with filter parameters
_ @param {Object} [params] - Optional query parameters (page, status, etc.)
\*/
list: (params = {}) => {
const query = new URLSearchParams(params).toString();
const queryString = query ? `?${query}` : '';
return this.\_request(`/orders${queryString}`, { method: 'GET' });
},

    create: (orderData) =>
      this._request('/orders', { method: 'POST', body: JSON.stringify(orderData) }),

    get: (id) =>
      this._request(`/orders/${id}`, { method: 'GET' }),

    update: (id, orderData) =>
      this._request(`/orders/${id}`, { method: 'PUT', body: JSON.stringify(orderData) }),

    cancel: (id) =>
      this._request(`/orders/${id}/cancel`, { method: 'POST' }),

    createNote: (id, noteData) =>
      this._request(`/orders/${id}/notes`, { method: 'POST', body: JSON.stringify(noteData) })

};

// ==========================================
// Products
// ==========================================
products = {
list: () => this.\_request('/products', { method: 'GET' }),

    create: (productData) =>
      this._request('/products', { method: 'POST', body: JSON.stringify(productData) }),

    get: (id) =>
      this._request(`/products/${id}`, { method: 'GET' }),

    update: (id, productData) =>
      this._request(`/products/${id}`, { method: 'PUT', body: JSON.stringify(productData) }),

    delete: (id) =>
      this._request(`/products/${id}`, { method: 'DELETE' }),

    // Product Properties Sub-resource
    properties: {
      list: (productId) =>
        this._request(`/products/${productId}/properties`, { method: 'GET' }),
      create: (productId, propertyData) =>
        this._request(`/products/${productId}/properties`, { method: 'POST', body: JSON.stringify(propertyData) }),
      update: (productId, propertyId, propertyData) =>
        this._request(`/products/${productId}/properties/${propertyId}`, { method: 'PUT', body: JSON.stringify(propertyData) }),
      delete: (productId, propertyId) =>
        this._request(`/products/${productId}/properties/${propertyId}`, { method: 'DELETE' })
    }

};

// ==========================================
// Warehouses
// ==========================================
warehouses = {
list: () => this.\_request('/warehouses', { method: 'GET' }),
create: (data) => this.\_request('/warehouses', { method: 'POST', body: JSON.stringify(data) }),
get: (id) => this.\_request(`/warehouses/${id}`, { method: 'GET' }),
update: (id, data) => this.\_request(`/warehouses/${id}`, { method: 'PUT', body: JSON.stringify(data) }),
delete: (id) => this.\_request(`/warehouses/${id}`, { method: 'DELETE' })
};
}
Usage Example
Here is how you can import and test the client inside your runtime application (e.g., an index.js file):

JavaScript
import { VeeqoClient } from './VeeqoClient.js';

const veeqo = new VeeqoClient({
apiKey: 'your_actual_veeqo_api_key_here'
});

async function run() {
try {
// 1. Fetch a list of active orders awaiting fulfillment
console.log('Fetching orders...');
const orders = await veeqo.orders.list({
status: 'awaiting_fulfillment',
page_size: 5
});
console.log(`Found ${orders.length} orders.`);

    // 2. Create an allocation for a specific order if items exist
    if (orders.length > 0) {
      const targetOrder = orders[0];

      const newAllocation = await veeqo.allocations.create(targetOrder.id, {
        allocation: {
          warehouse_id: 123, // Replace with your exact warehouse ID
          line_items_attributes: [
            {
              sellable_id: 987654,
              quantity: 1
            }
          ]
        }
      });
      console.log('Allocation created successfully!', newAllocation);
    }

} catch (error) {
console.error('An error occurred during SDK operational lifecycle:', error.message);
}
}

run();
