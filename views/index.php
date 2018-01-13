<h1>Dashboard</h1>
<p>Dear <b><?=$this->user->username?></b> Welcome to the CAMPUS INFORMATION MANAGEMENT SYSTEM</p>

<h3>README</h3>
<ol>
    <li>You failed your course and you have to pass this course otherwise you will return your ERASMUS scholarship.</li>
    <li>So maybe you want to access the system and change your grade :)</li>
    <li>You notice that they added a new section to website which is "Academic Staff". </li>
    <li>Something there may help you!</li>
</ol>
<?php if($this->user->is_admin): ?>
    <h4>One more step to save your scholarship</h4>
    <ol>
        <li>Congratulations you logged in as admin user.</li>
    </ol>
<?php endif; ?>