require('./bootstrap');

let search_autocomplete_timeout;

$(document).ready(function () {
    let $search = $("#search");

    $search.on("input", function () {
        if(search_autocomplete_timeout)
            clearTimeout(search_autocomplete_timeout);

        search_autocomplete_timeout = setTimeout(function(input){
            if(!input.value) {
                build_autocomplete_results([]); //deletes previous results
                return;
            }

            $.get("/ajax/search", $("#search_form").serialize(), function(response){
                if(response.success){
                    build_autocomplete_results(response.results);
                }
            }, "json")
        }, 300, this)
    })

    $search.on("blur", function(e){
        if(!$(e.relatedTarget).is(".autocomplete-results-item"))
            build_autocomplete_results([]);
    })

    $("#search_form").on("submit", function(e){
        e.preventDefault();

        let href = $("#autocomplete_results > .autocomplete-results-item").first().attr("href");
        if(href)
            window.location = href;
    })

    $(document).on("click", ".autocomplete-results-item", function(){
        build_autocomplete_results([]);
    })
})

function build_autocomplete_results(results){
    $("#autocomplete_results").remove();
    let $autocomplete_results = $("<div id='autocomplete_results' class='dynamic-border-radius-helper'></div>");

    results.forEach(function(item){
        $autocomplete_results.append("<a class='autocomplete-results-item link-dark text-decoration-none' href='/city/"+item.id+"'>"+item.name+"</a>")
    })

    if(!$autocomplete_results.is(":empty"))
        $("#autocomplete_container").append($autocomplete_results);
}
