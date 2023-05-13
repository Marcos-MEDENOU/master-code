document.addEventListener("DOMContentLoaded", () => {

    let clique = document.querySelectorAll(".clique");
    let all_checked = document.getElementById("all_checked");
    let deletes = document.querySelector(".delete");
    let x = 0;
    all_checked.addEventListener("click", () => {
        if (x === 0) {
            clique.forEach(el => {
                el.checked = true;
            })
            x++
        } else {
            clique.forEach(el => {
                el.checked = false;
            })
            x = 0;
        }
    })

    deletes.addEventListener("click", () => {
        let res = "";
        let tab = [];
        clique.forEach(el => {
            if (el.checked === true) {
                tab.push(el);
            }
        })
        clique.forEach(el => {
            if (el.checked === true) {
                let parent = el.parentElement.parentElement;
                let data = {
                    id: el.value,
                }
                data = JSON.stringify(data);
                let option = {
                    header: {
                        content: "application/json"
                    },
                    body: data,
                    method: "post"
                }
                fetch("?ajax=contact-controller&action=delete-one-contact", option).then(response => response.json()).then(response => {
                    res += response;
                    if (res === "supprime".repeat(tab.length)) {
                        window.location.href = window.location.href;
                    }
                })
                parent.remove();
            }
        })
    })
})