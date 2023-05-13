
let modifier = document.querySelector("#modifier");
let state = document.querySelector("#status");
document.addEventListener("DOMContentLoaded", () => {
    modifier.addEventListener("click", (e) => {
        e.preventDefault();
        if (state.value !== "default") {
            let data = {
                status: state.value
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
            let userid = url.split("&")[1];
            fetch("?ajax=user-controller&action=update-one-user&" + userid, options).then(response => response.json()).then(response => {
                window.location.href = response;
            })
        }
    })
})