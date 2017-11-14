<h2>About</h2>
<p>This is a multi player web game inspired by an anecdote in the book "A Mathematician Plays The Stock Market" by John Allen Paulos. He called the story "My Pedagogical Cruelty" and it goes more or less as follows.</p>
<p>In a class that John taught at Temple University, he would put a checkbox at the bottom of a daily quiz with the following notes (paraphrased).</p>
<blockquote>Students who check the box will receive ten extra points on their quiz. However if more than half the class checks the box, those students who checked it will lose ten points.</blockquote>
<p>This game is essentially the same, I've simply done away with the pedagogy and kept only the cruelty.</p>
<p>If you have questions or suggestions, please email <a href="mailto:admin@brandonfoltz.com">admin@brandonfoltz.com</a> or tweet <a href="https://twitter.com/CrueltyGame">@CrueltyGame</a>.</p>
<h2>FAQ / Other Stuff</h2>
<ul>
    <li><b>How do I make an account?</b></li>
    <ul>
        <li><?= $this->Html->link("By clicking here.", ['controller' => 'users', 'action' => 'add']) ?></li>
    </ul>
    <br/>
    <?php if (!empty($loggedUser)): ?>
    <li><b>How do I change my password?</b></li>
    <ul>
        <li>If you're logged in, you can <?= $this->Html->link("edit your account and preferences here.", ['controller' => 'users', 'action' => 'edit', $loggedUser->id]) ?></li>
    </ul>
    <br/>
    <?php endif; ?>
    <li><b>How can I make a bot to play?</b></li>
    <ul>
        <li>Check out <?= $this->Html->link("the API.", ['controller' => 'users', 'action' => 'api']) ?></li>
    </ul>
    <br/>
    <li><b>I think I found a bug...</b></li>
    <ul>
        <li>Email me at <a href="mailto:admin@brandonfoltz.com">admin@brandonfoltz.com</a>, tweet <a href="https://twitter.com/CrueltyGame">@CrueltyGame</a>, or post an issue in the <a href="https://github.com/JayWalker512/CrueltyGame">Github repository.</a></li>
    </ul>
</ul>
<a class="twitter-timeline" href="https://twitter.com/CrueltyGame?ref_src=twsrc%5Etfw">Tweets by CrueltyGame</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>