<script src="/wp-includes/js/addInput.js" language="Javascript" type="text/javascript"></script>
<form method="POST">
    <div id="dynamicInput">
        Entry 1<br><input type="text" name="myInputs[]">
    </div>
    <input type="button" value="Add another text input" onClick="addInput('dynamicInput');">
</form>
