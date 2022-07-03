import { addSnackbar } from "../functions/snackbar"
import submitForm from "./submitForm"

(() => {
    const form = document.querySelector("form#forgot") as HTMLFormElement | null
    if (!form) return

    form.addEventListener("submit", async (e) => {
        e.preventDefault()
        const result = await submitForm(form)
        if (!result) return
        
        const message = result.data as string
        addSnackbar({
            type: "success",
            message
        })
    })
})()

export {}