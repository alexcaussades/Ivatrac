/** refresh automatiquement toutes les minutes cette url 127.0.0.1:8000/api/whazzup */

function url_apps() {
    if (location.hostname == "127.0.0.1") {
        local =
            "http://" +
            location.hostname +
            ":" +
            location.port +
            "/api/whazzup";
        return local;
    } else {
        local = "https://" + location.hostname + "/api/whazzup";
        return local;
    }
}

function refresh() {
    /** recharger la page  */
    location.reload();
}

setInterval(function () {
    Http = new XMLHttpRequest();
    Http.open("GET", url_apps());
    Http.send();
    fetch(url_apps()).then(function (response) {
        if (response.status == 200) {
            refresh();
        } else {
            console.log("error");
        }
    });
}, 120000);
