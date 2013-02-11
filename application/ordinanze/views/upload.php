<?php  require dirname(__FILE__).'/includes/head_1.php'; ?>
<script type="text/JavaScript">
<!-- Begin
function TestFileType( fileName, fileTypes ) {

//alert(fileName);
if (!fileName) return;

dots = fileName.split(".")
//get the part AFTER the LAST period.
fileType = "." + dots[dots.length-1];

if (fileTypes.join(".").indexOf(fileType) == -1){
    alert("Possibile caricare file solo di tipo: \n\n" + (fileTypes.join(" .")) + "\n\nPer favore selezionare un altro file.");
    $('#file').val('');
    return false;
    }
    else return true;
}
// -->
</script>

<table>

<tr><th><?php echo $title.$errore ?></th><td>
<form method="post" action='<?php echo site_url()."/manage/upload/" ?>' enctype="multipart/form-data">
<?php if(isset($cod)) echo form_hidden("cod",$cod); ?>
<?php if(isset($id)) echo form_hidden("id",$id); ?>
            <input type="hidden" name="action" value="upload" />
            <input type="file" name="user_file" id="file" />
            <br />
            <input type="submit" value="Carica Ordinanza" onClick=" return TestFileType(this.form.user_file.value, ['.pdf']);" />
</form>
 </td></tr></table>
<table><tr><td><a href=<?php echo site_url().'/ordinanze'?>> Riepilogo Ordinanze</a>
</td></tr></table>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>
