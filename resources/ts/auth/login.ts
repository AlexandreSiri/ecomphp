import submitForm from "./submitForm"

(() => {
    const form = document.querySelector("form#login, form#register, form#reset") as HTMLFormElement | null
    if (!form) return

    form.addEventListener("submit", async (e) => {
        e.preventDefault()
        const result = await submitForm(form)
        if (!result) return

        window.location.href = "/"
    })
})()

export {}