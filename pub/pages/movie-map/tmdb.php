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
    if ($details !== null) {
    ?>
        <div class="row">
            <h2>Result</h2>

            <pre><?php echo $movie->toString(true); ?></pre>
            <pre><?php echo json_encode($details, JSON_PRETTY_PRINT); ?></pre>
        </div>
    <?php
    } else {
    ?>
        <div class="row">
            <h2>Search Results</h2>

            <form method="post" action="" id="frm">
                <div class="search-results">
                    <?php
                    foreach ($search as $res) {
                    ?>
                        <label class="search-result" for="<?php echo htmlentities($res->id); ?>">
                            <input type="radio" name="selection" value="<?php echo htmlentities($res->id); ?>" id="<?php echo htmlentities($res->id); ?>">
                            <img src="<?php echo htmlentities((property_exists($res, "poster_path") && $res->poster_path !== null) ? "https://image.tmdb.org/t/p/w200/" . $res->poster_path : ""); ?>" loading="lazy" />
                            <div class="info">
                                <div class="title">
                                    <div class="label">Title</div>
                                    <div><samp><?php echo htmlentities($res->title); ?></samp></div>
                                </div>
                                <div class="date">
                                    <div class="label">Release Date</div>
                                    <div><samp><?php echo htmlentities($res->release_date); ?></samp></div>
                                </div>
                                <div class="overview">
                                    <div class="label">Overview</div>
                                    <div><samp><?php echo htmlentities($res->overview); ?></samp></div>
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