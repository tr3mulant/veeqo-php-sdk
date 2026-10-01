<?php

namespace IronGate\Veeqo;

use IronGate\Veeqo\Resource\Allocations;
use IronGate\Veeqo\Resource\BulkTagging;
use IronGate\Veeqo\Resource\Company;
use IronGate\Veeqo\Resource\Customers;
use IronGate\Veeqo\Resource\DeliveryMethods;
use IronGate\Veeqo\Resource\Orders;
use IronGate\Veeqo\Resource\Products;
use IronGate\Veeqo\Resource\PurchaseOrders;
use IronGate\Veeqo\Resource\Returns;
use IronGate\Veeqo\Resource\Shipments;
use IronGate\Veeqo\Resource\StockEntries;
use IronGate\Veeqo\Resource\Stores;
use IronGate\Veeqo\Resource\Suppliers;
use IronGate\Veeqo\Resource\Tags;
use IronGate\Veeqo\Resource\Warehouses;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector;

/**
 * Veeqo API
 *
 * The Veeqo API gives you everything you need to build the world's most powerful tools for ecommerce retailers.
 *
 * You can integrate any external application or service with a retailer's Veeqo account. This allows you to do just about anything you can do in the Veeqo Web App using the programming language of your choice.
 *
 * Our API is built using Ruby on Rails and based on RESTful principles, using predictable and explorable URLs, HTTP requests and JSON responses.
 * ## Developer Central
 *
 * We're working hard at Veeqo to make your developing experience when building on top of our API the best it can be. We have therefore created [Developer Central](https://developer.veeqo.com) which has access to all of the resources you'll need.
 *
 * We also have a [Developer Forum](https://developer-forum.veeqo.com) so if you get stuck you can get answers to your queries from our team or our growing developer community.
 *
 * ## Limits
 *
 * The API has requests rate limits per key/token powered by a [Leaky Bucket algorithm](https://en.wikipedia.org/wiki/Leaky_bucket). If requests come too frequently, they are queued in a bucket. If the queue reaches the bucket limit, the API responds with HTTP 429 error.
 *
 * Current limit is **5 requests per second** with a bucket size **up to 100** requests.
 *
 * ## Code Samples
 *
 * Learn from our examples.
 *
 * All of our examples live on the VeeqoAPI GitHub page. We're working hard to add
 * more over the coming months.
 *
 * ### [Labels Downloader](https://github.com/VeeqoAPI/shipment-label-downloader)
 *
 * Sample application, written in Python, shows how to retrieve shipment lables
 * from shipped Veeqo orders
 *
 * ### [Products Catalog](https://github.com/VeeqoAPI/products-list)
 *
 * This example, written in PHP, illustrates how to build simple catalog
 * displaying products from Veeqo account
 *
 * ### [Dashboard](https://github.com/VeeqoAPI/dashboard)
 *
 * Written in PHP and based on the same CURL script as the Products Catalog,
 * this live example displays a simple dashboard of orders from today and yesterday.
 * Similar to the way the mobile app dashboard works.
 *
 * The live example can be [found here.](https://veeqo-dashboard.herokuapp.com/)
 *
 * ## Useful Files
 *
 * ### [Common Currencies](https://github.com/VeeqoAPI/api-docs/blob/master/resources/references/common_currency.json), [Countries](https://github.com/VeeqoAPI/api-docs/blob/master/resources/references/countries.json), [Order Statuses](https://github.com/VeeqoAPI/api-docs/blob/master/resources/references/order_statuses.json)
 *
 * ## Support
 *
 * If you get stuck at any stage in your project you can find answers on our [Developer Forum](https://developer-forum.veeqo.com/). Our forum is monitored by one of our friendly team and we aim to respond to all posts within 48-hours.
 *
 * ## Feedback
 *
 * We LOVE &#10084;&#65039; feedback! It helps us to improve developer experience and make your job easier! If you have any feedback regarding our API, documentation or Developer Central please leave a comment on the forum or email [api-support@veeqo.com](mailto:api-support@veeqo.co)
 */
class Veeqo extends Connector
{
	/**
	 * @param string $xApiKey
	 */
	public function __construct(
		protected string $xApiKey,
	) {
	}


	public function resolveBaseUrl(): string
	{
		return "https://api.veeqo.com";
	}


	public function defaultAuth(): Authenticator
	{
		return new HeaderAuthenticator($this->xApiKey, "x-api-key");
	}


	public function allocations(): Allocations
	{
		return new Allocations($this);
	}


	public function bulkTagging(): BulkTagging
	{
		return new BulkTagging($this);
	}


	public function company(): Company
	{
		return new Company($this);
	}


	public function customers(): Customers
	{
		return new Customers($this);
	}


	public function deliveryMethods(): DeliveryMethods
	{
		return new DeliveryMethods($this);
	}


	public function orders(): Orders
	{
		return new Orders($this);
	}


	public function products(): Products
	{
		return new Products($this);
	}


	public function purchaseOrders(): PurchaseOrders
	{
		return new PurchaseOrders($this);
	}


	public function returns(): Returns
	{
		return new Returns($this);
	}


	public function shipments(): Shipments
	{
		return new Shipments($this);
	}


	public function stockEntries(): StockEntries
	{
		return new StockEntries($this);
	}


	public function stores(): Stores
	{
		return new Stores($this);
	}


	public function suppliers(): Suppliers
	{
		return new Suppliers($this);
	}


	public function tags(): Tags
	{
		return new Tags($this);
	}


	public function warehouses(): Warehouses
	{
		return new Warehouses($this);
	}
}
