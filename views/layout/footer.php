
<footer>



</footer>

<script>

    async function getNewImages(query, longitud) {
        let requestUrl = "https://api.unsplash.com/search/photos?query=" + query + "&client_id=uIvacdZyO4OEOUMdgEWRi5rlB-OLx6OKOHIfeRBH2KU";
        return fetch(requestUrl)
            .then((response) => response.json())
            .then((data) => {
                return data.results.sort(() => .5 - Math.random()).slice(0, longitud);
            });
    }

    function getImages(imagenes, query) {
        getNewImages(query, imagenes.length).then((images) => {
            for (let i = 0; i < images.length; i++) {
                imagenes[i].src = images[i].urls.raw + "&fit=crop&w=400&h=300";
            }
        });
    }

    window.onload = function() {
        const cursosImg = document.querySelectorAll('.curso-img');
        const talleresImg = document.querySelectorAll('.taller-img');

        getImages(cursosImg, "chef");
        getImages(talleresImg, "food");
    }


</script>

</body>
</html>