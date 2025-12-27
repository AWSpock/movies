ready(loadData);

async function loadData() {
  var table = document.getElementById("data-table");

  try {
    loader.show(true);

    var response = await fetch("/api/movie-map", {
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

      var create_froms = clone.querySelectorAll('[data-id="create_from"] a');
      create_froms.forEach(function (el) {
        // console.log(el);
        var link = el.getAttribute("href");
        el.setAttribute("href", link.replace("MOVIE_ID", i.id));
      });

      clone.querySelector(
        '[data-id="title"] .data-table-cell-content'
      ).textContent = i.title;
      clone.querySelector(
        '[data-id="year"] .data-table-cell-content'
      ).textContent = i.year;
      clone.querySelector(
        '[data-id="created"] .data-table-cell-content'
      ).textContent = i.created;
      clone.querySelector(
        '[data-id="updated"] .data-table-cell-content'
      ).textContent = i.updated;

      table.appendChild(clone);

      x++;
    });

    convertAllFields();
  } catch (error) {
    console.error(error);
    table.innerHTML = "<div class='alert alert-danger'>" + error + "</div>";
  }
}
