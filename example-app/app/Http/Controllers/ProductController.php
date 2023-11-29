<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetListRequest;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Components(
    securitySchemes: [new OA\SecurityScheme(
        securityScheme: "bearerAuth",
        type: "http",
        in: "header",
        bearerFormat: "JWT",
        scheme: "Bearer"
    )]
)]
class ProductController extends Controller
{
    #[OA\Get(
        path: "/api/products/",
        summary: "Получить список продуктов с пагинацией",
        tags: ["Products"],
        responses: [
            new OA\Response(response: 200, description: "OK", content: [
                new OA\JsonContent(properties: [
                    new OA\Property(property: "products", type: "array", items: new OA\Items(properties: [
                        new OA\Property(property: "id", type: "integer", example: "1"),
                        new OA\Property(property: "name", type: "string", example: "Аспирин"),
                        new OA\Property(property: "slug", type: "string", example: "aspirin")
                    ])),
                    new OA\Property(property: "links", properties: [
                        new OA\Property(property: "first", type: "string", example: "http://localhost/api/products?page=1"),
                        new OA\Property(property: "last", type: "string", example: "http://localhost/api/products?page=11"),
                        new OA\Property(property: "prev", type: "string", example: null, nullable: true),
                        new OA\Property(property: "next", type: "string", example: "http://localhost/api/products?page=2", nullable: true),
                    ], type: "object"),
                    new OA\Property(property: "meta", properties: [
                        new OA\Property(property: "current_page", type: "integer", example: 1),
                        new OA\Property(property: "from", type: "integer", example: 1),
                        new OA\Property(property: "last_page", type: "integer", example: 11),
                        new OA\Property(property: "links", type: "array", items: new OA\Items(properties: [
                            new OA\Property(property: "url", type: "string", example: null, nullable: true),
                            new OA\Property(property: "label", type: "string", example: "&laquo; Previous"),
                            new OA\Property(property: "active", type: "boolean", example: false),
                        ])),
                        new OA\Property(property: "path", type: "string", example: "http://localhost/api/products"),
                        new OA\Property(property: "per_page", type: "integer", example: 10),
                        new OA\Property(property: "to", type: "integer", example: 10),
                        new OA\Property(property: "total", type: "integer", example: 102),
                    ])
                ])
            ])
        ]
    )]
    public function getList(GetListRequest $request): ResourceCollection
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 10;
        $sort = $validated['sort'] ?? 'name-asc';

        $products = Product::query();
        if ($sort === 'name-asc') { $products->orderBy('name'); }
        else if ($sort === 'name-dsc') { $products->orderByDesc('name'); }

        return new ProductCollection($products->paginate($perPage));
    }

    #[OA\Get(
        path: "/api/products/{slug}/",
        summary: "Получить продукт",
        tags: ["Products"],
        parameters: [
            new OA\Parameter(
                name: "slug",
                description: "slug продукта",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", example: "aspirin")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(properties: [
                new OA\Property(property: "id", type: "integer", example: "1"),
                new OA\Property(property: "name", type: "string", example: "Аспирин"),
                new OA\Property(property: "slug", type: "string", example: "aspirin")
            ])),
        ]
    )]
    public function get(Product $product): ProductResource
    {
        return ProductResource::make($product);
    }

    #[OA\Post(
        path: "/api/products/",
        summary: "Создать продукт",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(required: ['name'], properties: [
            new OA\Property(property: "name", type: "string", example: "Аспирин"),
            new OA\Property(property: "slug", type: "string", example: "aspirin")
        ])),
        tags: ["Products"],
        responses: [
            new OA\Response(response: 201, description: "OK", content: new OA\JsonContent(properties: [
                new OA\Property(property: "id", type: "integer", example: "1"),
                new OA\Property(property: "name", type: "string", example: "Аспирин"),
                new OA\Property(property: "slug", type: "string", example: "aspirin")
            ])),
            new OA\Response(response: 422, description: "Unprocessable Content", content: new OA\JsonContent(properties: [
                new OA\Property(property: "message", type: "string", example: "The slug has already been taken.")
            ]))
        ],
    )]
    public function create(ProductCreateRequest $request): ProductResource
    {
        $validated = $request->validated();

        $name = $validated['name'];
        $slug = $validated['slug'] ?? str_slug($name);

        if (Product::whereSlug($slug)->exists()) {
            throw new \Exception('The product exists');
        }

        $product = Product::create([
            'name' => $name,
            'slug' => $slug
        ]);

        return ProductResource::make($product);
    }

    #[OA\Put(
        path: "/api/products/{slug}/",
        summary: "Обновить продукт",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [
            new OA\Property(property: "name", type: "string", example: "Аспирин 2.0"),
            new OA\Property(property: "slug", type: "string", example: "aspirin-20")
        ])),
        tags: ["Products"],
        parameters: [
            new OA\Parameter(
                name: "slug",
                description: "slug продукта",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", example: "aspirin")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(properties: [
                new OA\Property(property: "id", type: "integer", example: "1"),
                new OA\Property(property: "name", type: "string", example: "Аспирин"),
                new OA\Property(property: "slug", type: "string", example: "aspirin")
            ])),
        ]
    )]
    public function update(ProductUpdateRequest $request, Product $product): ProductResource
    {
        $validated = $request->validated();

        if (key_exists('name', $validated)) { $product->name = $validated['name']; }
        if (key_exists('slug', $validated)) {
            if (Product::whereSlug($validated['slug'])->exists()) {
                throw new \Exception('The product slug exists');
            }

            $product->slug = $validated['slug'];
        }

        if ($product->isDirty()) { $product->save(); }

        return ProductResource::make($product);
    }

    #[OA\Delete(
        path: "/api/products/{slug}/",
        summary: "Удалить продукт",
        security: [["bearerAuth" => []]],
        tags: ["Products"],
        parameters: [
            new OA\Parameter(
                name: "slug",
                description: "slug продукта",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", example: "aspirin")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "OK"),
            new OA\Response(response: 404, description: "Not Found")
        ]
    )]
    public function delete(Product $product): void
    {
        $product->delete();
    }
}
