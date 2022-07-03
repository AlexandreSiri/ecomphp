export const sleep = (time: number) => (new Promise(resolve => setTimeout(resolve, time)))

export const createElementFromHTML = (htmlString: string) => {
    var div = document.createElement('div')
    div.innerHTML = htmlString.trim()

    return div.firstChild as HTMLElement
}

export const reloadScript = (src?: string) => {
    const scripts = Array.from(document.querySelectorAll(`script${src ? `[src*="${src}"]` : ""}`)) as HTMLScriptElement[]
    scripts.map(script => {
        const newScript = document.createElement("script")

        newScript.src = `${script.src}`
        newScript.defer = script.defer
        newScript.async = script.async
        
        const parent = script.parentElement
        if (!parent) return
        parent.removeChild(script)
        parent.appendChild(newScript)
    })
}

export const getParents = (el: EventTarget | HTMLElement | null) => {
    if (!el) return []
    let element = el as HTMLElement | null

    let parents: string[] = []
    while (element) {
        parents = [...parents, ...Array.from(element.classList)]
        element = element.parentElement
    }
    return parents.filter(f => !!f.length)
}

export const getParentsId = (el: EventTarget | HTMLElement | null) => {
    if (!el) return []
    let element = el as HTMLElement | null

    let parents: string[] = []
    while (element) {
        parents = [...parents, element.id]
        element = element.parentElement
    }
    return parents.filter(f => !!f.length)
}