// XMLHttpRequest follows the Ajax pattern in the supplied course-search code.
var searchNumber = 0;

function loadScores() {
    if (document.getElementById('bulkScores') && !confirm('Searching refreshes the table. Save your changes first. Continue?')) { return; }
    var search = document.getElementById('search').value;
    var status = document.getElementById('searchStatus');
    var results = document.getElementById('reportResults');
    var xhr = new XMLHttpRequest();
    searchNumber++;
    var thisSearch = searchNumber;
    status.textContent = 'Loading scores...';
    results.innerHTML = '';
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && thisSearch == searchNumber) {
            if (xhr.status == 200) {
                // PHP escapes user data before returning the HTML table.
                results.innerHTML = xhr.responseText;
                status.textContent = 'Search complete. Scores loaded.';
            } else {
                status.textContent = 'Could not load scores. Check your login and try again.';
            }
        }
    };
    xhr.timeout = 15000;
    xhr.ontimeout = function () {
        if (thisSearch == searchNumber) {
            status.textContent = 'The request timed out. Please try again.';
        }
    };
    xhr.open('GET', 'createReport.php?search=' + encodeURIComponent(search) + '&edit=' + encodeURIComponent(document.getElementById('editMode').value), true);
    xhr.send();
}

window.onload = function () {
    var searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.onsubmit = function (event) {
            event.preventDefault();
            loadScores();
        };
        loadScores();
    }
    var registration = document.getElementById('registrationForm');
    if (registration) {
        registration.onsubmit = function (event) {
            var password = document.getElementById('passwordexample').value;
            var confirmation = document.getElementById('confirmation').value;
            if (password.length < 12 || password != confirmation) {
                event.preventDefault();
                document.getElementById('formError').textContent = 'Passwords must match and contain at least 12 characters.';
            }
        };
    }
};

// Send only edited cells, avoiding PHP's limit on large numbers of form fields.
function prepareScores(form) {
    var changes = [];
    form.querySelectorAll('input[data-user]').forEach(function (input) {
        var auto = input.parentNode.querySelector('input[type="checkbox"]').checked ? 'yes' : 'no';
        if (input.value !== input.dataset.original || auto !== input.dataset.auto) {
            changes.push({username: input.dataset.user, chapter: input.dataset.chapter,
                score: input.value, autofail: auto, original: input.dataset.original,
                originalAuto: input.dataset.auto});
        }
    });
    if (!changes.length) { alert('No scores have changed.'); return false; }
    form.querySelector('#scoreChanges').value = JSON.stringify(changes);
    return true;
}
