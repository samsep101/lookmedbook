<?php if ($relations || $reversed_relations):?>

    <div class="in-club">
        <h2>Семья в клубе</h2>
        <div class="in-club-block">
            <?php if ($relations) {?>
                <?php foreach ($relations as $relation) {?>
                <?php $phone = ModelManagerFactory::getByName('account_phone')->getConfirmedOneByAccountId($relation->account2_id);?>

                <div class="item flo">
                    <div class="name"><?php echo $relation->last_name; ?> <?php echo $relation->first_name; ?> <?php echo $relation->middle_name; ?>
                        <?php if ($phone):?>
                            <span class="phone">+<?php echo $phone->phone; ?></span>
                        <?php endif?>
                    </div>
                    <div class="family-status"><?php echo FamilyRelationViewHelper::getReversedRelation($relation->family_relation_status->name, $relation->account2_id); ?></div>
                    <span class="btn-5">
                        <input type="submit" class="delete-relation" data-id="<?php echo $relation->relation_id; ?>" value="Удалить">
                    </span>
                </div>

                <?php }?>
            <?php }?>

            <?php if ($reversed_relations) {?>
            <?php foreach ($reversed_relations as $relation) {?>
                <?php $phone = ModelManagerFactory::getByName('account_phone')->getConfirmedOneByAccountId($relation->account1_id);?>

                <div class="item flo">
                    <div class="name"><?php echo $relation->last_name; ?> <?php echo $relation->first_name; ?> <?php echo $relation->middle_name; ?>
                        <?php if ($phone):?>
                            <span class="phone">+<?php echo $phone->phone; ?></span>
                            <?php endif?>
                    </div>
                    <div class="family-status"><?php echo $relation->family_relation_status->name; ?></div>
                    <span class="btn-5">
                        <input type="submit" class="delete-relation" data-id="<?php echo $relation->relation_id; ?>" value="Удалить">
                    </span>
                </div>

                <?php }?>
            <?php }?>
        </div>
    </div>

<?php endif?>