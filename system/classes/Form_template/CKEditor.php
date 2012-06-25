$this->value = stripslashes($this->value);

include_once "system/3d-party/ckeditor/ckeditor_php5.php" ;

ob_start();

$CKEditor = new CKEditor();
$CKEditor->basePath = BASE_HREF."/system/3d-party/ckeditor/";

$CKEditor->config['height'] = 200;
$CKEditor->config['width'] = 800;
$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
$CKEditor->config['skin'] = 'v2';

//$CKEditor->config['toolbar'] = 'Basic';
$CKEditor->config['toolbar'] = array(
    array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
    array( 'Image', 'Link', 'Unlink', 'Anchor' )
    );
$CKEditor->editor($this->name, $this->value);

$t=ob_get_contents();
ob_clean ();

return ($t);
