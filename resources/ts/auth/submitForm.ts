import { addSnackbar } from "../functions/snackbar"

const submitForm = async (form: HTMLFormElement): Promise<any> => {
    console.log("test")
    return new Promise(resolve => {
        fetch("", {
            method: "POST",
            body: new FormData(form)
        }).then(async res => {
            let response = await res.json()
            if (res.status >= 400) {
                addSnackbar({
                    type: "error",
                    message: response.data[0]
                })
                resolve(null)
            }
            else resolve(response)
        })
    })
}

export default submitForm