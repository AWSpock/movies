<div class="header">
    <h1>Movie Info</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li><a href="/movie-map">Map</a></li>
        <li><?php echo htmlentities($recMovie_File->title()); ?></li>
    </ul>
</nav>

<div class="content">
    <div class="row">
        <div class="input-group">
            <label class="form-control">Title</label>
            <div><samp><?php echo htmlentities($recMovie_File->title()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">Year</label>
            <div><samp><?php echo htmlentities($recMovie_File->year()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">File</label>
            <div><samp><?php echo htmlentities($recMovie_File->file_name()); ?></samp></div>
        </div>
    </div>

    <?php
    if ($recMovie_File->movie_id() > 0) {
    ?>
        <div class="row">
            <h2>Result</h2>

            <pre><?php echo $recMovie_File->toString(true); ?></pre>
        </div>
    <?php
    } else {
    ?>
        <div class="row">
            <h2>Search Results</h2>

            <form method="post" action="" id="frm">
                <div class="movies">
                    <?php
                    foreach ($search as $res) {
                    ?>
                        <label class="movie-info" for="<?php echo htmlentities($res->id()); ?>">
                            <input type="radio" name="selection" value="<?php echo htmlentities($res->id()); ?>" id="<?php echo htmlentities($res->id()); ?>">
                            <div class="poster">
                                <img src="/api/movie/<?php echo htmlentities($res->id()); ?>/poster" loading="lazy" />
                            </div>
                            <div class="info">
                                <div class="title">
                                    <h2><?php echo htmlentities($res->title()); ?>
                                </div>
                                <div class="release_date">
                                    <span>Release Date: </span><span data-dateonlyformatter><?php echo htmlentities($res->release_date()); ?></span>
                                </div>
                                <div class="overview">
                                    <div class="label">Overview:</div>
                                    <div><samp><?php echo htmlentities($res->overview()); ?></samp></div>
                                </div>
                            </div>
                        </label>
                    <?php
                    }
                    ?>
                </div>
                <div class="button-group">
                    <button type="submit" class="button primary"><i class="fa-solid fa-save"></i>Save</button>
                </div>
            </form>
        </div>
    <?php
    }
    ?>
</div>