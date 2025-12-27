<div class="header">
    <h1>Edit Movie</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li><a href="/movie/<?php echo htmlentities($recMovie->id()); ?>/summary"><?php echo htmlentities($recMovie->title()); ?></a></li>
        <li>Edit</li>
    </ul>
</nav>

<div class="content">
    <form method="post" action="" id="frm" class="form-group full-form">
        <input type="hidden" id="movie.id" name="movie.id" value="<?php echo htmlentities($recMovie->id()); ?>" />
        <div class="group-one">
            <div class="input-group">
                <label for="movie.order_title" class="form-control">Order Title</label>
                <input type="text" id="movie.order_title" name="movie.order_title" class="form-control" required="required" value="<?php echo htmlentities($recMovie->order_title()); ?>" />
            </div>
        </div>

        <div class="group-two">
            <div class="input-group">
                <label class="form-control">Collections</label>
                <div class="collections">
                    <?php
                    foreach ($collections as $collection) {
                    ?>
                        <div class="collection">
                            <input type="checkbox" id="movie.collection.<?php echo htmlentities($collection->id()); ?>" name="movie.collection[]" value="<?php echo htmlentities($collection->id()); ?>" <?php echo (isCollectionChecked($recMovie->collections(), $collection->id())) ? "checked='checked'" : "" ?> />
                            <label for="movie.collection.<?php echo htmlentities($collection->id()); ?>"><?php echo htmlentities($collection->name()); ?></label>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="group-three">
            <div class="input-group">
                <label class="form-control">Genres</label>
                <div class="genres">
                    <?php
                    foreach ($genres as $genre) {
                    ?>
                        <div class="genre">
                            <input type="checkbox" id="movie.genre.<?php echo htmlentities($genre->id()); ?>" name="movie.genre[]" value="<?php echo htmlentities($genre->id()); ?>" <?php echo (isGenreChecked($recMovie->genres(), $genre->id())) ? "checked='checked'" : "" ?> />
                            <label for="movie.genre.<?php echo htmlentities($genre->id()); ?>"><?php echo htmlentities($genre->name()); ?></label>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="button primary"><i class="fa-solid fa-save"></i>Save</button>
            <a href="/movie/<?php echo htmlentities($recMovie->id()); ?>/summary" class="button secondary"><i class="fa-solid fa-ban"></i>Cancel</a>
            <a href="/movie/<?php echo htmlentities($recMovie->id()); ?>/delete" class="button remove"><i class="fa-solid fa-trash"></i>Delete?</a>
        </div>
    </form>
</div>