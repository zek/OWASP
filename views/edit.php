<h2>Student Info</h2>
<?php if ($this->update_error): ?>
    <p style="color: red">The time is passed. You cannot change student grades.</p>
    <ul style="color: red">
        <li>It's too late to change the grades even if you are teacher right now :(</li>
        <li>I belive that there is another way to do it.</li>
    </ul>
<?php endif; ?>
<div>
    <h6>Grades of <?= $this->student['name'] ?> <?= $this->student['surname'] ?></h6>

    <form method="POST" action="?">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" value="<?=$this->student['name']?>" disabled>
            </div>
            <div class="form-group col-md-6">
                <label for="surname">Surname</label>
                <input type="text" class="form-control" id="name" value="<?=$this->student['surname']?>" disabled>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" value="<?=$this->student['username']?>" disabled>
            </div>
            <div class="form-group col-md-6">
                <label for="country">Country</label>
                <input type="text" class="form-control" id="country" value="<?=$this->student['country']?>" name="country">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="faculty">Faculty</label>
                <input type="text" class="form-control" id="faculty" value="<?=$this->student['faculty']?>" name="faculty">
            </div>
            <div class="form-group col-md-6">
                <label for="field">Field</label>
                <input type="text" class="form-control" id="field" value="<?=$this->student['field']?>" name="field">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>

    </form>
</div>