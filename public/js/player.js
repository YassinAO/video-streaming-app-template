/**
 * Don't use ES6+ syntax, because it won't be compiled by babel.
 * What is babel? https://babeljs.io/docs/en/
 */
var player = videojs('video')
var viewLogged = false

player.on('timeupdate', function () {
    var percentagePlayed = Math.ceil(player.currentTime() / player.duration() * 100)

    if(percentagePlayed > 5 && !viewLogged) {
        axios.put('/videos/' + window.CURRENT_VIDEO);
        
        console.log(percentagePlayed);
        viewLogged = true
    }
});