<?php
namespace PHPMaker2021\inplaze;
if (IsLoggedIn()) {
?>
<script>
	$("[data-inplaze-image=true]").each(function(){
		$(this).css("cursor","pointer").click(function(e) {
			e.stopPropagation();
			window.location.href = "imagelist.php?cmd=search&x_Name=" + $(this).attr("id")
		}).parent().attr("href", "javascript:void(0)");
	});
	$("[data-inplaze-text=true]").each(function(){
		$(this).css("cursor","pointer").click(function(e) {
			e.stopPropagation();
			window.location.href = "textlist.php?cmd=search&x_Name=" + $(this).attr("id")
		});
	});
</script>
<?php } ?>
