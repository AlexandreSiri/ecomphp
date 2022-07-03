// import Turbolinks from "turbolinks"
// import { reloadScript } from "./functions/basic"
import { checkSnackbar } from "./functions/snackbar"
import "./auth"
import "./products"
import "./cart"
import "./account"
import "../scss/main.scss"

(Array.from(document.querySelectorAll("a[href='#']")) as HTMLAnchorElement[]).map(a => a.addEventListener("click", e => e.preventDefault()))

checkSnackbar()

// Turbolinks.start()
// let first = true

// document.addEventListener("turbolinks:load", () => {
//     if (first) checkSnackbar()
//     else reloadScript()
    
//     first = false
// })