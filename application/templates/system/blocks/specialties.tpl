<table style="float: left">
    <thead>
    <tr>
        <td>Специализации без докторов</td>
    </tr>
    </thead>

    <tbody>
    <?php if (isset($specialties_without_doctor) && $specialties_without_doctor): ?>
        <?php foreach ($specialties_without_doctor as $specialty): ?>
            <tr>
                <td><?php echo $specialty['name']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <td>Специализации без клиник</td>
    </tr>
    </thead>

    <tbody>
    <?php if (isset($specialties_without_clinic) && $specialties_without_clinic): ?>
        <?php foreach ($specialties_without_clinic as $specialty): ?>
            <tr>
                <td><?php echo $specialty['name']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>