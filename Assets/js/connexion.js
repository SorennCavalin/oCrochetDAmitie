window.addEventListener("load",() => {
    $("button").click((e) => {
        // e.preventDefault();
        $("button").removeClass("unclicked").addClass("clicked");
    })

    $('.loupe').click(() => {
        let mdp = $('#mdp');
        if (mdp.attr("type") == "text") {
            mdp.attr("type", "password");
        } else {
            mdp.attr("type", "text");
        }
    })
    
    $("#inscription").click((e) => {
        e.preventDefault();
        window.location = $("#inscription").attr("url");
    })
})