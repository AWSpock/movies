ready(loadData);

async function loadData() {
  var table = document.getElementById("data-table");

  try {
    loader.show(true);

    var response = await fetch("/api/movie-file", {
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

      var row = clone.querySelector(".data-table-row");
      var edit_link = row.getAttribute("href");
      row.setAttribute("href", edit_link.replace("MOVIE_FILE_ID", i.id));

      clone.querySelector(
        '[data-id="file_name"] .data-table-cell-content'
      ).textContent = i.file_name;
      clone.querySelector(
        '[data-id="movie"] .data-table-cell-content'
      ).textContent = i.movie_id;
      clone.querySelector(
        '[data-id="quality"] .data-table-cell-content'
      ).textContent = i.quality;
      clone.querySelector(
        '[data-id="from_disk"] .data-table-cell-content'
      ).textContent = i.from_disk ? "Yes" : "No";
      clone.querySelector(
        '[data-id="bluray"] .data-table-cell-content'
      ).textContent = i.bluray ? "Yes" : "No";

      table.appendChild(clone);

      x++;
    });

    convertAllFields();
  } catch (error) {
    console.error(error);
    table.innerHTML = "<div class='alert alert-danger'>" + error + "</div>";
  }
}
