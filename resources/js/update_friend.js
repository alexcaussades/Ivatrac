/** refresh automatiquement toutes les minutes cette url 127.0.0.1:8000/api/whazzup */
setInterval(function () {
    $.ajax({
        url: "http://127.0.0.1:8000/api/whazzup",
        type: "GET",
        dataType: "json",
        success: function (data) {
            console.log(data);
        },
    });
}, 60000);
