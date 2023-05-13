
let mail = document.querySelector("#mail");
let subject = document.querySelector("#subject");
let message = document.querySelector("#message");
let btn_msg = document.querySelector("#btn-msg");
document.addEventListener("DOMContentLoaded", () => {
    btn_msg.addEventListener("click", (e) => {
        e.preventDefault();
        
        if ((mail.value.trim() !== "") && (subject.value.trim() !== "") && (message.value.trim() !== "")) {
            let data = {
                mail: mail.value,
                subject: subject.value,
                message: message.value
            }
            data = JSON.stringify(data);
            let options = {
                header: {
                    content: "application/json"
                },
                body: data,
                method: "post"
            }
            let url = window.location.href;
            let contactid = url.split("&")[1];
            fetch("?ajax=contact-controller&action=send-message&" + contactid, options).then(response => response.json()).then(response => {
                window.location.href = response;
            })
        }
    })
})