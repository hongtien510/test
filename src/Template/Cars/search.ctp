<h2>Test search</h2>
<form method="post" enctype="multipart/form-data" action="<?php echo $this->Url->build(["controller" => "Cars","action" => "search","_ext"=>"json"]); ?>">
    <input type="text" name="term" />
    <input type="submit" value="Submit" />
</form>