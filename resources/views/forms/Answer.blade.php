<script src="{{ URL::asset('js/addAnswer.js') }}" language="Javascript" type="text/javascript"></script>
<form method="POST" name="Answers">
    <div id="dynamicInput">
        Answer 1 Is Right<br><input type="text" name="answers[]">
        <input type="checkbox" name="is_true">
    </div>
    <input type="button" value="Add another text input" onClick="addAnswer('dynamicInput');">
</form>
