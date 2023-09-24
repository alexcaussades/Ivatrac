/** refresh automatiquement toutes les minutes cette url 127.0.0.1:8000/api/whazzup */


function refresh() {
    /** recharger la page  */
    location.reload();
}

setInterval(function () {
    Http = new XMLHttpRequest();
    Http.open("GET", refresh());
    Http.send();
    fetch(url_apps()).then(function (response) {
        if (response.status == 200) {
            refresh();
        } else {
            console.log("error");
        }
    });
}, 120000);
