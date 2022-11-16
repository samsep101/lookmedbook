<?php
    $ap = $clinic->additional_params;

if(!empty($ap)) : ?>
    <div class="type-and-service-area">
        <?php if (!empty($ap['multidisciplinary'])) : ?>
            <div class="multidisciplinary">
                Многопрофильная клиника
            </div>
        <?php endif; ?>

        <div class="types-services-list">
            <?php if (!empty($ap['twenty-four-hours'])) : ?>
                <div class="tsl-item hint--top-right" aria-label="Круглосуточная">
                    <div class="tsl-icon icon-twenty-four-hours"></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($ap['accepts-children'])) : ?>
                <div class="tsl-item hint--top-right" aria-label="Принимает детей" >
                    <div class="tsl-icon icon-accepts-children"></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($ap['have-ramp'])) : ?>
                <div class="tsl-item hint--top-right" aria-label="Есть пандус">
                    <div class="tsl-icon icon-have-ramp"></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($ap['payment-cards'])) : ?>
                <div class="tsl-item hint--top-right" aria-label="Оплата картой">
                    <div class="tsl-icon icon-payment-cards"></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($ap['medical-certificates'])) : ?>
                <div class="tsl-item hint--top-right" aria-label="Больничные листы">
                    <div class="tsl-icon icon-medical-certificates"></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($ap['leave-the-house'])) : ?>
                <div class="tsl-item hint--top-right" aria-label="Выезд на дом">
                    <div class="tsl-icon icon-leave-the-house"></div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
    </div>
<?php endif; ?>