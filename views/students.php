<h2>Students</h2>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Country</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->students as $student): ?>
            <tr>
                <td><?= $student["user_id"] ?></td>
                <td><?= $student["username"] ?></td>
                <td><?= $student["name"] ?></td>
                <td><?= $student["surname"] ?></td>
                <td><?= $student["country"] ?></td>
                <td><a href="/student/<?=$student['user_id']?>/grades">Grades</a>
                    <a href="/student/<?=$student['user_id']?>/edit">Info</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
