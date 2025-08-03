<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8" />
    <title>Foci meccs kereső</title>
</head>
<body>
    <h1>Csapat kereső</h1>
    <input type="text" id="searchInput" placeholder="Írj be egy csapatot..." />
    <ul id="results"></ul>

    <script>
        const input = document.getElementById('searchInput');
        const results = document.getElementById('results');

        input.addEventListener('input', () => {
            const query = input.value.trim();
            if (query.length < 3) {
                results.innerHTML = '';
                return;
            }
            fetch(`search.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    results.innerHTML = '';
                    if(data.error){
                        results.innerHTML = `<li>${data.error}</li>`;
                        return;
                    }
                    if (data.length === 0) {
                        results.innerHTML = '<li>Nincs találat</li>';
                        return;
                    }
                    data.forEach(match => {
                        const li = document.createElement('li');
                        li.textContent = `${match.home_team} vs ${match.away_team} (Szorzó: ${match.odds})`;
                        results.appendChild(li);
                    });
                })
                .catch(() => {
                    results.innerHTML = '<li>Hiba történt a keresés során.</li>';
                });
        });
    </script>
</body>
</html>
