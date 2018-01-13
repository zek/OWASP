<h2>Student Grades</h2>
<?php if ($this->update_error): ?>
    <p style="color: red">Student account is locked. You cannot make any changes on student grades.</p>
    <ul style="color: red">
        <li>It's too late to change the grades even if you are teacher right now :(</li>
        <li>I belive that there is another way to do it.</li>
    </ul>
<?php endif; ?>
<?php if ($this->mission_complete): ?>
    <p style="color: green">Apparently you've unlocked your account!</p>
    <p style="color: green">Congratulations! Now you saved your scholarship ;)</p>
<?php endif; ?>
<div class="table-responsive">
    <form method="POST" action="?">
        <h6>Grades of <?= $this->student['name'] ?> <?= $this->student['surname'] ?></h6>
        <table class="table table-striped">
            <thead>
            <tr>
                <td>Lesson Code</td>
                <td>Lesson Name</td>
                <td style="text-align: center">Ects</td>
                <td style="text-align: center">Grade</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->lectures as $lecture): ?>
                <tr>
                    <td class="textC">
                        <?= $lecture["code"] ?>
                    </td>
                    <td class="textC">
                        <?= $lecture["name"] ?>
                    </td>
                    <td style="text-align : center">
                        <?= $lecture["ects"] ?>
                    </td>
                    <td style="text-align : center; width:20%">
                        <select class="1" name="grades[<?=$lecture['lesson_id']?>]" name="LetterGradeValue">
                            <option <?= $lecture["grade"] == 'A' ? 'selected="selected"' : '' ?> value="A">A</option>
                            <option <?= $lecture["grade"] == 'B1' ? 'selected="selected"' : '' ?> value="B1">B1</option>
                            <option <?= $lecture["grade"] == 'B2' ? 'selected="selected"' : '' ?> value="B2">B2</option>
                            <option <?= $lecture["grade"] == 'B3' ? 'selected="selected"' : '' ?> value="B3">B3</option>
                            <option <?= $lecture["grade"] == 'C1' ? 'selected="selected"' : '' ?> value="C1">C1</option>
                            <option <?= $lecture["grade"] == 'C2' ? 'selected="selected"' : '' ?> value="C2">C2</option>
                            <option <?= $lecture["grade"] == 'C3' ? 'selected="selected"' : '' ?> value="C3">C3</option>
                            <option <?= $lecture["grade"] == 'F1' ? 'selected="selected"' : '' ?> value="F1">F1</option>
                            <option <?= $lecture["grade"] == 'F2' ? 'selected="selected"' : '' ?> value="F2">F2</option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <input type="submit" value="Update!" class="btn btn-success">
        <?php if(!$this->student['is_locked']) : ?>
        <a href="/student/<?=$this->student['user_id']?>/lock" class="btn btn-danger float-right">Lock Changes</a>
        <?php endif; ?>
    </form>

</div>