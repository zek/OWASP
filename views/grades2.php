<h2>Student Grades</h2>
<div class="table-responsive">
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
                    <?= $lecture["grade"] ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>