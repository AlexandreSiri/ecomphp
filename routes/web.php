<?php

Router::get("/", "HomeController@index");


Router::get("/auth/login", "AuthController@showLogin")
    ->name("auth.login.show")
    ->middleware("guest");
Router::post("/auth/login", "AuthController@login")
    ->name("auth.login")
    ->middleware("guest");

Router::get("/auth/register", "AuthController@showRegister")
    ->name("auth.register.show")
    ->middleware("guest");
Router::post("/auth/register", "AuthController@register")
    ->name("auth.register")
    ->middleware("guest");

Router::get("/auth/forgot", "AuthController@showForgot")
    ->name("auth.forgot.show")
    ->middleware("guest");
Router::post("/auth/forgot", "AuthController@forgot")
    ->name("auth.forgot")
    ->middleware("guest");

Router::get("/auth/reset/{token}", "AuthController@showReset")
    ->name("auth.reset.show")
    ->middleware("guest");
Router::post("/auth/reset/{token}", "AuthController@reset")
    ->name("auth.reset")
    ->middleware("guest");

Router::get("/auth/logout", "AuthController@logout")
    ->name("auth.logout")
    ->middleware("auth");


Router::get("/products", "HomeController@products")
    ->name("products.list");
Router::post("/products", "HomeController@products");

Router::post("/products/{id}/rate", "UserController@rateProduct")
    ->middleware("auth");
Router::post("/products/{id}/comment", "UserController@commentProduct")
    ->middleware("auth");
Router::delete("/products/review/{id}", "UserController@deleteReview")
    ->middleware("auth");

Router::get("/products/{id}/{slug?}", "HomeController@productsDetail")
    ->name("products.detail");
Router::post("/products/{id}/{slug?}", "HomeController@productsDetail");


Router::post("/cart/products/{id}", "CartController@addProduct")
    ->name("cart.products.add");
Router::delete("/cart/products/{id}", "CartController@removeProduct")
    ->name("cart.products.add");
Router::delete("/cart/products/{id}/all", "CartController@deleteProduct")
    ->name("cart.products.add");

Router::get("/cart/checkout", "CartController@showCheckout")
    ->name("cart.checkout");
Router::post("/cart/checkout", "CartController@createCheckout");


Router::get("/payments/confirm/{id}", "CartController@confirmPayment")
    ->name("payment.confirm");
Router::get("/payments/cancel/{id}", "CartController@cancelPayment")
    ->name("payment.cancel");


Router::get("/account/me", "UserController@me")
    ->name("account.me")
    ->middleware("auth");
Router::post("/account/me", "UserController@editMe")
    ->middleware("auth");

Router::get("/account/security", "UserController@security")
    ->name("account.security")
    ->middleware("auth");
Router::post("/account/security", "UserController@editSecurity")
    ->middleware("auth");

Router::get("/account/addresses", "UserController@addresses")
    ->name("account.addresses")
    ->middleware("auth");
Router::delete("/account/addresses/{id}", "UserController@deleteAddress")
    ->middleware("auth");

Router::get("/account/orders", "UserController@orders")
    ->name("account.orders")
    ->middleware("auth");

Router::get("/account/reviews", "UserController@reviews")
    ->name("account.reviews")
    ->middleware("auth");