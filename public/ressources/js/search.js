
//pour les recherches dans un tableau
let input = document.querySelector("#search");

document.addEventListener("DOMContentLoaded", function () {

    //au moment où on entre des caractères, la fonction myFunction() s'exécute en recherchant tout les mots comportants ces caractères
    input.addEventListener("keyup", (e) => {
        e.preventDefault();
        let input, filter, table, tr, td, i, inputValue;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                inputValue = td.textContent || td.innerText;
                if (inputValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    })

})