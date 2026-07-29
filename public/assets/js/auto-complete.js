document.addEventListener("DOMContentLoaded", function () {
    var searchInput = document.getElementById("search-input");
    var suggestionsContainer = document.getElementById("suggestions-container");

    // Dummy data for suggestions
    var suggestions = ["ui ux", "landing page", "websites", "design"];

    // Event listener for mouse down
    searchInput.addEventListener("focus", function () {
        var searchTerm = searchInput.value.toLowerCase();
        showSuggestions(filterSuggestions(suggestions, searchTerm));
    });

    // Event listener for input changes
    searchInput.addEventListener("input", function () {
        var searchTerm = searchInput.value.toLowerCase();
        showSuggestions(filterSuggestions(suggestions, searchTerm));
    });

    // Event listener for suggestion click
    suggestionsContainer.addEventListener("click", function (event) {
        if (event.target.classList.contains("suggestion")) {
            searchInput.value = event.target.innerText;
            hideSuggestions();
        }
    });

    function filterSuggestions(suggestions, term) {
        return suggestions.filter(function (suggestion) {
            return suggestion.toLowerCase().includes(term);
        });
    }

    function showSuggestions(filteredSuggestions) {
        suggestionsContainer.innerHTML = "";

        if (filteredSuggestions.length > 0) {
            filteredSuggestions.forEach(function (suggestion) {
                var suggestionElement = document.createElement("div");
                suggestionElement.classList.add("suggestion");
                suggestionElement.innerText = suggestion;
                suggestionsContainer.appendChild(suggestionElement);
            });

            suggestionsContainer.style.display = "block";
        } else {
            hideSuggestions();
        }
    }

    function hideSuggestions() {
        suggestionsContainer.style.display = "none";
    }
});