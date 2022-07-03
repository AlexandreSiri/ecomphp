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
var basic_1 = require("../functions/basic");
var snackbar_1 = require("../functions/snackbar");
(function () {
    var _a, _b;
    if (!document.querySelector("body > .products-detail"))
        return;
    var sort = "newest";
    var open = false;
    var size = parseFloat((_b = (_a = document.querySelector("#size .choice.selected")) === null || _a === void 0 ? void 0 : _a.id) !== null && _b !== void 0 ? _b : "");
    var button = document.querySelector("button#add-cart");
    var controller = null;
    var fetchComments = function () { return __awaiter(void 0, void 0, void 0, function () {
        var form, response, element, comments, commentsElement;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    controller && controller.abort();
                    controller = new AbortController();
                    form = new FormData();
                    form.append("sort", sort);
                    return [4 /*yield*/, fetch("", {
                            method: "POST",
                            body: form,
                            signal: controller.signal
                        }).then(function (res) { return res.text(); })["catch"](function () { return null; })];
                case 1:
                    response = _a.sent();
                    if (!response)
                        return [2 /*return*/];
                    element = document.createElement("div");
                    element.innerHTML = response;
                    comments = element.querySelector(".reviews-list");
                    commentsElement = document.querySelector(".reviews-list");
                    if (!comments || !commentsElement)
                        return [2 /*return*/];
                    commentsElement.innerHTML = comments.innerHTML;
                    return [2 /*return*/];
            }
        });
    }); };
    var addToCart = function () { return __awaiter(void 0, void 0, void 0, function () {
        var response, badge;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    button.disabled = true;
                    controller && controller.abort();
                    controller = new AbortController();
                    return [4 /*yield*/, fetch("/cart/products/".concat(size), {
                            method: "POST",
                            signal: controller.signal
                        }).then(function (res) { return res.json(); })];
                case 1:
                    response = _a.sent();
                    button.disabled = false;
                    if (!response)
                        return [2 /*return*/];
                    badge = document.querySelector(".navbar .badge");
                    badge.innerText = response.data.count;
                    (0, snackbar_1.addSnackbar)({
                        type: "success",
                        message: response.data.message
                    });
                    return [2 /*return*/];
            }
        });
    }); };
    button.addEventListener("click", function (e) {
        if (button.disabled)
            return;
        addToCart();
    });
    var filter = document.querySelector(".filter");
    var label = filter === null || filter === void 0 ? void 0 : filter.querySelector(".label");
    label === null || label === void 0 ? void 0 : label.addEventListener("click", function () {
        if (open) {
            open = false;
            filter === null || filter === void 0 ? void 0 : filter.classList.remove("open");
        }
        else {
            open = true;
            filter === null || filter === void 0 ? void 0 : filter.classList.add("open");
        }
    });
    var inputs = Array.from((filter === null || filter === void 0 ? void 0 : filter.querySelectorAll("input")) || []);
    inputs.map(function (input) { return input.addEventListener("change", function (e) {
        var _a, _b, _c;
        e.preventDefault();
        if (input.id === sort)
            input.checked = true;
        else {
            inputs.filter(function (f) { return f.id !== input.id; }).map(function (c) { return c.checked = false; });
            sort = input.id;
            var text = (_c = (_b = (_a = input.parentElement) === null || _a === void 0 ? void 0 : _a.querySelector("label")) === null || _b === void 0 ? void 0 : _b.innerText) !== null && _c !== void 0 ? _c : "";
            var l = label === null || label === void 0 ? void 0 : label.querySelector("div");
            if (!l)
                return;
            l.innerText = text;
        }
        fetchComments();
    }); });
    document.addEventListener("mousedown", function (e) {
        var parents = (0, basic_1.getParents)(e.target);
        if (parents.includes("filter") || !open)
            return;
        filter === null || filter === void 0 ? void 0 : filter.classList.remove("open");
        open = false;
    });
    var bigImage = document.querySelector(".image-large");
    var images = Array.from(document.querySelectorAll(".images-list .image"));
    images.map(function (image) { return image.addEventListener("click", function () {
        var _a;
        var url = (_a = image.dataset.image) !== null && _a !== void 0 ? _a : "";
        if (bigImage.style.backgroundImage.includes(url))
            return;
        bigImage.style.backgroundImage = "url(\"".concat(url, "\")");
        images.map(function (img) { return img.classList.remove("selected"); });
        image.classList.add("selected");
    }); });
    var sizeChoices = Array.from(document.querySelectorAll("#size .choice"));
    sizeChoices.map(function (s) { return s.addEventListener("click", function () {
        if (s.classList.contains("selected") || s.classList.contains("disabled"))
            return;
        var value = document.querySelector("#size .value");
        sizeChoices.map(function (s) { return s.classList.remove("selected"); });
        s.classList.add("selected");
        size = parseFloat(s.id);
        value.innerText = s.innerText;
    }); });
})();
