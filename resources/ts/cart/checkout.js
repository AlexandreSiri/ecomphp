"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
exports.__esModule = true;
var snackbar_1 = require("../functions/snackbar");
(function () {
    if (!document.querySelector("body > .checkout"))
        return;
    var controller = null;
    var changePrice = function () {
        var products = Array.from(document.querySelectorAll(".cart-product"));
        var price = products.reduce(function (r, product) {
            var p = product.querySelector(".price");
            var count = product.querySelector(".count span");
            return r + parseFloat(p.dataset.price || "") * parseInt(count.innerText);
        }, 0);
        var dutyFree = document.querySelector("li.duty-free span:last-child");
        var vat = document.querySelector("li.vat span:last-child");
        var total = document.querySelector("li.total span:last-child");
        var button = document.querySelector(".payment-foot button");
        dutyFree.innerText = "$".concat(price);
        vat.innerText = "$".concat(price * 0.2);
        total.innerText = "$".concat(price * 1.2);
        button.innerText = "Pay $".concat(price * 1.2);
        if (!products.length) {
            var illustration = document.querySelector(".illustration");
            illustration.classList.add("visible");
            button.disabled = true;
        }
    };
    var removeProduct = function (id) { return __awaiter(void 0, void 0, void 0, function () {
        var product, button, countElement, count, response, badge;
        var _a, _b;
        return __generator(this, function (_c) {
            switch (_c.label) {
                case 0:
                    controller && controller.abort();
                    controller = new AbortController();
                    product = document.querySelector(".cart-product[id=\"".concat(id, "\"]"));
                    button = document.querySelector(".less");
                    countElement = product.querySelector("span");
                    count = parseInt((_a = countElement.innerText) !== null && _a !== void 0 ? _a : "");
                    button.disabled = true;
                    return [4 /*yield*/, fetch("/cart/products/".concat(id), {
                            method: "DELETE",
                            signal: controller.signal
                        }).then(function (res) { return res.json(); })["catch"](function () { return null; })];
                case 1:
                    response = _c.sent();
                    button.disabled = false;
                    if (!response)
                        return [2 /*return*/];
                    badge = document.querySelector(".navbar .badge");
                    badge.innerText = response.data.count;
                    if (count === 1)
                        (_b = product.parentElement) === null || _b === void 0 ? void 0 : _b.removeChild(product);
                    else
                        countElement.innerText = "".concat(count - 1);
                    changePrice();
                    return [2 /*return*/];
            }
        });
    }); };
    var addProduct = function (id) { return __awaiter(void 0, void 0, void 0, function () {
        var product, button, countElement, count, response, badge;
        var _a;
        return __generator(this, function (_b) {
            switch (_b.label) {
                case 0:
                    controller && controller.abort();
                    controller = new AbortController();
                    product = document.querySelector(".cart-product[id=\"".concat(id, "\"]"));
                    button = document.querySelector(".less");
                    countElement = product.querySelector("span");
                    count = parseInt((_a = countElement.innerText) !== null && _a !== void 0 ? _a : "");
                    button.disabled = true;
                    return [4 /*yield*/, fetch("/cart/products/".concat(id), {
                            method: "POST",
                            signal: controller.signal
                        }).then(function (res) { return res.json(); })["catch"](function () { return null; })];
                case 1:
                    response = _b.sent();
                    button.disabled = false;
                    if (!response)
                        return [2 /*return*/];
                    badge = document.querySelector(".navbar .badge");
                    badge.innerText = response.data.count;
                    countElement.innerText = "".concat(count + 1);
                    changePrice();
                    return [2 /*return*/];
            }
        });
    }); };
    var deleteProduct = function (id) { return __awaiter(void 0, void 0, void 0, function () {
        var product, button, response, badge;
        var _a;
        return __generator(this, function (_b) {
            switch (_b.label) {
                case 0:
                    controller && controller.abort();
                    controller = new AbortController();
                    product = document.querySelector(".cart-product[id=\"".concat(id, "\"]"));
                    button = document.querySelector(".less");
                    button.disabled = true;
                    return [4 /*yield*/, fetch("/cart/products/".concat(id, "/all"), {
                            method: "DELETE",
                            signal: controller.signal
                        }).then(function (res) { return res.json(); })["catch"](function () { return null; })];
                case 1:
                    response = _b.sent();
                    button.disabled = false;
                    if (!response)
                        return [2 /*return*/];
                    badge = document.querySelector(".navbar .badge");
                    badge.innerText = response.data.count;
                    (_a = product.parentElement) === null || _a === void 0 ? void 0 : _a.removeChild(product);
                    changePrice();
                    return [2 /*return*/];
            }
        });
    }); };
    var products = Array.from(document.querySelectorAll(".cart-product"));
    products.map(function (product) {
        var id = parseInt(product.id);
        var less = product.querySelector(".less");
        var more = product.querySelector(".more");
        var del = product.querySelector(".delete");
        less.addEventListener("click", function () { return removeProduct(id); });
        more.addEventListener("click", function () { return addProduct(id); });
        del.addEventListener("click", function () { return deleteProduct(id); });
    });
    var form = document.querySelector("form");
    form.addEventListener("submit", function (e) { return __awaiter(void 0, void 0, void 0, function () {
        var formData, button, response;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    e.preventDefault();
                    controller && controller.abort();
                    controller = new AbortController();
                    formData = new FormData(form);
                    button = document.querySelector(".payment-foot button");
                    button.disabled = true;
                    return [4 /*yield*/, fetch(form.action, {
                            method: "POST",
                            body: formData,
                            signal: controller.signal
                        }).then(function (res) { return __awaiter(void 0, void 0, void 0, function () {
                            var response;
                            return __generator(this, function (_a) {
                                switch (_a.label) {
                                    case 0: return [4 /*yield*/, res.json()];
                                    case 1:
                                        response = _a.sent();
                                        if (res.status >= 400)
                                            return [2 /*return*/, (0, snackbar_1.addSnackbar)({
                                                    type: "error",
                                                    message: response.data[0]
                                                })];
                                        else
                                            return [2 /*return*/, response];
                                        return [2 /*return*/];
                                }
                            });
                        }); })["catch"](function () { return null; })];
                case 1:
                    response = _a.sent();
                    button.disabled = false;
                    if (!response)
                        return [2 /*return*/];
                    window.location.href = response.data;
                    return [2 /*return*/];
            }
        });
    }); });
    var addresses = Array.from(document.querySelectorAll(".addresses > div"));
    var addressForm = document.querySelector(".delivery-address");
    var addressInput = document.querySelector("input[type='hidden']");
    addresses.map(function (address) { return address.addEventListener("click", function () {
        if (address.id === "add") {
            addressInput.value = "0";
            addressForm.classList.add("active");
        }
        else {
            addressInput.value = address.id;
            addressForm.classList.remove("active");
        }
        addresses.map(function (address) { return address.classList.remove("active"); });
        address.classList.add("active");
    }); });
})();
