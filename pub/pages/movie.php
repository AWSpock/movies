<div class="header">
    <h1>Movie Info</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li><?php echo htmlentities($recMovie->title()); ?></li>
    </ul>
</nav>

<div class="content">
    <div class="movie-info">
        <div class="poster">
            <?php
            if ($recMovie->poster_path() !== null) {
            ?>
                <img src="/api/movie/<?php echo htmlentities($recMovie->id()); ?>/poster" loading="lazy" />
            <?php
            }
            ?>
        </div>
        <div class="info">
            <div class="title">
                <h2><?php echo htmlentities($recMovie->title()); ?></h2>
            </div>
            <div class="release_date">
                <span>Release Date: </span><span data-dateonlyformatter><?php echo htmlentities($recMovie->release_date()); ?></span>
            </div>
            <div class="overview">
                <span><?php echo htmlentities($recMovie->overview()); ?></span>
            </div>
            <div class="genres">
                <h3>Genres</h3>
                <ul>
                    <?php
                    foreach ($recMovie->genres() as $genre) {
                    ?>
                        <li><a href="/genre/<?php echo htmlentities($genre->id()); ?>" target="_blank"><?php echo htmlentities($genre->name()); ?></a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="file_name">
                <span>File(s): </span>
                <ul>
                    <?php
                    foreach ($recMovie->movie_files() as $movie_file) {
                    ?>
                        <li><span><?php echo htmlentities($movie_file->file_name()); ?></span></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>