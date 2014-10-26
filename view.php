<div class="wrap">
    <h2><?php echo $title; ?></h2>
</div><!--.wrap-->

<a href="#" class="duplicate">Add</a> <br/>
<form id="shortener-form" method="POST">
    <div id="" style="float:left;margin-bottom: 10px;width:100%;">
        <div class="links-filter-container" style="margin-top:5px">
            <div class="filtered-link-container">
                <input type="text" class="filter-link" id="filtered-link0" placeholder="link"><span style="color:red" class="error">Error</span>
            </div>
        </div>
        <input type="button" class="button process" value="Process" />
    </div>
</form>