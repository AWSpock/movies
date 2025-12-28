<div class="header">
    <h1>Edit Movie File</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li><a href="/movie-file">Movie Files</a></li>
        <li>Edit <?php echo htmlentities($recMovie_File->file_name()); ?></li>
    </ul>
</nav>

<div class="content">
    <form method="post" action="" id="frm" class="form-group full-form">
        <input type="hidden" id="movie_file.id" name="movie_file.id" value="<?php echo htmlentities($recMovie_File->id()); ?>" />
        <div class="group-one">
            <div class="input-group">
                <label for="movie_file.file_name" class="form-control">File Name</label>
                <input type="text" id="movie_file.file_name" name="movie_file.file_name" class="form-control" required="required" value="<?php echo htmlentities($recMovie_File->file_name()); ?>" />
            </div>
            <div class="input-group">
                <label for="movie_file.movie_id" class="form-control">Movie</label>
                <select id="movie_file.movie_id" name="movie_file.movie_id" class="form-control">
                    <option value="">Select a Movie</option>
                    <?php
                    foreach ($movies as $movie) {
                    ?>
                        <option value="<?php echo htmlentities($movie->id()); ?>" <?php echo $recMovie_File->movie_id() == $movie->id() ? "selected='selected' " : ""; ?>><?php echo htmlentities($movie->title()); ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="input-group">
                <label for="movie_file.from_disk" class="form-control">From Disk</label>
                <select id="movie_file.from_disk" name="movie_file.from_disk" class="form-control" required="required">
                    <option value="">Select a Status</option>
                    <option value="1" <?php echo $recMovie_File->from_disk() ? "selected='selected' " : "" ?>>Yes</option>
                    <option value="0" <?php echo !$recMovie_File->from_disk() ? "selected='selected' " : "" ?>>No</option>
                </select>
            </div>
            <div class="input-group">
                <label for="movie_file.bluray" class="form-control">Bluray</label>
                <select id="movie_file.bluray" name="movie_file.bluray" class="form-control" required="required">
                    <option value="">Select a Status</option>
                    <option value="1" <?php echo $recMovie_File->bluray() ? "selected='selected' " : "" ?>>Yes</option>
                    <option value="0" <?php echo !$recMovie_File->bluray() ? "selected='selected' " : "" ?>>No</option>
                </select>
            </div>
            <div class="input-group">
                <label for="movie_file.quality" class="form-control">Quality</label>
                <select id="movie_file.quality" name="movie_file.quality" class="form-control" required="required">
                    <option value="Unchecked">Unchecked</option>
                    <option value="Good" <?php echo $recMovie_File->quality() === "Good" ? "selected='selected' " : "" ?>>Good</option>
                    <option value="Bad" <?php echo $recMovie_File->quality() === "Bad" ? "selected='selected' " : "" ?>>Bad</option>
                </select>
            </div>
            <div class="input-group">
                <label for="movie_file.quality_notes" class="form-control">Quality Notes</label>
                <textarea id="movie_file.quality_notes" name="movie_file.quality_notes" class="form-control"><?php echo htmlentities($recMovie_File->quality_notes()); ?></textarea>
            </div>
        </div>

        <div class="group-two">
            <div class="input-group">
                <label for="movie_file.file_size" class="form-control">File Size</label>
                <input type="text" id="movie_file.file_size" name="movie_file.file_size" class="form-control" readonly="readonly" disabled="disabled" value="<?php echo readableBytes($recMovie_File->file_size()); ?>" />
            </div>
            <div class="input-group">
                <label for="movie_file.title" class="form-control">Title</label>
                <input type="text" id="movie_file.title" name="movie_file.title" class="form-control" readonly="readonly" disabled="disabled" value="<?php echo htmlentities($recMovie_File->title()); ?>" />
            </div>
            <div class="input-group">
                <label for="movie_file.year" class="form-control">Year</label>
                <input type="text" id="movie_file.year" name="movie_file.year" class="form-control" readonly="readonly" disabled="disabled" value="<?php echo htmlentities($recMovie_File->year()); ?>" />
            </div>
            <div class="input-group">
                <label for="movie_file.notes" class="form-control">Notes</label>
                <input type="text" id="movie_file.notes" name="movie_file.notes" class="form-control" readonly="readonly" disabled="disabled" value="<?php echo htmlentities($recMovie_File->notes()); ?>" />
            </div>
            <div class="input-group">
                <label for="movie_file.file_type" class="form-control">File Type</label>
                <input type="text" id="movie_file.file_type" name="movie_file.file_type" class="form-control" readonly="readonly" disabled="disabled" value="<?php echo htmlentities($recMovie_File->file_type()); ?>" />
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="button primary"><i class="fa-solid fa-save"></i>Save</button>
            <a href="/movie-file" class="button secondary"><i class="fa-solid fa-ban"></i>Cancel</a>
            <a href="/movie-file/<?php echo htmlentities($recMovie_File->id()); ?>/delete" class="button remove"><i class="fa-solid fa-trash"></i>Delete?</a>
        </div>

        <div class="dates">
            <div class="input-group">
                <label class="form-control">Create</label>
                <div><samp data-dateformatter><?php echo htmlentities($recMovie_File->created()); ?></samp></div>
            </div>
            <div class="input-group">
                <label class="form-control">Updated</label>
                <div><samp data-dateformatter><?php echo htmlentities($recMovie_File->updated()); ?></samp></div>
            </div>
        </div>
    </form>
</div>