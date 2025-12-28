<div class="header">
    <h1>Movies</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li>Movie Files</li>
    </ul>
</nav>

<div class="content">
    <div class="row">
        <div class="options">
            <!--<a href="/movie-file/create" class="button secondary"><i class="fa-solid fa-plus"></i>Create Movie File</a>-->
        </div>
    </div>

    <div class="row">
        <p>Record Count: <span id="data-table-count">?</span></p>
        <div class="data-table" id="data-table">
            <div class="data-table-row header-row">
                <div class="data-table-cell header-cell" data-id="file_name">
                    <div class="data-table-cell-label">File Name</div>
                </div>
                <div class="data-table-cell header-cell" data-id="movie">
                    <div class="data-table-cell-label">Movie</div>
                </div>
                <div class="data-table-cell header-cell" data-id="quality">
                    <div class="data-table-cell-label">Quality</div>
                </div>
                <div class="data-table-cell header-cell" data-id="from_disk">
                    <div class="data-table-cell-label">From Disk</div>
                </div>
                <div class="data-table-cell header-cell" data-id="bluray">
                    <div class="data-table-cell-label">Bluray</div>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="template">
    <a href="/movie-file/MOVIE_FILE_ID/edit" class="data-table-row">
        <div class="data-table-cell" data-id="file_name">
            <div class="data-table-cell-label">File Name</div>
            <div class="data-table-cell-content"></div>
        </div>
        <div class="data-table-cell" data-id="movie">
            <div class="data-table-cell-label">Movie</div>
            <div class="data-table-cell-content"></div>
        </div>
        <div class="data-table-cell" data-id="quality">
            <div class="data-table-cell-label">Quality</div>
            <div class="data-table-cell-content"></div>
        </div>
        <div class="data-table-cell" data-id="from_disk">
            <div class="data-table-cell-label">From Disk</div>
            <div class="data-table-cell-content"></div>
        </div>
        <div class="data-table-cell" data-id="bluray">
            <div class="data-table-cell-label">Bluray</div>
            <div class="data-table-cell-content"></div>
        </div>
    </a>
</template>