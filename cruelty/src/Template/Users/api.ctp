<h2>API</h2>

<p>I encourage players to write bots that play this game for them. There is a very simple API to use, via GET requests.</p>

<?php if (!empty($apiKey)): ?>
<p><?= $this->Form->input('apiKey', [
        'type' => 'textbox',
        'label' => 'Your API key',
        'value' => $apiKey
    ]) ?></p>
<?php endif; ?>

<h3>Submitting a play</h3>
<p>Make a GET request to the following URL with the query values <b>c</b> and <b>apiKey</b> set appropriately:</p>
<pre>http://cruelty.brandonfoltz.com/games/botPlay?c=1&apiKey=someverylongstringwithlettersandnumbers1111</pre>
<p><b>c</b> should be set to <b>0</b> or <b>1</b>, corresponding to NOT checking the box and CHECKING the box respectively. <b>apiKey</b> should be your API key value. 
A JSON response will be returned in the following format:</p>
<pre>{
    "content": {
        "success": 1
    }
}</pre>

