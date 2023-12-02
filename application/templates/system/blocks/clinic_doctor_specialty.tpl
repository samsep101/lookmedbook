<table>
    <thead>
    <tr>
        <td>№</td>
        <td>Клиника</td>
        <td>Докторов</td>
        <td>Специализаций по докторам</td>
        <td>Специализаций по клиние</td>
    </tr>
    </thead>

    <tbody>
    <?php if (isset($doctors_clinics_specialties) && $doctors_clinics_specialties): ?>
        <?php $number = 1; ?>
            <?php foreach ($doctors_clinics_specialties as $information): ?>
            <tr>
                <td><?php echo $number; ?></td>
                <td><?php echo $information['clinic']; ?></td>
                <td><?php echo $information['doctor']; ?></td>
                <td><?php echo $information['doctor_specialties']; ?></td>
                <td><?php echo $information['clinic_specialties']; ?></td>
            </tr>
            <?php $number++; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
