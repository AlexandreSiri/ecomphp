import { addSnackbar } from "../functions/snackbar"

(() => {
    if (!document.querySelector("body > .checkout")) return

    let controller: AbortController | null = null

    const changePrice = () => {
        const products = Array.from(document.querySelectorAll(".cart-product")) as HTMLDivElement[]
        const price = products.reduce((r: number, product: HTMLDivElement) => {
            const p = product.querySelector(".price") as HTMLTitleElement
            const count = product.querySelector(".count span") as HTMLSpanElement

            return r + parseFloat(p.dataset.price || "") * parseInt(count.innerText)
        }, 0)
        
        const dutyFree = document.querySelector("li.duty-free span:last-child") as HTMLLIElement
        const vat = document.querySelector("li.vat span:last-child") as HTMLLIElement
        const total = document.querySelector("li.total span:last-child") as HTMLLIElement
        const button = document.querySelector(".payment-foot button") as HTMLButtonElement

        dutyFree.innerText = `$${price}`
        vat.innerText = `$${price * 0.2}`
        total.innerText = `$${price * 1.2}`
        button.innerText = `Pay $${price * 1.2}`
        
        if (!products.length) {
            const illustration = document.querySelector(".illustration") as HTMLDivElement
            illustration.classList.add("visible")

            button.disabled = true
        }
    }
    const removeProduct = async (id: number) => {
        controller && controller.abort()
        controller = new AbortController()

        const product = document.querySelector(`.cart-product[id="${id}"]`) as HTMLDivElement
        const button = document.querySelector(".less") as HTMLButtonElement
        const countElement = product.querySelector("span") as HTMLSpanElement
        const count = parseInt(countElement.innerText ?? "")
        
        button.disabled = true
        let response = await fetch(`/cart/products/${id}`, {
            method: "DELETE",
            signal: controller.signal
        }).then(res => res.json()).catch(() => null)
        button.disabled = false
        if (!response) return
        
        let badge = document.querySelector(".navbar .badge") as HTMLDivElement
        badge.innerText = response.data.count
        if (count === 1) product.parentElement?.removeChild(product)
        else countElement.innerText = `${count - 1}`

        changePrice()
    }
    const addProduct = async (id: number) => {
        controller && controller.abort()
        controller = new AbortController()

        const product = document.querySelector(`.cart-product[id="${id}"]`) as HTMLDivElement
        const button = document.querySelector(".less") as HTMLButtonElement
        const countElement = product.querySelector("span") as HTMLSpanElement
        const count = parseInt(countElement.innerText ?? "")
        
        button.disabled = true
        let response = await fetch(`/cart/products/${id}`, {
            method: "POST",
            signal: controller.signal
        }).then(res => res.json()).catch(() => null)
        button.disabled = false
        if (!response) return
        
        let badge = document.querySelector(".navbar .badge") as HTMLDivElement
        badge.innerText = response.data.count
        countElement.innerText = `${count + 1}`

        changePrice()
    }
    const deleteProduct = async (id: number) => {
        controller && controller.abort()
        controller = new AbortController()

        const product = document.querySelector(`.cart-product[id="${id}"]`) as HTMLDivElement
        const button = document.querySelector(".less") as HTMLButtonElement
        
        button.disabled = true
        let response = await fetch(`/cart/products/${id}/all`, {
            method: "DELETE",
            signal: controller.signal
        }).then(res => res.json()).catch(() => null)
        button.disabled = false
        if (!response) return
        
        let badge = document.querySelector(".navbar .badge") as HTMLDivElement
        badge.innerText = response.data.count
        product.parentElement?.removeChild(product)
        
        changePrice()
    }

    const products = Array.from(document.querySelectorAll(".cart-product")) as HTMLDivElement[]
    products.map(product => {
        const id = parseInt(product.id)

        const less = product.querySelector(".less") as HTMLButtonElement
        const more = product.querySelector(".more") as HTMLButtonElement
        const del = product.querySelector(".delete") as HTMLButtonElement

        less.addEventListener("click", () => removeProduct(id))
        more.addEventListener("click", () => addProduct(id))
        del.addEventListener("click", () => deleteProduct(id))
    })

    const form = document.querySelector("form") as HTMLFormElement
    form.addEventListener("submit", async e => {
        e.preventDefault()
        controller && controller.abort()
        controller = new AbortController()

        let formData = new FormData(form)
        const button = document.querySelector(".payment-foot button") as HTMLButtonElement
        button.disabled = true

        let response = await fetch(form.action, {
            method: "POST",
            body: formData,
            signal: controller.signal
        }).then(async res => {
            let response = await res.json()
            if (res.status >= 400) return addSnackbar({
                type: "error",
                message: response.data[0]
            }) 
            else return response
        }).catch(() => null)
        button.disabled = false
        if (!response) return

        window.location.href = response.data
    })

    const addresses = Array.from(document.querySelectorAll(".addresses > div")) as HTMLDivElement[]
    const addressForm = document.querySelector(".delivery-address") as HTMLDivElement
    const addressInput = document.querySelector("input[type='hidden']") as HTMLInputElement
    addresses.map(address => address.addEventListener("click", () => {
        if (address.id === "add") {
            addressInput.value = "0"
            addressForm.classList.add("active")
        } else {
            addressInput.value = address.id
            addressForm.classList.remove("active")
        }

        addresses.map(address => address.classList.remove("active"))
        address.classList.add("active")
    }))
})()

export {}