// Loader
$(window).on('load', function () {
    $(".loader-fleetline").fadeOut(400);

    $('.loader-on').click(function (e) {
        e.preventDefault();
        var goTo = this.getAttribute("href");

        $(".loader-fleetline").fadeIn(400);

        setTimeout(function(){
            window.location = goTo;
        }, 400);
    });
});





// Animation bandereau réussite
function successAnimation(text) {
    $("#success_msg").html(text);
    $("#success_msg").fadeIn(1000);
    $("#overlay_success").slideDown();
    setTimeout(function(){
        $("#overlay_success").fadeOut(1000);
    }, 1500);
}

// Animation bandereau erreur
function errorAnimation(text) {
    $("#error_msg").html(text);
    $("#error_msg").fadeIn(1000);
    $("#overlay_error").slideDown();
    setTimeout(function(){
        $("#overlay_error").fadeOut(1000);
    }, 1500);
}





// Return la valeur d'un query
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
    results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}





// Ouverture de l'overlay
function openNav() {
    document.getElementById("myNav").style.width = "100%";
    setTimeout(function() {
        $(".overlay-content").fadeIn(500);
    }, 200);
}

// Fermeture de l'overlay
function closeNav() {
    $(".overlay-content").fadeOut(100);
    document.getElementById("myNav").style.width = "0%";
}




// Bing Map API
function loadMapScenario() {
    var map = new Microsoft.Maps.Map(document.getElementById('myMap'), {
        /* No need to set credentials if already passed in URL */
        center: new Microsoft.Maps.Location(47.606209, -122.332071),
        zoom: 12
    });

    Microsoft.Maps.loadModule('Microsoft.Maps.AutoSuggest', function () {
        var options = {
            maxResults: 4,
            map: map
        };
        var manager = new Microsoft.Maps.AutosuggestManager(options);
        manager.attachAutosuggest('#searchBox', '#searchBoxContainer', selectedSuggestion);
    });

    function selectedSuggestion(suggestionResult) {
        map.entities.clear();
        map.setView({ bounds: suggestionResult.bestView });
        var pushpin = new Microsoft.Maps.Pushpin(suggestionResult.location);
        map.entities.push(pushpin);
        document.getElementById('searchBox').innerHTML = suggestionResult.formattedSuggestion;
    }

}
