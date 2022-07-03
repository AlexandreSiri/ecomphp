"use strict";
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
exports.getParentsId = exports.getParents = exports.reloadScript = exports.createElementFromHTML = exports.sleep = void 0;
var sleep = function (time) { return (new Promise(function (resolve) { return setTimeout(resolve, time); })); };
exports.sleep = sleep;
var createElementFromHTML = function (htmlString) {
    var div = document.createElement('div');
    div.innerHTML = htmlString.trim();
    return div.firstChild;
};
exports.createElementFromHTML = createElementFromHTML;
var reloadScript = function (src) {
    var scripts = Array.from(document.querySelectorAll("script".concat(src ? "[src*=\"".concat(src, "\"]") : "")));
    scripts.map(function (script) {
        var newScript = document.createElement("script");
        newScript.src = "".concat(script.src);
        newScript.defer = script.defer;
        newScript.async = script.async;
        var parent = script.parentElement;
        if (!parent)
            return;
        parent.removeChild(script);
        parent.appendChild(newScript);
    });
};
exports.reloadScript = reloadScript;
var getParents = function (el) {
    if (!el)
        return [];
    var element = el;
    var parents = [];
    while (element) {
        parents = __spreadArray(__spreadArray([], parents, true), Array.from(element.classList), true);
        element = element.parentElement;
    }
    return parents.filter(function (f) { return !!f.length; });
};
exports.getParents = getParents;
var getParentsId = function (el) {
    if (!el)
        return [];
    var element = el;
    var parents = [];
    while (element) {
        parents = __spreadArray(__spreadArray([], parents, true), [element.id], false);
        element = element.parentElement;
    }
    return parents.filter(function (f) { return !!f.length; });
};
exports.getParentsId = getParentsId;
