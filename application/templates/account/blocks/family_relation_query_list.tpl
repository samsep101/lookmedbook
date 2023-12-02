<?php if ($relations_moderate):?>

    <div class="request-block">
        <?php $query_counter = 1;?>
        <?php foreach ($relations_moderate as $relation) {
            $phone = ModelManagerFactory::getByName('account_phone')->getConfirmedOneByAccountId($relation->account_id);
            //if ($relation->phone_confirm) $phone = $relation->phone;
            //else $phone = '';
            ?>

            <div class="request-cont flo">
                <?php if ($query_counter == 1):?><h3>Запрос</h3><?php endif?>
                <?php $query_counter++;?>
                <div class="descr">
                    <p>Данный пользователь утверждает, что вы родственники</p>
                    <div class="item flo">
                        <div class="name"><?php echo $relation->last_name?> <?php echo $relation->first_name; ?> <?php echo $relation->middle_name; ?>
                            <?php if ($phone):?>
                                <span class="phone">+<?php echo $phone->phone; ?></span>
                            <?php endif?>
                        </div>
                        <div class="family-status"><?php echo FamilyRelationViewHelper::getReversedRelation($relation->family_relation_status->name, $relation->account_id); ?></div>
                    </div>
                </div>
                <div class="btns"> <span class="btn-4">
                    <input type="submit" class="confirm-relation" data-id="<?php echo $relation->relation_id; ?>" value="Принять">
                    </span> <span class="btn-5">
                    <input type="submit" class="delete-relation-moderate" data-id="<?php echo $relation->relation_id; ?>" value="Отклонить">
                    </span> </div>
            </div>

        <?php }?>

    </div>
<?php endif?>