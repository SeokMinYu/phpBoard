<input type="button" onclick="test();" value ="test" />

<input type="button" onclick="test2();" value ="test2" />

<script>
	function test()
	{
		opener.document.getElementById("gaya3").value = "test";
		window.close();

	}


	function test2()
	{
		parent.document.getElementById("gaya3").value = "test2";
		window.close();

	}
</script>