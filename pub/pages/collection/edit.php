<div class="header">
    <h1>Edit Collection</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li><a href="/collection">Collections</a></li>
        <li><a href="/collection/<?php echo htmlentities($recCollection->id()); ?>"><?php echo htmlentities($recCollection->name()); ?></a></li>
        <li>Edit</li>
    </ul>
</nav>

<div class="content">
    <form method="post" action="" id="frm" class="form-group full-form">
        <input type="hidden" id="collection.id" name="collection.id" value="<?php echo htmlentities($recCollection->id()); ?>" />
        <div class="group-one">
            <div class="input-group">
                <label for="collection.name" class="form-control">Name</label>
                <input type="text" id="collection.name" name="collection.name" class="form-control" required="required" value="<?php echo htmlentities($recCollection->name()); ?>" />
            </div>
        </div>

        <div class="group-two">
            <div class="input-group">
                <label class="form-control">Movies</label>
                <div class="movies">
                    <?php
                    foreach ($movies as $movie) {
                    ?>
                        <div class="movie">
                            <input type="checkbox" id="collection.movie.<?php echo htmlentities($movie->id()); ?>" name="collection.movie[]" value="<?php echo htmlentities($movie->id()); ?>" <?php echo (isChecked($recCollection->movies(), $movie->id())) ? "checked='checked'" : "" ?> />
                            <label for="collection.movie.<?php echo htmlentities($movie->id()); ?>"><?php echo htmlentities($movie->title()); ?> [<span data-dateonlyformatter><?php echo htmlentities($movie->release_date()); ?></span>]</label>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="button primary"><i class="fa-solid fa-save"></i>Save</button>
            <a href="/collection/<?php echo htmlentities($recCollection->id()); ?>" class="button secondary"><i class="fa-solid fa-ban"></i>Cancel</a>
            <a href="/collection/<?php echo htmlentities($recCollection->id()); ?>/delete" class="button remove"><i class="fa-solid fa-trash"></i>Delete?</a>
        </div>
    </form>
</div>