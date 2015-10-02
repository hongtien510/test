<h2>Test recognition</h2>
<form method="post" enctype="multipart/form-data" action="<?php echo $this->Url->build(["controller" => "Ads","action" => "recognize","_ext"=>"json"]); ?>">
    <input type="file" name="audio" />
    <input type="submit" value="Submit" />
</form>