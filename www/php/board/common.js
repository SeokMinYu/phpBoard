$(document).ready(function(){
	$(".dat_edit_bt").click(function(){
	/* dat_edit_bt클래스 클릭시 동작(댓글 수정) */
		var obj = $(this).closest(".dap_lo").find(".dat_edit");
		obj.dialog({
			modal:true,
			width:650,
			height:200,
			title:"댓글 수정"});
			close: function () {
				console.log("dialog_close");
				// location.reload();
				history.go(0);
			}
		});
		console.log("dialog_open");
	});

	$(".dat_delete_bt").click(function(){
	/* dat_delete_bt클래스 클릭시 동작(댓글 삭제) */
		var obj = $(this).closest(".dap_lo").find(".dat_delete");
		obj.dialog({
			modal:true,
			width:400,
			title:"댓글 삭제확인"});
			close: function () {
				console.log("dialog_close");
				// location.reload();
				history.go(0);
			}
		});
		console.log("dialog_open");
	});
});