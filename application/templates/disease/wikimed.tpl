<div id="ban_wikimed" >
	<div class="info-box">
		<div class="action">Акция</div>
		<p class="titled">Клиника WikiMed.</p>
		<p class="desc">Комплексное дуплексное сканирование сосудов (вен, артерий, брахиоцефальных и транскраниальных сосудов)</p>
		<p class="price">6000 руб. (<span class="old_price">8200 руб.</span>)</p>
		<div class="wiki_logo"></div>
	</div>
	<div class="info-box">
		<div class="action">Акция</div>
		<p class="titled">Клиника WikiMed.</p>
		<p class="desc">Консультации врачей терапевта, гинеколога, уролога, флеболога с дуплексным сканирование вен. Консультация терапевта бесплатно.</p>
		7000 руб. (<span class="old_price">8300 руб.</span>)</p>
		<div class="wiki_logo"></div>
	</div>
	<div class="info-box">
		<div class="action">Акция</div>
		<p class="titled">Клиника WikiMed.</p>
		<p class="desc">Полное ультразвуковое обследование женщин (органы брюшной полости, малого таза, мочевыделительной системы, молочных и щитовидной желез)</p>
		<p class="price">5600 руб. (<span class="old_price">8100 руб.</span>)</p>
		<div class="wiki_logo"></div>
	</div>
	<div class="info-box">
		<div class="action">Акция</div>
		<p class="titled">Клиника WikiMed.</p>
		<p class="desc">Полное ультразвуковое обследование мужчин  (органы брюшной полости,  мошонки, мочевыделительной системы, предстательной и щитовидной желез)</p>
		<p class="price">4200 руб. (<span class="old_price">7700 руб.</span>)</p>
		<div class="wiki_logo"></div>
	</div>
</div>

<script language="JavaScript">
		$('#ban_wikimed').click(function(){ window.location = '/clinic/klinika-WikiMed'; return false; });
</script>

<style>
    #ban_wikimed {
        display: none;
        color: black;
        cursor: pointer;
    }

    #ban_wikimed > div {
        margin: 10px 0 20px;
        overflow: hidden;
    }

    #ban_wikimed .wiki_logo {
        background: url(/media/images/banners/wikimed.png) left bottom no-repeat;
        height: 28px;
        margin: 0 0 -15px -15px;
    }

    #ban_wikimed .titled {
        font-weight: bold;
        font-size: 16px;
    }

    #ban_wikimed .desc {
    }

    #ban_wikimed .price {
        color: #00ADD7;
        margin: 0;
        float: none;
    }

    #ban_wikimed .old_price {
        color: red;
        text-decoration: line-through;
    }

    #ban_wikimed .action {
        background: red none repeat scroll 0% 0%;
        color: white;
        font-size: 14px;
        font-weight: bold;
        width: 40%;
        text-align: center;
        float: right;
        margin: -15px -22px 0px 0px;
        padding: 4px 0 2px;
    }
</style>

