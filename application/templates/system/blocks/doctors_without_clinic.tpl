<table>
    <thead>
    <tr>
        <td>№</td>
        <td>Фамилия</td>
        <td>Имя</td>
        <td>Отчество</td>
    </tr>
    </thead>

    <tbody>
    <?php if (isset($doctors) && $doctors): ?>
        <?php $number = 1; ?>
        <?php foreach ($doctors as $doctor): ?>
            <tr>
                <td><?php echo $number; ?></td>
                <td><?php echo $doctor['last_name']; ?></td>
                <td><?php echo $doctor['first_name']; ?></td>
                <td><?php echo $doctor['second_name']; ?></td>
            </tr>
        <?php $number++; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
