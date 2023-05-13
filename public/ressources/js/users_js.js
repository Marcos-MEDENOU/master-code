document.addEventListener("DOMContentLoaded", () => {

    let clique = document.querySelectorAll(".clique");
    let all_checked = document.getElementById("all_checked");
    let update = document.querySelector(".update");
    let deletes = document.querySelector(".delete");
    let x = 0;

    all_checked.addEventListener("click", () => {
        if (x === 0) {
            clique.forEach(el => {
                el.checked = true;
            })
            update.style = "background-color: rgb(33, 64, 96);";
            update.setAttribute("disabled", "disabled");
            x++
        } else {
            clique.forEach(el => {
                el.checked = false;
            })
            update.style = "background-color: rgb(58, 142, 226);";
            update.removeAttribute("disabled", "disabled");
            x = 0;
        }
    })

    let longueur = 0;
    clique.forEach(el => {
        el.addEventListener("click", () => {
            if (el.checked === true) {
                longueur += 1;
            } else {
                longueur -= 1;
            }

            if (longueur == 1) {
                update.style = "background-color: rgb(58, 142, 226);";
                update.removeAttribute("disabled", "disabled");
            } else {
                update.style = "background-color: rgb(33, 64, 96);";
                update.setAttribute("disabled", "disabled");
            }
        })
    })

    update.addEventListener("click", () => {
        clique.forEach(el => {
            if (el.checked === true) {
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
                fetch("?ajax=user-controller&action=redirect", option).then(response => response.json()).then(response => {
                    window.location.href = response;
                })
            }
        })
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
                fetch("?ajax=user-controller&action=delete-one-user", option).then(response => response.json()).then(response => {
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