import { getParents } from "../functions/basic"
import { addSnackbar } from "../functions/snackbar"



(() => {
    if (!document.querySelector("body > .products-detail")) return
    
    let sort: "newest" | "low" | "high" = "newest"
    let open = false

    let size = parseFloat(document.querySelector("#size .choice.selected")?.id ?? "")
    let button = document.querySelector("button#add-cart") as HTMLButtonElement

    let controller: AbortController | null = null
    const fetchComments = async () => {
        controller && controller.abort()
        controller = new AbortController()

        const form = new FormData()
        form.append("sort", sort)
        
        let response = await fetch("", {
            method: "POST",
            body: form,
            signal: controller.signal
        }).then(res => res.text()).catch(() => null)
        if (!response) return

        let element = document.createElement("div")
        element.innerHTML = response
        
        let comments = element.querySelector(".reviews-list")
        let commentsElement = document.querySelector(".reviews-list")
        if (!comments || !commentsElement) return

        commentsElement.innerHTML = comments.innerHTML
    }
    const addToCart = async () => {
        button.disabled = true
        controller && controller.abort()
        controller = new AbortController()

        let response = await fetch(`/cart/products/${size}`, {
            method: "POST",
            signal: controller.signal
        }).then(res => res.json())
        button.disabled = false
        if (!response) return

        let badge = document.querySelector(".navbar .badge") as HTMLDivElement
        badge.innerText = response.data.count
        addSnackbar({
            type: "success",
            message: response.data.message
        })
    }

    
    button.addEventListener("click", e => {
        if (button.disabled) return
        addToCart()
    })
    const filter = document.querySelector(".filter") as HTMLDivElement | null
    const label = filter?.querySelector(".label") as HTMLDivElement | null
    label?.addEventListener("click", () => {
        if (open) {
            open = false
            filter?.classList.remove("open")
        } else {
            open = true
            filter?.classList.add("open") 
        }
    })

    const inputs = Array.from(filter?.querySelectorAll("input") || [])
    inputs.map(input => input.addEventListener("change", e => {
        e.preventDefault()
        if (input.id === sort) input.checked = true
        else {
            inputs.filter(f => f.id !== input.id).map(c => c.checked = false)
            sort = input.id as "newest" | "low" | "high"
            let text = input.parentElement?.querySelector("label")?.innerText ?? ""
            let l = label?.querySelector("div")
            if (!l) return
            l.innerText = text
        }
        fetchComments()
    }))

    document.addEventListener("mousedown", e => {
        const parents = getParents(e.target)
        if (parents.includes("filter") || !open) return

        filter?.classList.remove("open")
        open = false
    })

    const bigImage = document.querySelector(".image-large") as HTMLDivElement
    const images = Array.from(document.querySelectorAll(".images-list .image")) as HTMLDivElement[]
    images.map(image => image.addEventListener("click", () => {
        let url = image.dataset.image ?? ""
        if (bigImage.style.backgroundImage.includes(url)) return
        bigImage.style.backgroundImage = `url("${url}")`
        images.map(img => img.classList.remove("selected"))
        image.classList.add("selected")
    }))

    const sizeChoices = Array.from(document.querySelectorAll("#size .choice")) as HTMLDivElement[]
    sizeChoices.map(s => s.addEventListener("click", () => {
        if (s.classList.contains("selected") || s.classList.contains("disabled")) return
        
        let value = document.querySelector("#size .value") as HTMLDivElement
        sizeChoices.map(s => s.classList.remove("selected"))
        s.classList.add("selected")
        size = parseFloat(s.id)
        value.innerText = s.innerText
    }))

})()

export {}