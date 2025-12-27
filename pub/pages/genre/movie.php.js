ready(loadData);

async function loadData() {
  var table = document.getElementById("movies");

  try {
    loader.show(true);

    var response = await fetch("/api/genre/" + genre_id + "/movies", {
      method: "GET",
    });
    // console.log(response);

    if (!response.ok) {
      throw new Error(`${response.statusText}`);
    }

    var data = await response.json();
    // console.log(data);

    loader.hide();

    var template = document.getElementById("template");
    // console.log(template);

    document.getElementById("data-table-count").textContent =
      data.length.toLocaleString("en-US");

    var x = 0;
    data.forEach(function (i) {
      var clone = template.content.cloneNode(true);

      var row = clone.querySelector(".movie-info");
      var edit_link = row.getAttribute("href");
      row.setAttribute("href", edit_link.replace("MOVIE_ID", i.id));

      var img = row.querySelector("[data-id='poster']");
      var img_link = img.getAttribute("src");
      img.setAttribute("src", img_link.replace("MOVIE_ID", i.id));
      clone.querySelector('[data-id="title"]').textContent = i.title;
      clone.querySelector('[data-id="release_date"]').textContent =
        i.release_date;
      // clone.querySelector('[data-id="file_name"]').textContent = i.file_name;

      table.appendChild(clone);

      x++;
    });

    convertAllFields();
  } catch (error) {
    console.error(error);
    table.innerHTML = "<div class='alert alert-danger'>" + error + "</div>";
  }
}
