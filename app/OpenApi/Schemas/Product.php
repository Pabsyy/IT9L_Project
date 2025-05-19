<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="Product",
 *     required={"id", "name", "description", "price", "category_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Product Name"),
 *     @OA\Property(property="description", type="string", example="Product description"),
 *     @OA\Property(property="price", type="number", format="float", example=99.99),
 *     @OA\Property(property="category_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Product {}

/**
 * @OA\Schema(
 *     schema="ProductRequest",
 *     required={"name", "description", "price", "category_id"},
 *     @OA\Property(property="name", type="string", example="Product Name"),
 *     @OA\Property(property="description", type="string", example="Product description"),
 *     @OA\Property(property="price", type="number", format="float", example=99.99),
 *     @OA\Property(property="category_id", type="integer", example=1)
 * )
 */
class ProductRequest {} 