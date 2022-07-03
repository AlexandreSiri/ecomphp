"use strict";
exports.__esModule = true;
// import Turbolinks from "turbolinks"
// import { reloadScript } from "./functions/basic"
var snackbar_1 = require("./functions/snackbar");
require("./auth");
require("./products");
require("./cart");
require("./account");
require("../scss/main.scss");
Array.from(document.querySelectorAll("a[href='#']")).map(function (a) { return a.addEventListener("click", function (e) { return e.preventDefault(); }); });
(0, snackbar_1.checkSnackbar)();
// Turbolinks.start()
// let first = true
// document.addEventListener("turbolinks:load", () => {
//     if (first) checkSnackbar()
//     else reloadScript()
//     first = false
// })
