<?php

namespace App\Http\Controllers;

use App\Helpers\Curl\Http;
use App\Helpers\Routers\Request;
use App\Models\Cart;
use App\Models\Category;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\Review;
use App\Models\User;

class HomeController {
    public function index(Request $request) {
        $carts = array_map(function (ProductSize $size) {
            $product = $size->product;
            $image = $product->images[0]->source;
            return [
                "id" => $product->id,
                "name" => $product->name,
                "color" => $product->color,
                "slug" => slugify("{$product->name} {$product->color}"),
                "size" => $size->size,
                "image" => $image,
                "price" => $product->price,
                "promo" => $product->promo
            ];
        }, array_slice($request->cart->sizes, 0, 3));
        
        $orderProducts = OrderProduct::all();
        $products = [];
        foreach ($orderProducts as $orderProduct) {
            $index = -1;
            $pr = $orderProduct->size->product;

            foreach ($products as $i => $p) if ($p["product"]->id === $pr->id) {
                $index = $i;
                break;
            }
            if ($index >= 0) $products[$index]["count"]++;
            else {
                array_push($products, [
                    "product" => $pr,
                    "count" => 1
                ]);
            }
        }
        usort($products, fn (array $a, array $b) => $a["count"] < $b["count"] ? 1 : -1);
        
        $popular = array_map(function (array $row) {
            $product = $row["product"];
            $reviews = $product->allReviews;
            $score = array_reduce($reviews, fn (int $r, Review $re) => $r + $re->note, 0) / count($reviews);
            return [
                "id" => $product->id,
                "name" => $product->name,
                "image" => $product->images[0]->source,
                "score" => $score,
                "price" => $product->price,
                "promo" => $product->promo,
                "color" => $product->color,
                "slug" => slugify("{$product->name} {$product->color}")
            ];
        }, array_slice($products, 0, 4));
        
        return view("index", [
            "carts" => $carts,
            "popular" => $popular
        ]);
    }
    public function products(Request $request) {
        $category = (key_exists("category", $request->body) && gettype($request->body["category"]) === "array") ? $request->body["category"] : [];
        $color = (key_exists("color", $request->body) && gettype($request->body["color"]) === "array") ? $request->body["color"] : [];
        $size = (key_exists("size", $request->body) && gettype($request->body["size"]) === "array") ? $request->body["size"] : [];
        $sort = key_exists("sort", $request->body) ? $request->body["sort"] : "newest";

        $products = Product::where([
            "categoryId" => [ONE_OF => $category],
            "color" => [ONE_OF => $color]
        ])->all();


        if (count($size)) $products = array_filter($products, function (Product $p) use ($size) {
            return !!array_find(fn (ProductSize $s) => in_array($s->size, $size), $p->sizes);
        });

        $pr = [];
        foreach ($products as $p) {
            $id = $p->productId ? $p->productId : $p->id;
            if (key_exists($id, $pr)) array_push($pr[$id], $p);
            else $pr[$id] = [$p];
        }
        foreach ($pr as $key => $value) {
            $pr[$key] = array_filter($value, fn (Product $p) => !count($color) ? true : in_array(strtolower($p->color), $color));
        }
        
        $pr = array_map(function (array $prs) use ($color) {
            if (!count($color)) return array_find(fn (Product $p) => !$p->productId, $prs);
            return $prs[0];
        }, $pr);

        usort($pr, function (Product $a, Product $b) use ($sort) {
            if ($sort === "newest") return $a->createdAt->getTimestamp() > $b->createdAt->getTimestamp() ? 1 : -1;
            else if ($sort === "low") return ($a->promo ?? $a->price) > ($b->promo ?? $b->price) ? 1 : -1;
            else return ($b->promo ?? $b->price) > ($a->promo ?? $a->price) ? 1 : -1;
        });

        $p = array_map(function (Product $product) {
            $p = $product->toArray();
            $image = $product->images[0]->source;

            $reviews = $product->allReviews;
            $rating = round(array_reduce($reviews, fn ($r, Review $re) => $r + $re->note, 0) / count($reviews) * 100) / 100;

            return array_merge($p, [
                "slug" => slugify($p["name"] . " {$p["color"]}"),
                "image" => $image,
                "rating" => $rating
            ]);
        }, $pr);


        $categories = Category::where("categoryId", IS, null)->all();
        $sizes = array_reduce(ProductSize::all(["size"]), fn (array $r, ProductSize $p) => !in_array($p->size, $r) ? [...$r, $p->size] : $r, []);

        $products = Product::all(["price", "color"]);
        $colors = array_reduce($products, fn (array $r, Product $p) => !in_array($p->color, $r) ? [...$r, $p->color] : $r, []);

        sort($sizes);
        sort($colors);
        
        return view("products.index", [
            "categories" => array_map(function (Category $category) {
                return array_merge($category->toArray(), [
                    "sub" => array_map(fn (Category $category) => $category->toArray(), $category->categories)
                ]);
            }, $categories),
            "sizes" => $sizes,
            "colors" => $colors,
            "products" => $p
        ]);
    }
    public function productsDetail(Request $request) {
        $product = Product::where("id", $request->params["id"])->first();
        if (!$product) return view("errors.404");

        $sort = key_exists("sort", $request->body) ? $request->body["sort"] : "newest";
        $parent = $product->productId ? Product::where("id", $product->productId)->first() : $product;
        $products = [...$parent->products, $parent];

        $images = array_map(fn (ProductImage $i) => $i->source, $product->images);
        $colors = array_map(fn (Product $p) => [
            "name" => $p->color,
            "image" => $p->images[0]->source,
            "id" => $p->id,
            "slug" => slugify($p->name . " {$p->color}")
        ], $products);
        $sizes = array_map(fn (ProductSize $s) => array_merge($s->toArray(), ["disabled" => $s->quantity === 0]), $product->sizes);
        usort($sizes, fn (array $a, array $b) => $a["size"] > $b["size"] ? 1 : -1);
        usort($colors, fn (array $a, array $b) => $a["id"] > $b["id"] ? 1 : -1);

        $reviews = $parent->allReviews;
        $rating = round(array_reduce($reviews, fn ($r, Review $re) => $r + $re->note, 0) / count($reviews) * 100) / 100;
        $availabe_sizes = array_filter($sizes, fn (array $s) => !$s["disabled"]);
        $size = array_shift($availabe_sizes) ?? $sizes[0];
        $category = $parent->category->name;
        $reviewsCount = count(array_filter($reviews, fn (Review $r) => $r->content));
        

        usort($reviews, function (Review $a, Review $b) use ($sort) {
            if ($sort === "newest") return $a->createdAt->getTimestamp() > $b->createdAt->getTimestamp() ? 1 : -1;
            else if ($sort === "low") return $a->note > $b->note ? 1 : -1;
            else return $b->note > $a->note ? 1 : -1;
        });

        view("products.detail", [
            "product" => array_merge($product->toArray(), [
                "images" => $images,
                "colors" => $colors,
                "sizes" => $sizes,
                "rating" => $rating,
                "size" => $size,
                "category" => $category,
                "reviewsCount" => $reviewsCount,
                "reviews" => array_map(fn (Review $r) => array_merge($r->toArray(), [
                    "createdAt" => preg_replace("/ .*/", "", $r->toArray()["createdAt"]),
                    "author" => $r->user->username
                ]), array_filter($reviews, fn (Review $r) => $r->content))
            ])
        ]);
    }
}