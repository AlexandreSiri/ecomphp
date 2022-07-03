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
var tiny_date_picker_1 = require("tiny-date-picker");
var submitForm_1 = require("../auth/submitForm");
var basic_1 = require("../functions/basic");
var snackbar_1 = require("../functions/snackbar");
(function () {
    if (!window.location.pathname.startsWith("/account"))
        return;
    var birthInput = document.querySelector("input#birthday");
    if (birthInput) {
        (0, tiny_date_picker_1["default"])({
            input: birthInput,
            format: function (date) {
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                return "".concat(year, "-").concat(month < 10 ? "0".concat(month) : month, "-").concat(day < 10 ? "0".concat(day) : day);
            },
            min: new Date("1910-01-01"),
            max: new Date()
        });
    }
    var form = document.querySelector("form");
    form === null || form === void 0 ? void 0 : form.addEventListener("submit", function (e) { return __awaiter(void 0, void 0, void 0, function () {
        var result, inputs;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    e.preventDefault();
                    return [4 /*yield*/, (0, submitForm_1["default"])(form)];
                case 1:
                    result = _a.sent();
                    if (!result)
                        return [2 /*return*/];
                    (0, snackbar_1.addSnackbar)({
                        type: "success",
                        message: result.data
                    });
                    inputs = Array.from(form.querySelectorAll("input[type='password']"));
                    inputs.map(function (input) { return input.value = ""; });
                    return [2 /*return*/];
            }
        });
    }); });
    var illustration = document.querySelector(".illustration");
    var deleteAddress = function (id) { return __awaiter(void 0, void 0, void 0, function () {
        var response;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0: return [4 /*yield*/, fetch("/account/addresses/".concat(id), {
                        method: "DELETE"
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
                    (0, snackbar_1.addSnackbar)({
                        type: "success",
                        message: response.data
                    });
                    return [2 /*return*/];
            }
        });
    }); };
    var addresses = Array.from(document.querySelectorAll(".addresses-list .address"));
    addresses.map(function (address) {
        var deleteButton = address.querySelector(".address-icon");
        deleteButton === null || deleteButton === void 0 ? void 0 : deleteButton.addEventListener("click", function () {
            var _a;
            deleteAddress(parseInt(address.id));
            (_a = address.parentElement) === null || _a === void 0 ? void 0 : _a.removeChild(address);
            if (!document.querySelectorAll(".addresses-list .address").length && illustration)
                illustration.classList.add("visible");
        });
    });
    var controller = null;
    var rateProduct = function (id, note) { return __awaiter(void 0, void 0, void 0, function () {
        var form, response, product, stars;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    controller && controller.abort();
                    controller = new AbortController();
                    form = new FormData();
                    form.append("note", "".concat(note));
                    return [4 /*yield*/, fetch("/products/".concat(id, "/rate"), {
                            method: "POST",
                            body: form,
                            signal: controller.signal
                        }).then(function (res) { return res.text(); })["catch"](function () { return null; })];
                case 1:
                    response = _a.sent();
                    if (!response)
                        return [2 /*return*/];
                    product = document.querySelector(".order-product[data-id='".concat(id, "']"));
                    stars = Array.from(product.querySelectorAll(".star"));
                    stars.map(function (star, index) { return note > index ? star.classList.add("filled") : star.classList.remove("filled"); });
                    product.classList.add("review");
                    return [2 /*return*/];
            }
        });
    }); };
    var orderProducts = Array.from(document.querySelectorAll(".order-product"));
    orderProducts.map(function (orderProduct) { return orderProduct.addEventListener("click", function (e) { return __awaiter(void 0, void 0, void 0, function () {
        var review, review, content, id, form_1, response, review, p;
        var _a, _b;
        return __generator(this, function (_c) {
            switch (_c.label) {
                case 0:
                    if ((0, basic_1.getParents)(e.target).includes("write")) {
                        review = orderProduct.querySelector(".review");
                        review === null || review === void 0 ? void 0 : review.classList.add("writing");
                    }
                    if ((0, basic_1.getParents)(e.target).includes("cancel")) {
                        review = orderProduct.querySelector(".review");
                        review === null || review === void 0 ? void 0 : review.classList.remove("writing");
                    }
                    if (!(0, basic_1.getParents)(e.target).includes("submit")) return [3 /*break*/, 2];
                    content = orderProduct.querySelector("textarea").value;
                    id = parseInt((_a = orderProduct.dataset.id) !== null && _a !== void 0 ? _a : "");
                    form_1 = new FormData();
                    form_1.append("content", "".concat(content));
                    return [4 /*yield*/, fetch("/products/".concat(id, "/comment"), {
                            method: "POST",
                            body: form_1
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
                    response = _c.sent();
                    if (!response)
                        return [2 /*return*/];
                    review = orderProduct.querySelector(".review");
                    review === null || review === void 0 ? void 0 : review.classList.remove("writing");
                    orderProduct.classList.remove("review");
                    p = document.createElement("p");
                    p.innerText = content;
                    (_b = orderProduct.querySelector(".note")) === null || _b === void 0 ? void 0 : _b.appendChild(p);
                    _c.label = 2;
                case 2: return [2 /*return*/];
            }
        });
    }); }); });
    var ratings = Array.from(document.querySelectorAll(".rating.ratable"));
    ratings.map(function (rating) {
        var classes = ["hover-1", "hover-2", "hover-3", "hover-4", "hover-5"];
        var star = null;
        rating.addEventListener("mouseleave", function () {
            var _a;
            (_a = rating.classList).remove.apply(_a, classes);
            star = null;
        });
        rating.addEventListener("mousemove", function (e) {
            var _a, _b;
            var ids = (0, basic_1.getParentsId)(e.target).map(function (id) { return parseInt(id); }).filter(function (id) { return !isNaN(id); });
            if (ids.length !== 2) {
                star = null;
                return (_a = rating.classList).remove.apply(_a, classes);
            }
            star = ids[0];
            if (rating.classList.contains("hover-".concat(star)))
                return;
            (_b = rating.classList).remove.apply(_b, classes);
            rating.classList.add("hover-".concat(star));
        });
        rating.addEventListener("mousedown", function (e) {
            if (!star)
                return;
            e.preventDefault();
            var productId = parseInt(rating.id);
            rateProduct(productId, star);
        });
    });
    var reviews = Array.from(document.querySelectorAll(".reviews-list .review-row"));
    reviews.map(function (review) {
        var trash = review.querySelector(".trash");
        trash.addEventListener("click", function () { return __awaiter(void 0, void 0, void 0, function () {
            var id, response, r, list;
            var _a, _b, _c;
            return __generator(this, function (_d) {
                switch (_d.label) {
                    case 0:
                        id = parseInt((_a = trash.dataset.id) !== null && _a !== void 0 ? _a : "");
                        return [4 /*yield*/, fetch("/products/review/".concat(id), {
                                method: "DELETE"
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
                        response = _d.sent();
                        if (!response)
                            return [2 /*return*/];
                        r = document.querySelector(".review-row[data-id=\"".concat(id, "\"]"));
                        (_b = r === null || r === void 0 ? void 0 : r.parentElement) === null || _b === void 0 ? void 0 : _b.removeChild(r);
                        if (!document.querySelectorAll(".review-row").length) {
                            list = document.querySelector(".reviews-list");
                            (_c = list === null || list === void 0 ? void 0 : list.parentNode) === null || _c === void 0 ? void 0 : _c.removeChild(list);
                            illustration === null || illustration === void 0 ? void 0 : illustration.classList.add("visible");
                        }
                        return [2 /*return*/];
                }
            });
        }); });
    });
})();
