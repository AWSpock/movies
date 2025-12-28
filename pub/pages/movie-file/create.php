<div class="header">
    <h1>Add Movie File</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li><a href="/movie-file">Movie Files</a></li>
        <li>Add Movie File</li>
    </ul>
</nav>

<div class="content">
    <form method="post" action="" id="frm" class="form-group full-form">
        <div class="group-one">
            <div class="input-group">
                <label for="movie_file.title" class="form-control">Title</label>
                <input type="text" id="movie_file.title" name="movie_file.title" class="form-control" required="required" value="<?php echo htmlentities($recMovie_File->title()); ?>" />
            </div>
        </div>

        <div class="group-two">
            <div class="input-group">
                <label class="form-control">Movies</label>
                <div class="movies">
                    <?php
                    /*foreach ($movies as $movie) {
                    ?>
                        <div class="movie">
                            <input type="checkbox" id="movie_file.movie.<?php echo htmlentities($movie->id()); ?>" name="movie_file.movie[]" value="<?php echo htmlentities($movie->id()); ?>" <?php echo (isMovieChecked($recMovie_File->movies(), $movie->id())) ? "checked='checked'" : "" ?> />
                            <label for="movie_file.movie.<?php echo htmlentities($movie->id()); ?>"><?php echo htmlentities($movie->title()); ?> [<span data-dateonlyformatter><?php echo htmlentities($movie->release_date()); ?></span>]</label>
                        </div>
                    <?php
                    }*/
                    ?>
                </div>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="button primary"><i class="fa-solid fa-save"></i>Save</button>
            <a href="/movie-file" class="button secondary"><i class="fa-solid fa-ban"></i>Cancel</a>
        </div>
    </form>
</div>