<input type="text" name="gaya" id="gaya3" /> <input type="button" onclick="openPop();" value="Å×½ºÆ®" />

<input type="checkbox" value="Y" onclick="mychk(this);" />

<iframe src="./gaya2.php" ></iframe>
<script>
	function openPop()
	{
		window.open("./gaya2.php", 600, 300);
	}	

	function mychk(chk)
	{
		console.log(chk);
	}
</script>