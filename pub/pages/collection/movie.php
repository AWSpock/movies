<div class="header">
    <h1>Collection Info</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li><a href="/collection">Collections</a></li>
        <li><?php echo htmlentities($recCollection->name()); ?></li>
    </ul>
</nav>

<div class="content">
    <div class="row">
        <div class="options">
            <a href="/collection/<?php echo htmlentities($collection_id); ?>/edit" class="button secondary"><i class="fa-solid fa-pencil"></i>Edit Collection</a>
        </div>
    </div>

    <div class="row">
        <h2><?php echo htmlentities($recCollection->name()); ?></h2>
        <p>Record Count: <span id="data-table-count">?</span></p>
        <div class="movies" id="movies"></div>
    </div>
</div>


<template id="template">
    <a class="movie-info" href="/movie/MOVIE_ID" target="_blank">
        <div class="poster">
            <img src="/api/movie/MOVIE_ID/poster" data-id="poster" loading="lazy" />
        </div>
        <div class="info">
            <div class="title">
                <h2 data-id="title">TITLE</h2>
            </div>
            <div class="release_date">
                <span>Release Date: </span><span data-dateonlyformatter data-id="release_date">RELEASE_DATE</span>
            </div>
            <!-- <div class="file_name">
                <span>File Name: </span><span data-id="file_name">FILE_NAME</span>
            </div> -->
        </div>
    </a>
</template>