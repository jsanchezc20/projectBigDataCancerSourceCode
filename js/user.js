function checkUser(objInput, blnNewUser = false) {
    if (objInput.value.split().length === 0) {
        return false
    }

    /*  (?!\.)      - No permet punt a l'inici.
        (?!.*?\.\.) - No permet punts consecutius.
        (?!.*\.$)   - No permet punt al final. */
    let regExp = /^(?!\.)(?!.*\.$)(?!.*?\.\.)[a-zA-Z\d_.]+$/;
    let strMsgError = "";

    switch (objInput.id) {
        case "name":
            if (objInput.value.length < 3 || objInput.value.length > 30) {
                strMsgError = "Nom d'usuari ha de tenir entre 3 i 30 caràcters.";
            } else if (! objInput.value.match(regExp)) {
                strMsgError = "Només es permeten lletres (a-z), números (0-9) i punts (.).";
            }
            break;
        case "password":
            if (objInput.value.length < 8 || objInput.value.length > 15) {
                strMsgError = "La contrasenya ha de tenir entre 8 i 15 caràcters.";
            }
            break;
    }

    if (blnNewUser) {
        var objAjax = new XMLHttpRequest();

        objAjax.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText === objInput.value) {
                    strMsgError = "Aquest nom d'usuari ja està en ús. Prova amb un altre."
                }
            }
        };

        objAjax.open("POST", "/user/check", true);
        objAjax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        objAjax.send(`user=${objInput.value}`);
    }

    if (strMsgError.length) {
        (objInput.id+"Err").style.visibility = "visible";
    }
}