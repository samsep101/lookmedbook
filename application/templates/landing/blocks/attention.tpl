<?php if (isset($specialty)):?>
    <?php if ($specialty->getId() == SpecialtyModel::OPHTHALMOLOGIST):?>
        <span class="">Многие откладывают визит к <?php if (isset($specialty)) echo $specialty->dative_name;?> до тех пор, пока не появятся серьезные проблемы. <?php echo SITE_NAME; ?></span>
        <span class="">напоминает: чем раньше Вы обратитесь за помощью к специалисту, тем больше шансов сохранить зрение и</span>
        <span class="">снизить риск развития осложнений.</span>
    <?php elseif ($specialty->getId() == SpecialtyModel::ALLERGIST_IMMUNOLOGIST):?>
        <span class="">Многие откладывают визит к <?php if (isset($specialty)) echo $specialty->dative_name;?> до тех пор, пока не появятся серьезные проблемы.</span>
        <span class=""><?php echo SITE_NAME; ?> напоминает: чем раньше Вы обратитесь за помощью к специалисту, тем больше шансов сохранить</span>
        <span class="">здоровье и снизить риск развития осложнений.</span>
    <?php elseif ($specialty->getId() == SpecialtyModel::NEUROLOGIST || $specialty->getId() == SpecialtyModel::OTOLARYNGOLOGIST):?>
        <span class="">Многие откладывают визит к <?php if (isset($specialty)) echo $specialty->dative_name;?> до тех пор, пока не появятся серьезные проблемы. <?php echo SITE_NAME; ?></span>
        <span class="">напоминает: чем раньше Вы обратитесь за помощью к специалисту, тем больше шансов сохранить здоровье</span>
        <span class="">и снизить риск развития осложнений.</span>
    <?php endif;?>
<?php endif;?>