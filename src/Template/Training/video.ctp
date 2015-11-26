<H3 class="page-title"><?php if (isset($_GET["title"])) { echo $_GET["title"];} else { Echo "No video"; } ?></H3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?= $this->request->webroot  ?>">Dashboard</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <LI>
            <a href="<?=$this->request->webroot;?>profiles/training">Training</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="">Video Player</a>
        </li>
    </ul>
    <a href="javascript:window.close();" class="floatright btn btn-primary">Back</a>
</div>
<?php if (isset($_GET["url"])){ ?>
<P>Please be patient, it may take a few minutes to load.</P>
<div align="center"><video width="100%" height="100%" controls="controls">
    <source src="<?php echo $_GET["url"]; ?>" type="video/mp4">
    Your browser does not support the video tag.
</video></div>
<?php } else {
    echo "No video";
}?>