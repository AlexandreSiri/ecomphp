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
var __spreadArray = (this && this.__spreadArray) || function (to, from, pack) {
    if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
        if (ar || !(i in from)) {
            if (!ar) ar = Array.prototype.slice.call(from, 0, i);
            ar[i] = from[i];
        }
    }
    return to.concat(ar || Array.prototype.slice.call(from));
};
exports.__esModule = true;
var basic_1 = require("../functions/basic");
(function () {
    if (!document.querySelector("body > .products"))
        return;
    var options = {
        category: [],
        size: [],
        color: [],
        sort: "newest"
    };
    var open = null;
    var controller = null;
    var fetchProducts = function () { return __awaiter(void 0, void 0, void 0, function () {
        var form, response, element, products, productsElement;
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0:
                    controller && controller.abort();
                    controller = new AbortController();
                    form = new FormData();
                    options.category.map(function (c) { return form.append("category[]", c); });
                    options.size.map(function (c) { return form.append("size[]", c); });
                    options.color.map(function (c) { return form.append("color[]", c); });
                    form.append("sort", options.sort);
                    return [4 /*yield*/, fetch("/products", {
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
                    products = element.querySelector(".products-list");
                    productsElement = document.querySelector(".products-list");
                    if (!products || !productsElement)
                        return [2 /*return*/];
                    productsElement.innerHTML = products.innerHTML;
                    return [2 /*return*/];
            }
        });
    }); };
    var filters = Array.from(document.querySelectorAll(".filter[id]"));
    filters.map(function (filter) {
        var id = filter.id;
        var label = filter.querySelector(".label");
        label === null || label === void 0 ? void 0 : label.addEventListener("click", function () {
            filters.filter(function (f) { return f.id !== id; }).map(function (filter) { return filter.classList.remove("open"); });
            if (id === open) {
                open = null;
                filter.classList.remove("open");
            }
            else {
                open = id;
                filter.classList.add("open");
            }
        });
        if (id !== "sort") {
            var choices = Array.from(filter.querySelectorAll("input"));
            choices.map(function (choice) { return choice.addEventListener("change", function (e) {
                e.preventDefault();
                var values = options[id].filter(function (f) { return f !== choice.id; });
                options[id] = choice.checked ? __spreadArray(__spreadArray([], values, true), [choice.id], false) : values;
                fetchProducts();
            }); });
        }
        else {
            var choices_1 = Array.from(filter.querySelectorAll("input"));
            choices_1.map(function (choice) { return choice.addEventListener("change", function (e) {
                var _a, _b, _c;
                e.preventDefault();
                if (choice.id === options.sort)
                    choice.checked = true;
                else {
                    choices_1.filter(function (f) { return f.id !== choice.id; }).map(function (c) { return c.checked = false; });
                    options.sort = choice.id;
                    var text = (_c = (_b = (_a = choice.parentElement) === null || _a === void 0 ? void 0 : _a.querySelector("label")) === null || _b === void 0 ? void 0 : _b.innerText) !== null && _c !== void 0 ? _c : "";
                    var l = label === null || label === void 0 ? void 0 : label.querySelector("div");
                    if (!l)
                        return;
                    l.innerText = text;
                }
                fetchProducts();
            }); });
        }
    });
    document.addEventListener("mousedown", function (e) {
        var parents = (0, basic_1.getParents)(e.target);
        if (parents.includes("filter") || !open)
            return;
        filters.map(function (filter) { return filter.classList.remove("open"); });
        open = null;
    });
})();
