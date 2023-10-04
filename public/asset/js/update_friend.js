/** refresh automatiquement toutes les minutes cette url 127.0.0.1:8000/api/whazzup */


function refresh() {
    /** recherche de url */
    const url = new URL(window.location.href);
    //console.log(url);
    //const urls = window.location.href;
    /** faire une requette sur API du serveur ivao */
    const url_api = url.origin + "/api/whazzup";
    return url_api;   
}

/** requette ajax 
 * @todo faire une requette ajax pour rafraichir la page
*/
setInterval(function () {

    const url = new URL(window.location.href);
    Http = new XMLHttpRequest();
    Http.open("GET", refresh());
    Http.send();
    fetch(refresh()).then(function (response) {
        if (response.status == 200) {
            refresh();
            window.location.reload();

        } else {
            console.log("error");
        }
        
    });
}, 120000);


function time_refresh(){
    /** faire une function qui decompte le temps avant le prochain update */
    const time = 120000;
    const time_refresh = time / 1000;
    const time_refresh_min = time_refresh / 60;
    const time_refresh_sec = time_refresh % 60;
    const time_refresh_min_sec = time_refresh_min + " min " + time_refresh_sec + " sec";
    return time_refresh_min_sec;
}

