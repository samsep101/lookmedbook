<?php
    /**
     * @var string $login
     * @var int $role_id
     * @var ClinicModel[] $clinics
     * @var int $clinic_id
     * @var int $city_id
     * @var UserModel $user
     * @var CityModel[] $cities
     */
?>
<div class="users-filter">
    <div>
        <form action="/manage/account" method="GET">
            Фамилия: <input type="text" name="last_name" value="<?php echo $last_name; ?>" />
            Имя: <input type="text" name="first_name" value="<?php echo $first_name; ?>" />
            Отчество: <input type="text" name="middle_name" value="<?php echo $middle_name; ?>" /><br/>
            Телефон: <input type="text" name="phone" value="<?php echo $phone; ?>" />
            Email: <input type="text" name="email" value="<?php echo $email; ?>" />

            <input type="submit" value="Применить" />
        </form>
    </div>
</div>

<input class="btn-appoint block-button" type="submit" value="Добавить" onclick="window.location='/manage/account/create';" />
<script language="JavaScript">
  $( document ).ready(function(){
    var ths = $('table.account-list th');
    ths.css({cursor:"pointer"});
    ths.click(function(){
      var tag = $(this);
      var field = tag.attr('class');
      var loc = ''+window.location.search;
      var asc = 1;
      if(!loc){
        loc = '?';
      }else if(loc.indexOf("sort=")<=0){
        loc += '&';
      }else{
        var reg = /^(.*)(&|\?)sort=([^&]*)&asc=([^&]*)(&.*)?$/;
        var mch = loc.match(reg);
        if(mch && mch.length) {
          loc = mch[1]?mch[1]:'?';
          if(mch[3]==field) {
            asc = -1;
          }
          loc += (typeof mch[5]== 'undefined'?'':mch[5]);
        }
        loc += '&';
      }
      loc += "sort="+field+'&asc='+asc;
      window.location = "/manage/account"+loc;
      return false;
    });
    $('table.account-list a.delete-line').click(function(){
      var tag = $(this);
      if(!confirm("Операция ликвидации аккаунта необратима. Вы уверены?")) {
        return;
      }
      var id = tag.attr('data-id');

      var form_data = $('.users-filter form').serializeArray();
      var locat = '/manage/account?del_id='+id;
      for(var i in form_data) {
        if(form_data[i].value) {
          locat += '&'+form_data[i].name+'='+form_data[i].value;
        }
      }
      window.location = locat;
      return false;
    });


  });

</script>
<?php
if ($accounts) {
  $counter = 1;?>
  <table class="styled-table block account-list">
    <thead>
      <th class="first_name">Имя</th>
      <th class="middle_name">Отчество</th>
      <th class="last_name">Фамилия</th>
      <th class="nick">Имя на сайте</th>
      <th class="phone">Телефон</th>
      <th class="email">Email</th>
      <th class="regdate">Дата регистрации</th>
      <th></th>
      <th></th>
    </thead>
    <?php foreach($accounts as $account) { ?>
      <tr>
        <?php foreach(['first_name', 'middle_name', 'last_name', 'nick', 'phone', 'email', 'dt', ] as $fname) { ?>
          <td><?php echo $account->$fname; ?></td>
        <?php } ?>
        <td>
          <a href="/manage/account/edit?id=<?php echo $account->id; ?>">редактировать</a>
        </td>
        <td>
          <a class="delete-line" data-id="<?php echo $account->id; ?>" href="#">удалить</a>
        </td>
      </tr>
        <?php $counter++;?>
        <?php }; ?>
    </table>

  <div class="pager"><?php
    $i = 1;
    $pager = [];
    while(($i-1)*$page_size<$records_count) {
      if($i==$page_nm){
        $pager[] = '<span>'.$i.'</span>';
      }else {
        $pager[] = '<a href="/manage/account?' . $search_line . '&page=' . $i . '">' . $i . '</a>';
      }
      $i++;
    }
    if(count($pager)>1) {
      echo implode(' ', $pager);
    }
  ?></div>



<?php } else { ?>
    по данным параметрам ничего не найдено
<?php } ?>