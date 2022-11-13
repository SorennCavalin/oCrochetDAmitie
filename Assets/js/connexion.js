window.addEventListener("load",() => {
    $("button").click((e) => {
        // e.preventDefault();
        $("button").removeClass("unclicked").addClass("clicked");
    })
})