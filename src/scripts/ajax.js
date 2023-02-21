
function buildDataContainers(data, idContainer, elementClass) {
    let dataDivs = $("#" + idContainer);
    $.each(data, function (index, element) {
        let elementDiv = $("<div class='card p-3'></div>");
        let elementImg = $("<img src='' class='card-img-top' alt=''>");
        elementImg.addClass(elementClass + "-img");
        elementImg.attr("alt", elementClass + "-img");
        let elementBody = $("<div class='card-body'></div>");
        let elementTitle = $("<h5 class='card-title'></h5>");
        let elementText = $("<p class='card-text'></p>");
        let elementList = $("<ul class='list-group list-group-flush'></ul>");
        let elementItem1 = $("<li class='list-group-item'></li>");
        let elementItem2 = $("<li class='list-group-item'></li>");
        let elementBody2 = $("<div class='card-body'></div>");
        let elementButton = $("<a href='#' class='btn btn-primary'>Inscr&iacute;bete</a>");

        elementTitle.text(element.nombre);
        elementText.text(element.descripcion);
        elementItem1.text("Duración: " + element.horas + " horas");
        elementItem2.text("Ponente: " + element.ponente);
        elementButton.attr("href", "http://localhost/ProyectoCursos/public/" + elementClass + "/" + element.id);
        elementBody2.append(elementButton);
        elementList.append(elementItem1);
        elementList.append(elementItem2);
        elementDiv.append(elementImg);
        elementBody.append(elementTitle);
        elementBody.append(elementText);
        elementDiv.append(elementBody);
        elementDiv.append(elementList);
        elementDiv.append(elementBody2);
        dataDivs.append(elementDiv);
    });
}

async function getNewImages(query, longitud) {
    let requestUrl = "https://api.unsplash.com/search/photos?query=" + query + "&client_id=uIvacdZyO4OEOUMdgEWRi5rlB-OLx6OKOHIfeRBH2KU";
    return fetch(requestUrl)
        .then((response) => response.json())
        .then((data) => {
            return data.results.sort(() => .5 - Math.random()).slice(0, longitud);
        });
}


function getImages(container, query) {
    let imagenes = document.querySelectorAll('.' + container);
    getNewImages(query, imagenes.length).then((images) => {
        for (let i = 0; i < images.length; i++) {
            imagenes[i].src = images[i].urls.raw + "&fit=crop&w=400&h=300";
        }
    });
}

function getData(url, container, idContainer, elementClass, query) {
    $.ajax({
        url: url,
        type: 'GET',
        accepts: "application/json",
        dataType: "json",
        success: function (result) {
            console.log(result);
            buildDataContainers(result, idContainer, elementClass);
            getImages(container, query);
        },
        error: function (e) {
            console.log("ERROR: " + e.message);
        }
    })
}

function completarImagenes(source, container, idContainer, elementClass, query) {
    getData(source, container, idContainer, elementClass, query);
}

function construirPonentes(ponentes) {
    let carousel = $(".carousel-inner");
    let indicators = $(".carousel-indicators");
    $.each(ponentes, function (index, ponente) {
        let item = $("<div class='carousel-item'></div>");
        let img = $("<img src='' class='d-block w-100' alt='ponente-img'>");
        let div = $("<div class='carousel-caption d-none d-md-block'></div>");
        let h5 = $("<h5>Conoce a </h5>");
        let p = $("<p></p>");
        p.text(ponente.tags);
        h5.text("Conoce a " + ponente.nombre + " " + ponente.apellidos);
        div.append(h5, p);
        img.attr("src", "../src/media/" +ponente.imagen);
        item.append(img, div);
        index += 1;
        let indicator = $("<button type='button' data-bs-target='#carousel' data-bs-slide-to=" + index +" aria-label='Slide '" + index + "></button>");
        indicators.append(indicator);
        carousel.append(item);
    });
    getImages("ponente-img", "chef");
}

function completarPonentes() {
    $.ajax({
        url: "http://localhost/ProyectoCursos/public/ponentes",
        type: 'GET',
        accepts: "application/json",
        dataType: "json",
        success: function (result) {
            console.log(result);
            construirPonentes(result);
        },
        error: function (e) {
            console.log(e)
            console.log("ERROR: " + e.message);
        }
    })
}

const urlCursos = "http://localhost/ProyectoCursos/public/cursos";
const urlTalleres = "http://localhost/ProyectoCursos/public/talleres";

completarImagenes(urlCursos, "curso-img", "cursos-cards", "curso", "chef");
completarImagenes(urlTalleres, "taller-img", "talleres-cards", "taller", "food");
completarPonentes();