<div class="container-fluid">
    <div class="row">
        <header class="header" data-active="<?=$controller?>">
            <div class="container">
                <div class="flo">

                    <a class="logo" href="/" title="Портал медицинских услуг в Москве – LookMedBook">
                        <img class="main-logo" src="/media/images/blank.png" alt="Портал медицинских услуг в Москве – LookMedBook">
                    </a>

                    <nav>
                        <a id="menu-opener" href="#" class="toggle-opener"><i class="glyphicon glyphicon-menu-hamburger"></i></a>
                        <ul id="menu-container" class="nav-table animate-all">
                            <li><a class="doctor-link" href="/doctor" data-controller="doctor">Врачи</a></li>
                            <li><a class="clinic-link" href="/clinic" data-controller="clinic">Клиники</a></li>
                            <li><a class="disease-link" href="/disease" data-controller="disease">Заболевания</a></li>
                            <li><a class="" href="/shop/catalog" data-controller="shop">Лекарства</a></li>
                            <li><a class="" href="/action" data-controller="action">Акции</a></li>
                            <li><a class="" href="/uslugi" data-controller="uslugi">Услуги</a></li>
                        </ul>
                    </nav>

                    <form action="/search/results" method="GET">
                        <div class="quick-search" id="disease-quick-search">
                            <div class="fields">
                                <input type="text" class="quick-search-input" autocomplete="off" name="query" placeholder="Найти врача, клинику">
                                <input type="submit" class="quick-search-submit" data-action-for-counters="top" value="">
                            </div>
                            <ul class="drop-menu" style="">

                            </ul>
                        </div>
                    </form>

                    <div id="authorization-block-on-disease-page" class="no-auth-buttons">
                        <a class="btn-enter reg-linking ff-bold" href="javascript:void(0);">Войти</a>
                        <a class="btn-reg reg-linking ff-bold" data-action-for-counters="top-reg" href="javascript:void(0)">
                            <span>Зарегистрироваться</span>
                            <i class="glyphicon glyphicon-user"><i class="glyphicon glyphicon-plus"></i></i>
                        </a>
                    </div>

                </div>

                <div class="clearfix"></div>
            </div>
        </header>
    </div>
</div>