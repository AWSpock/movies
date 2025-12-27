<div class="header">
    <h1>Delete Collection</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li><a href="/collection">Collections</a></li>
        <li><a href="/collection/<?php echo htmlentities($recCollection->id()); ?>"><?php echo htmlentities($recCollection->name()); ?></a></li>
        <li><a href="/collection/<?php echo htmlentities($recCollection->id()); ?>/edit">Edit</a></li>
        <li>Delete</li>
    </ul>
</nav>

<div class="content">
    <form method="post" action="" id="frm" class="form-group main-form">
        <input type="hidden" id="warranty.id" name="warranty.id" value="<?php echo htmlentities($recCollection->id()); ?>" />
        <p>Are you sure you wish to delete this Collection?</p>
        <div class="input-group">
            <label class="form-control">Name</label>
            <div><samp><?php echo htmlentities($recCollection->name()); ?></samp></div>
        </div>
        <div class="input-group">
            <label class="form-control">Movies</label>
            <div><samp data-numberformatter><?php echo htmlentities(count($recCollection->movies())); ?></samp></div>
        </div>
        <div class="button-group">
            <button type="submit" class="button remove"><i class="fa-solid fa-trash"></i>Confirm Delete</button>
            <a href="/collection/<?php echo htmlentities($recCollection->id()); ?>/edit" class="button secondary"><i class="fa-solid fa-ban"></i>Cancel</a>
        </div>
    </form>
</div>