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
exports.removeSnackbar = exports.addSnackbar = exports.checkSnackbar = void 0;
var basic_1 = require("./basic");
var checkSnackbar = function () {
    var _a;
    var element = document.querySelector("snackbar");
    if (!element)
        return;
    (0, exports.addSnackbar)({
        type: element.getAttribute("type"),
        message: element.innerText
    });
    (_a = element.parentNode) === null || _a === void 0 ? void 0 : _a.removeChild(element);
};
exports.checkSnackbar = checkSnackbar;
var addSnackbar = function (props) { return __awaiter(void 0, void 0, void 0, function () {
    var snackbar, id, icon, span, close;
    return __generator(this, function (_a) {
        switch (_a.label) {
            case 0: return [4 /*yield*/, (0, exports.removeSnackbar)()];
            case 1:
                _a.sent();
                snackbar = document.createElement("div");
                id = snackbar.id = "id-".concat(new Date().getTime());
                snackbar.classList.add("snackbar", props.type);
                icon = (0, basic_1.createElementFromHTML)(props.type === "success" ? "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 52 52\">\n    <path d=\"M26,52A26,26,0,1,1,52,26,26,26,0,0,1,26,52ZM26,4A22,22,0,1,0,48,26,22,22,0,0,0,26,4Z\"/>\n    <path d=\"M23,37.6a2,2,0,0,1-1.41-.6l-9.2-9.19A2,2,0,0,1,15.22,25l9.19,9.19A2,2,0,0,1,23,37.6Z\"/>\n    <path d=\"M23,37.6a2,2,0,0,1-1.41-3.42L36.78,19a2,2,0,0,1,2.83,2.83L24.41,37A2,2,0,0,1,23,37.6Z\"/>\n</svg>" : "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\">\n    <g transform=\"translate(-6 -6)\">\n        <path d=\"M18,6A12,12,0,1,0,30,18,12,12,0,0,0,18,6Zm0,22A10,10,0,1,1,28,18,10,10,0,0,1,18,28Z\"/>\n        <path d=\"M18,20.07a1.3,1.3,0,0,1-1.3-1.3v-6a1.3,1.3,0,0,1,2.6,0v6a1.3,1.3,0,0,1-1.3,1.3Z\"/>\n        <circle cx=\"1.5\" cy=\"1.5\" r=\"1.5\" transform=\"translate(16.45 21.52)\"/>\n    </g>\n</svg>");
                span = document.createElement("span");
                span.innerText = props.message;
                close = (0, basic_1.createElementFromHTML)("<button><svg xmlns=\"http://www.w3.org/2000/svg\" class=\"close\" viewBox=\"0 0 32 32\">\n        <path id=\"close-svgrepo-com\" d=\"M8.169,5.144A2.139,2.139,0,0,0,5.145,8.169L17.494,20.518,5.145,32.867a2.138,2.138,0,1,0,3.024,3.024L20.519,23.542,32.868,35.892a2.138,2.138,0,0,0,3.024-3.024L23.543,20.518,35.892,8.169a2.138,2.138,0,0,0-3.024-3.024L20.519,17.494Z\" transform=\"translate(-4.519 -4.518)\" fill=\"#fff\"/>\n    </svg></button>");
                snackbar.appendChild(icon);
                snackbar.appendChild(span);
                snackbar.appendChild(close);
                close.addEventListener("click", function () { return (0, exports.removeSnackbar)(id); });
                document.body.appendChild(snackbar);
                setTimeout(function () { return (0, exports.removeSnackbar)(id); }, 5300);
                return [2 /*return*/];
        }
    });
}); };
exports.addSnackbar = addSnackbar;
var removeSnackbar = function (id) { return __awaiter(void 0, void 0, void 0, function () {
    var snackbar;
    return __generator(this, function (_a) {
        snackbar = document.querySelector(".snackbar".concat(id ? "#".concat(id) : ""));
        if (!snackbar)
            return [2 /*return*/];
        if (snackbar.classList.contains("closing")) {
            return [2 /*return*/, new Promise(function (resolve) { return __awaiter(void 0, void 0, void 0, function () {
                    var i;
                    return __generator(this, function (_a) {
                        switch (_a.label) {
                            case 0:
                                i = 0;
                                _a.label = 1;
                            case 1:
                                if (!(i < 30)) return [3 /*break*/, 4];
                                if (!(snackbar === null || snackbar === void 0 ? void 0 : snackbar.classList.contains("closing"))) {
                                    resolve();
                                    return [3 /*break*/, 4];
                                }
                                return [4 /*yield*/, (0, basic_1.sleep)(10)];
                            case 2:
                                _a.sent();
                                _a.label = 3;
                            case 3:
                                i++;
                                return [3 /*break*/, 1];
                            case 4:
                                resolve();
                                return [2 /*return*/];
                        }
                    });
                }); })];
        }
        snackbar.classList.add("closing");
        return [2 /*return*/, new Promise(function (resolve) {
                setTimeout(function () {
                    var _a;
                    (_a = snackbar === null || snackbar === void 0 ? void 0 : snackbar.parentNode) === null || _a === void 0 ? void 0 : _a.removeChild(snackbar);
                    resolve();
                }, 300);
            })];
    });
}); };
exports.removeSnackbar = removeSnackbar;
