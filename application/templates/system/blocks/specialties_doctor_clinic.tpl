<table>
    <thead>
    <tr>
        <td>Специализация</td>
        <td>Докторов</td>
        <td>Клиник</td>
    </tr>
    </thead>

    <tbody>
    <?php if (isset($doctors_clinics_with_specialties) && $doctors_clinics_with_specialties): ?>
        <?php foreach ($doctors_clinics_with_specialties as $information): ?>
            <tr>
                <td><?php echo $information['specialty']; ?></td>
                <td><?php echo $information['doctors']; ?></td>
                <td><?php echo $information['clinics']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
