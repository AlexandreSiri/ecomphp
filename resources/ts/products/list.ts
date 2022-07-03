import { getParents } from "../functions/basic"

interface Option {
    category: string[];
    size: string[];
    color: string[];
    sort: "newest" | "low" | "high",
}

(() => {
    if (!document.querySelector("body > .products")) return
    
    const options: Option = {
        category: [],
        size: [],
        color: [],
        sort: "newest"
    }
    let open: keyof Option | null = null

    let controller: AbortController | null = null
    const fetchProducts = async () => {
        controller && controller.abort()
        controller = new AbortController()

        const form = new FormData()
        options.category.map(c => form.append("category[]", c))
        options.size.map(c => form.append("size[]", c))
        options.color.map(c => form.append("color[]", c))
        form.append("sort", options.sort)
        
        let response = await fetch("/products", {
            method: "POST",
            body: form,
            signal: controller.signal
        }).then(res => res.text()).catch(() => null)
        if (!response) return

        let element = document.createElement("div")
        element.innerHTML = response
        
        let products = element.querySelector(".products-list")
        let productsElement = document.querySelector(".products-list")
        if (!products || !productsElement) return

        productsElement.innerHTML = products.innerHTML
    }

    const filters = Array.from(document.querySelectorAll(".filter[id]")) as HTMLDivElement[]
    filters.map(filter => {
        const id = filter.id as keyof Option
        const label = filter.querySelector(".label") as HTMLDivElement | null
        label?.addEventListener("click", () => {
            filters.filter(f => f.id !== id).map(filter => filter.classList.remove("open"))
            if (id === open) {
                open = null
                filter.classList.remove("open")
            } else {
                open = id
                filter.classList.add("open") 
            }
        })

        if (id !== "sort") {
            const choices = Array.from(filter.querySelectorAll("input"))
            choices.map(choice => choice.addEventListener("change", e => {
                e.preventDefault()

                const values = options[id].filter(f => f !== choice.id)
                options[id] = choice.checked ? [...values, choice.id] : values
                fetchProducts()
            }))
        } else {
            const choices = Array.from(filter.querySelectorAll("input"))
            choices.map(choice => choice.addEventListener("change", e => {
                e.preventDefault()
                if (choice.id === options.sort) choice.checked = true
                else {
                    choices.filter(f => f.id !== choice.id).map(c => c.checked = false)
                    options.sort = choice.id as Option["sort"]
                    let text = choice.parentElement?.querySelector("label")?.innerText ?? ""
                    let l = label?.querySelector("div")
                    if (!l) return
                    l.innerText = text
                }
                fetchProducts()
            }))
        }
    })

    document.addEventListener("mousedown", e => {
        const parents = getParents(e.target)
        if (parents.includes("filter") || !open) return

        filters.map(filter => filter.classList.remove("open"))
        open = null
    })
})()

export {}