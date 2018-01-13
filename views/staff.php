<h2>Academic Staff</h2>
<form method="POST">
    <input type="text" name="search" value="<?= $this->request->param('search') ?>">
    <input type="submit" value="Search...">
</form>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->staff as $staff): ?>
            <tr>
                <td><?= $staff["staff_id"] ?></td>
                <td><?= $staff["name"] ?></td>
                <td><?= $staff["surname"] ?></td>
                <td><?= $staff["email"] ?></td>
                <td><?= $staff["phone"] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
