<div class="header">
    <h1>Movies</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li>Collections</li>
    </ul>
</nav>

<div class="content">
    <div class="row">
        <div class="options">
            <?php
            if ($data->user_roles($userAuth->user()->id())->hasRole("manager")) {
            ?>
                <a href="/collection/create" class="button secondary"><i class="fa-solid fa-plus"></i>Create Collection</a>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="row">
        <p>Record Count: <span id="data-table-count">?</span></p>
        <div class="data-table" id="data-table">
            <div class="data-table-row header-row">
                <div class="data-table-cell header-cell" data-id="name">
                    <div class="data-table-cell-label">Name</div>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="template">
    <a href="/collection/COLLECTION_ID" class="data-table-row">
        <div class="data-table-cell" data-id="name">
            <div class="data-table-cell-label">Name</div>
            <div class="data-table-cell-content"></div>
        </div>
    </a>
</template>