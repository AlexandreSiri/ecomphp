import tinyDatePicker from "tiny-date-picker"
import submitForm from "../auth/submitForm"
import { getParents, getParentsId } from "../functions/basic"
import { addSnackbar } from "../functions/snackbar"

(() => {
    if (!window.location.pathname.startsWith("/account")) return
    
    const birthInput = document.querySelector("input#birthday") as HTMLInputElement | null
    if (birthInput) {
        tinyDatePicker({
            input: birthInput,
            format(date) {
                const day = date.getDate()
                const month = date.getMonth() + 1
                const year = date.getFullYear()
                return `${year}-${month < 10 ? `0${month}` : month}-${day < 10 ? `0${day}` : day}`
            },
            min: new Date("1910-01-01"),
            max: new Date(),
        })
    }

    const form = document.querySelector("form")
    form?.addEventListener("submit", async (e) => {
        e.preventDefault()
        const result = await submitForm(form)
        if (!result) return

        addSnackbar({
            type: "success",
            message: result.data
        })
        const inputs = Array.from(form.querySelectorAll("input[type='password']")) as HTMLInputElement[]
        inputs.map(input => input.value = "")
    })
    
    const illustration = document.querySelector(".illustration") as HTMLDivElement | null

    const deleteAddress = async (id: number) => {
        let response = await fetch(`/account/addresses/${id}`, {
            method: "DELETE"
        }).then(async res => {
            let response = await res.json()
            if (res.status >= 400) return addSnackbar({
                type: "error",
                message: response.data[0]
            }) 
            else return response
        }).catch(() => null)

        addSnackbar({
            type: "success",
            message: response.data
        }) 
    }
    const addresses = Array.from(document.querySelectorAll(".addresses-list .address")) as HTMLDivElement[]
    addresses.map(address => {
        const deleteButton = address.querySelector(".address-icon") as HTMLDivElement | null
        deleteButton?.addEventListener("click", () => {
            deleteAddress(parseInt(address.id))
            address.parentElement?.removeChild(address)
            if (!document.querySelectorAll(".addresses-list .address").length && illustration) illustration.classList.add("visible")
        })
    })

    let controller: AbortController | null = null
    const rateProduct = async (id: number, note: number) => {
        controller && controller.abort()
        controller = new AbortController()

        const form = new FormData()
        form.append("note", `${note}`)
        
        let response = await fetch(`/products/${id}/rate`, {
            method: "POST",
            body: form,
            signal: controller.signal
        }).then(res => res.text()).catch(() => null)
        if (!response) return

        const product = document.querySelector(`.order-product[data-id='${id}']`) as HTMLDivElement
        const stars = Array.from(product.querySelectorAll(".star")) as HTMLDivElement[]
        stars.map((star, index) => note > index ? star.classList.add("filled") : star.classList.remove("filled"))

        product.classList.add("review")
    }

    const orderProducts = Array.from(document.querySelectorAll(".order-product")) as HTMLDivElement[]
    orderProducts.map(orderProduct => orderProduct.addEventListener("click", async e => {
        if (getParents(e.target).includes("write")) {
            const review = orderProduct.querySelector(".review")
            review?.classList.add("writing")
        }
        if (getParents(e.target).includes("cancel")) {
            const review = orderProduct.querySelector(".review")
            review?.classList.remove("writing")
        }
        if (getParents(e.target).includes("submit")) {
            let content = (orderProduct.querySelector("textarea") as HTMLTextAreaElement).value
            let id = parseInt(orderProduct.dataset.id ?? "")
            
            const form = new FormData()
            form.append("content", `${content}`)
            
            let response = await fetch(`/products/${id}/comment`, {
                method: "POST",
                body: form,
            }).then(async res => {
                let response = await res.json()
                if (res.status >= 400) return addSnackbar({
                    type: "error",
                    message: response.data[0]
                }) 
                else return response
            }).catch(() => null)
            if (!response) return

            const review = orderProduct.querySelector(".review")
            review?.classList.remove("writing")
            orderProduct.classList.remove("review")

            const p = document.createElement("p")
            p.innerText = content
            orderProduct.querySelector(".note")?.appendChild(p)
        }
    }))

    const ratings = Array.from(document.querySelectorAll(".rating.ratable")) as HTMLDivElement[]
    ratings.map(rating => {
        let classes = ["hover-1", "hover-2", "hover-3", "hover-4", "hover-5"]
        let star: number | null = null

        rating.addEventListener("mouseleave", () => {
            rating.classList.remove(...classes)
            star = null
        })
        rating.addEventListener("mousemove", (e) => {
            const ids = getParentsId(e.target).map(id => parseInt(id)).filter(id => !isNaN(id))
            if (ids.length !== 2) {
                star = null
                return rating.classList.remove(...classes)
            }

            star = ids[0]
            if (rating.classList.contains(`hover-${star}`)) return
            rating.classList.remove(...classes)
            rating.classList.add(`hover-${star}`)
        })
        rating.addEventListener("mousedown", (e) => {
            if (!star) return
            e.preventDefault()
            
            const productId = parseInt(rating.id)
            rateProduct(productId, star)
        })
    })

    const reviews = Array.from(document.querySelectorAll(".reviews-list .review-row")) as HTMLDivElement[]
    reviews.map(review => {
        let trash = review.querySelector(".trash") as HTMLDivElement
        trash.addEventListener("click", async () => {
            let id = parseInt(trash.dataset.id ?? "")
            
            let response = await fetch(`/products/review/${id}`, {
                method: "DELETE"
            }).then(async res => {
                let response = await res.json()
                if (res.status >= 400) return addSnackbar({
                    type: "error",
                    message: response.data[0]
                }) 
                else return response
            }).catch(() => null)
            if (!response) return

            let r = document.querySelector(`.review-row[data-id="${id}"]`)
            r?.parentElement?.removeChild(r)

            if (!document.querySelectorAll(".review-row").length) {
                let list = document.querySelector(".reviews-list")
                list?.parentNode?.removeChild(list)

                illustration?.classList.add("visible")
            }
        })
    })
})()

export {}