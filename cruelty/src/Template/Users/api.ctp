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
        "success": true
    }
}</pre>
<h3>Getting game history</h3>
<p>Make a GET request to the following URL, noting that the <b>apiKey</b> query parameter is optional:
<pre>http://cruelty.brandonfoltz.com/games/history?apiKey=someverylongstringwithlettersandnumbers1111</pre>
You will receive a JSON response similar to this example:
<pre>
{
    "content": [
        {
            "id": 35,
            "start_time": "2017-10-19T02:44:17+00:00",
            "end_time": "2017-10-20T03:44:17+00:00",
            "total_plays": 1,
            "total_checked": "???",
            "ratio": "???",
            "complete": false,
            "you_checked_box": true
        },
        {
            "id": 34,
            "start_time": "2017-10-19T02:44:08+00:00",
            "end_time": "2017-10-20T02:44:08+00:00",
            "total_plays": 13,
            "total_checked": 3,
            "ratio": 0.23,
            "complete": true,
            "you_checked_box": false
        },
        ...
    ]
}
</pre>
The response will include the 100 most recent games data. The first entry is the current game in progress (with <b>total_checked</b> and <b>ratio</b> removed).
If you do not supply an <b>apiKey</b> or if you did not participate in a particular game, the <b>you_checked_box</b> field will be omitted on the relevant element.</p>

