import { createElementFromHTML, sleep } from "./basic"

interface SnackbarOptions {
    type: "success" | "error";
    message: string;
}

export const checkSnackbar = () => {
    const element = document.querySelector("snackbar") as HTMLDivElement | null
    if (!element) return

    addSnackbar({
        type: element.getAttribute("type") as SnackbarOptions["type"],
        message: element.innerText 
    })
    element.parentNode?.removeChild(element)
}

export const addSnackbar = async (props: SnackbarOptions) => {
    await removeSnackbar()

    const snackbar = document.createElement("div")
    let id = snackbar.id = `id-${new Date().getTime()}`
    snackbar.classList.add("snackbar", props.type)

    const icon = createElementFromHTML(props.type === "success" ? `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
    <path d="M26,52A26,26,0,1,1,52,26,26,26,0,0,1,26,52ZM26,4A22,22,0,1,0,48,26,22,22,0,0,0,26,4Z"/>
    <path d="M23,37.6a2,2,0,0,1-1.41-.6l-9.2-9.19A2,2,0,0,1,15.22,25l9.19,9.19A2,2,0,0,1,23,37.6Z"/>
    <path d="M23,37.6a2,2,0,0,1-1.41-3.42L36.78,19a2,2,0,0,1,2.83,2.83L24.41,37A2,2,0,0,1,23,37.6Z"/>
</svg>` : `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
    <g transform="translate(-6 -6)">
        <path d="M18,6A12,12,0,1,0,30,18,12,12,0,0,0,18,6Zm0,22A10,10,0,1,1,28,18,10,10,0,0,1,18,28Z"/>
        <path d="M18,20.07a1.3,1.3,0,0,1-1.3-1.3v-6a1.3,1.3,0,0,1,2.6,0v6a1.3,1.3,0,0,1-1.3,1.3Z"/>
        <circle cx="1.5" cy="1.5" r="1.5" transform="translate(16.45 21.52)"/>
    </g>
</svg>`)

    const span = document.createElement("span")
    span.innerText = props.message
    
    const close = createElementFromHTML(`<button><svg xmlns="http://www.w3.org/2000/svg" class="close" viewBox="0 0 32 32">
        <path id="close-svgrepo-com" d="M8.169,5.144A2.139,2.139,0,0,0,5.145,8.169L17.494,20.518,5.145,32.867a2.138,2.138,0,1,0,3.024,3.024L20.519,23.542,32.868,35.892a2.138,2.138,0,0,0,3.024-3.024L23.543,20.518,35.892,8.169a2.138,2.138,0,0,0-3.024-3.024L20.519,17.494Z" transform="translate(-4.519 -4.518)" fill="#fff"/>
    </svg></button>`)

    snackbar.appendChild(icon)
    snackbar.appendChild(span)
    snackbar.appendChild(close)

    close.addEventListener("click", () => removeSnackbar(id))
    
    document.body.appendChild(snackbar)
    setTimeout(() => removeSnackbar(id), 5300)
}

export const removeSnackbar = async (id?: string) => {
    let snackbar = document.querySelector(`.snackbar${id ? `#${id}` : ""}`) as HTMLDivElement | null
    if (!snackbar) return
    
    if (snackbar.classList.contains("closing")) {
        return new Promise<void>(async resolve => {
            for (let i = 0; i < 30; i++) {
                if (!snackbar?.classList.contains("closing")) {
                    resolve()
                    break
                }
                await sleep(10)
            }
            resolve()
        })
    }

    snackbar.classList.add("closing")
    return new Promise<void>(resolve => {
        setTimeout(() => {
            snackbar?.parentNode?.removeChild(snackbar)
            resolve()
        }, 300)
    })
}