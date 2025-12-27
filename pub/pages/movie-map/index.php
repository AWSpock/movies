<div class="header">
    <h1>Movies</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li>Map</li>
    </ul>
</nav>

<div class="content">
    <div class="row">
        <p>Record Count: <span id="data-table-count">?</span></p>
        <div class="data-table" id="data-table">
            <div class="data-table-row header-row">
                <div class="data-table-cell header-cell" data-id="title">
                    <div class="data-table-cell-label">Title</div>
                </div>
                <div class="data-table-cell header-cell" data-id="year">
                    <div class="data-table-cell-label">Year</div>
                </div>
                <div class="data-table-cell header-cell" data-id="created">
                    <div class="data-table-cell-label">Created</div>
                </div>
                <div class="data-table-cell header-cell" data-id="updated">
                    <div class="data-table-cell-label">Updated</div>
                </div>
                <div class="data-table-cell header-cell" data-id="create_from">
                    <div class="data-table-cell-label">Create From</div>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="template">
    <div class="data-table-row">
        <div class="data-table-cell" data-id="title">
            <div class="data-table-cell-label">Title</div>
            <div class="data-table-cell-content"></div>
        </div>
        <div class="data-table-cell" data-id="year">
            <div class="data-table-cell-label">Year</div>
            <div class="data-table-cell-content"></div>
        </div>
        <div class="data-table-cell" data-id="created">
            <div class="data-table-cell-label">Create</div>
            <div class="data-table-cell-content" data-dateformatter></div>
        </div>
        <div class="data-table-cell" data-id="updated">
            <div class="data-table-cell-label">Updated</div>
            <div class="data-table-cell-content" data-dateformatter></div>
        </div>
        <div class="data-table-cell" data-id="create_from">
            <div class="data-table-cell-label">Create From</div>
            <a href="/movie-map/MOVIE_ID/tmdb">TMDB</a> |
            <a href="/movie-map/MOVIE_ID/movie">Movie</a>
        </div>
    </div>
</template>